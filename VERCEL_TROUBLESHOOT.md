# Vercel still returns 500?

## Step 1 — Test PHP (no Laravel)

Open: **https://water-services-portal.vercel.app/hello**

| Result | Meaning |
|--------|---------|
| `OK` | PHP works → fix env vars (Step 2) |
| 500 | PHP runtime broken → Step 3 or use Render |

## Step 2 — Required Vercel environment variables

Project → **Settings** → **Environment Variables** → Production:

```
APP_KEY=base64:....        (from: php artisan key:generate --show)
APP_ENV=production
APP_DEBUG=false
APP_URL=https://water-services-portal.vercel.app
VERCEL=1

APP_CONFIG_CACHE=/tmp/config.php
APP_ROUTES_CACHE=/tmp/routes.php
APP_SERVICES_CACHE=/tmp/services.php
VIEW_COMPILED_PATH=/tmp/views

LOG_CHANNEL=stderr
SESSION_DRIVER=cookie
SESSION_SECURE_COOKIE=true
CACHE_STORE=array

DB_CONNECTION=mysql
DB_HOST=...
DB_PORT=3306
DB_DATABASE=water_services
DB_USERNAME=...
DB_PASSWORD=...
```

Then **Deployments → Redeploy** (required after env changes).

## Step 3 — Vercel project settings

| Setting | Value |
|---------|--------|
| Root Directory | *(empty)* or `water-services-portal` if monorepo |
| Framework Preset | **Other** (not Laravel) |
| Node.js Version | **18.x** (not 20) |
| Build Command | *(leave empty — vercel-php runs Composer)* |
| Output Directory | *(leave empty)* |

## Step 4 — Read build logs

Deployments → latest → **Building** tab.

Look for:

- `composer install` failed
- `250MB` size limit exceeded
- `vercel-php` / runtime errors

## Recommended: deploy on Render (easier for Laravel)

This repo includes `render.yaml` + `Dockerfile`.

1. Go to [render.com](https://render.com) → New → Blueprint
2. Connect GitHub repo `water-services-portal`
3. Render creates web service + PostgreSQL/MySQL
4. Set `APP_KEY` and run migrations from Shell:

```bash
php artisan migrate --seed
```

Laravel works reliably on Render/Railway/Fly.io with full PHP, storage, and MySQL.
