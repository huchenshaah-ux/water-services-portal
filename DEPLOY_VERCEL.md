# Deploy Water Services Portal on Vercel

Laravel runs on Vercel as a **serverless PHP function** ([vercel-php](https://github.com/vercel-community/php)). You still need a **hosted MySQL database** (Vercel does not provide MySQL).

## Important limitations

| Feature | On Vercel |
|---------|-----------|
| MySQL | Use [PlanetScale](https://planetscale.com), [Neon](https://neon.tech), [Railway](https://railway.app), or similar |
| Sessions | Use `cookie` or **Upstash Redis** (recommended for admin apps) |
| Excel upload | `/tmp` only — large imports may hit time/size limits; consider S3 later |
| `php artisan migrate` | Run locally or via CI against production DB, not on Vercel |
| Cold starts | First request after idle can be slow (5–15s) |

If deploy fails with **250MB size limit**, use [vercel-laravel-go](https://github.com/kristiansntsdev/vercel-laravel-go) (compresses `vendor/`).

---

## 1. Push to GitHub

```bash
cd water-services-portal
git init
git add .
git commit -m "Prepare for Vercel"
git remote add origin https://github.com/YOUR_USER/water-services-portal.git
git push -u origin main
```

---

## 2. Create external database

Example (PlanetScale / any MySQL 8):

1. Create database `water_services`
2. Note host, user, password, SSL if required
3. Run migrations **from your PC** (not Vercel):

```bash
# .env pointed at production DB
php artisan migrate --seed
```

---

## 3. Import project on Vercel

1. Go to [vercel.com/new](https://vercel.com/new)
2. Import your GitHub repo
3. **Root directory:** `water-services-portal` (if monorepo) or repo root
4. **Framework preset:** Other
5. Deploy (first build may fail until env vars are set)

---

## 4. Environment variables (Vercel Dashboard → Settings → Environment Variables)

Set for **Production** (and Preview if needed):

| Variable | Example |
|----------|---------|
| `APP_KEY` | Run `php artisan key:generate --show` locally |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://your-app.vercel.app` |
| `APP_CONFIG_CACHE` | `/tmp/config.php` |
| `APP_EVENTS_CACHE` | `/tmp/events.php` |
| `APP_PACKAGES_CACHE` | `/tmp/packages.php` |
| `APP_ROUTES_CACHE` | `/tmp/routes.php` |
| `APP_SERVICES_CACHE` | `/tmp/services.php` |
| `VIEW_COMPILED_PATH` | `/tmp/views` |
| `LOG_CHANNEL` | `stderr` |
| `SESSION_DRIVER` | `cookie` |
| `SESSION_SECURE_COOKIE` | `true` |
| `CACHE_DRIVER` | `array` |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | your-db-host |
| `DB_PORT` | `3306` |
| `DB_DATABASE` | `water_services` |
| `DB_USERNAME` | `...` |
| `DB_PASSWORD` | `...` |
| `WHATSAPP_NUMBER` | `9600000000` |

**SSL MySQL** (PlanetScale / many clouds):

| Variable | Value |
|----------|--------|
| `MYSQL_ATTR_SSL_CA` | `/etc/pki/tls/certs/ca-bundle.crt` |

**Optional — Redis sessions** ([Upstash](https://upstash.com) free tier):

| Variable | Value |
|----------|--------|
| `SESSION_DRIVER` | `redis` |
| `CACHE_STORE` | `redis` |
| `REDIS_URL` | `rediss://...` from Upstash |

Redeploy after saving variables.

---

## 5. Deploy from CLI (optional)

```bash
npm i -g vercel
cd water-services-portal
vercel login
vercel          # preview
vercel --prod   # production
```

---

## 6. Verify

1. Open `https://your-app.vercel.app`
2. Login: `admin@waterservices.local` / `password` (if you seeded production DB)
3. Check dashboard and applications list

---

## Files added for Vercel

```
api/index.php      → serverless entry
vercel.json        → PHP runtime + routes
.vercelignore      → skip vendor (built on deploy)
```

---

## Fix HTTP 500 errors

**Test diagnostics:** open `https://your-app.vercel.app/ping`  
You should see JSON with `"vendor": true` and `"app_key_set": true`.

**Vercel project settings:**
- **Node.js version:** 18.x (Node 20 breaks vercel-php for many projects)
- **Root directory:** `water-services-portal` if the repo is not at repo root

Most 500s on Vercel are caused by missing env vars or wrong session/cache drivers.

1. **Set `APP_KEY`** — required. Generate locally: `php artisan key:generate --show`
2. **Use cookie sessions** — `SESSION_DRIVER=cookie` (not `database` unless migrations ran on production DB)
3. **Use array cache** — `CACHE_STORE=array`
4. **Set `/tmp` paths** — copy from [.env.vercel.example](.env.vercel.example)
5. **Set `VERCEL=1`** and `APP_URL=https://water-services-portal.vercel.app`
6. **Database** — wrong `DB_*` causes errors after login; login page should work without DB if session is `cookie`
7. **View logs** — Vercel → Project → Deployments → your deployment → **Functions** → `api/index.php` → Logs

After changing env vars, **Redeploy** (required).

## Better alternatives for Laravel

If you need reliable file uploads, queues, or `artisan` on the server, prefer:

- [Laravel Forge](https://forge.laravel.com) + VPS
- [Railway](https://railway.app)
- [Render](https://render.com)
- [Fly.io](https://fly.io)

Vercel works best for lighter Laravel apps or APIs.
