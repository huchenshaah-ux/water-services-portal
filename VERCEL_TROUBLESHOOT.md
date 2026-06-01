# Fix HTTP 500 on Vercel

Your screenshot (Chrome “This page isn’t working” / HTTP ERROR 500) means the **serverless function crashes** before Laravel can respond.

## A. Fix Vercel settings (most common)

In [Vercel Dashboard](https://vercel.com) → your project → **Settings**:

| Setting | Required value |
|---------|----------------|
| **Node.js Version** | **22.x** (not 18, not 20) |
| **Framework Preset** | **Other** |
| **Root Directory** | *(leave empty)* |
| **Build Command** | *(empty)* |
| **Output Directory** | *(empty)* |

> `vercel-php` must match Node 22. Wrong Node → deploy may show **invalid runtime** or silent 500.

## B. Environment variables

**Settings → Environment Variables** → add for **Production**, then **Redeploy**:

```
APP_KEY=base64:xxxx          ← run: php artisan key:generate --show
APP_ENV=production
APP_DEBUG=true               ← set true until fixed, then false
APP_URL=https://water-services-portal.vercel.app
VERCEL=1

SESSION_DRIVER=cookie
SESSION_SECURE_COOKIE=true
CACHE_STORE=array
LOG_CHANNEL=stderr

APP_CONFIG_CACHE=/tmp/config.php
APP_ROUTES_CACHE=/tmp/routes.php
APP_SERVICES_CACHE=/tmp/services.php
VIEW_COMPILED_PATH=/tmp/views

DB_CONNECTION=mysql
DB_HOST=...
DB_PORT=3306
DB_DATABASE=water_services
DB_USERNAME=...
DB_PASSWORD=...
```

Without **APP_KEY**, Laravel always returns 500.

## C. Test in order

After redeploy (wait ~2 min):

1. https://water-services-portal.vercel.app/hello → must show **`OK`**
2. https://water-services-portal.vercel.app/ping → JSON with `"vendor": true`
3. https://water-services-portal.vercel.app/login → login form

If **/hello** is still 500 → open **Deployments → latest → Build Logs** and search for:

- `invalid runtime`
- `composer install` failed
- `250MB` / size limit

## D. Redeploy without cache

Deployments → ⋮ on latest → **Redeploy** → enable **Clear build cache**.

## E. If Vercel still fails — use Render (recommended)

Laravel + MySQL works reliably on Render:

1. https://render.com → **New +** → **Blueprint**
2. Connect repo `huchenshaah-ux/water-services-portal`
3. Use included `render.yaml` + `Dockerfile`
4. Shell: `php artisan migrate --seed`

---

**Latest repo fix:** Node 22, `vercel-php@0.7.4`, Composer build without `artisan` during install.
