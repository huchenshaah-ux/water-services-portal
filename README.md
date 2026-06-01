# Water Services Portal

Modern AdminLTE-style dashboard for managing water service applications (Fenaka-style workflow).

## Stack

| Component | Version |
|-----------|---------|
| Laravel | 12 |
| PHP | 8.3+ |
| MySQL | 8 |
| AdminLTE | 3 |
| Bootstrap | 5 |
| Chart.js | 4 |
| Laravel Excel | 3.x (dev/local; CSV on Vercel) |

## Features

- **Dashboard** — KPI cards, monthly chart, status/category charts, recent applications, staff activity, performance
- **Applications** — CRUD, DataTables search/filter, status workflow, QR code, PDF forms
- **Excel** — Bulk import (`Applications for water services.xlsx`), export, duplicate detection, import summary
- **Reports** — Daily, monthly, connections, service categories, printable/PDF
- **Users** — Roles: Admin, Supervisor, Staff, Viewer
- **i18n** — English / Dhivehi switcher
- **PWA** — Manifest, service worker, offline caching
- **Extras** — Dark mode, audit logs, WhatsApp quick contact, system alerts

## Quick Start

### Prerequisites

Install [PHP 8.3+](https://windows.php.net/download/), [Composer](https://getcomposer.org/), and [MySQL 8](https://dev.mysql.com/downloads/).

### Installation

```bash
cd water-services-portal
composer install
copy .env.example .env
php artisan key:generate

# Configure DB in .env, then:
php artisan migrate --seed
php artisan serve
```

Open http://localhost:8000

### Demo logins

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@waterservices.local | password |
| Supervisor | supervisor@waterservices.local | password |
| Staff | staff@waterservices.local | password |
| Viewer | viewer@waterservices.local | password |

## Excel import

Upload `storage/templates/Applications for water services.xlsx` (or use the CSV template).

Expected columns (header row):

`entry_no`, `application_date`, `applicant_name`, `id_number`, `contact_number`, `address`, `service_address`, `billing_address`, `service_category`, `status`, `fenaka_id`, `remarks`

## API endpoints

Enable Sanctum for token auth:

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/applications` | List applications |
| POST | `/api/applications` | Create |
| PUT | `/api/applications/{id}` | Update |
| DELETE | `/api/applications/{id}` | Delete |
| GET | `/api/reports` | Report aggregates |
| POST | `/api/excel/import` | Import file |
| GET | `/api/excel/export` | Export file |

## Project structure

```
water-services-portal/
├── app/
│   ├── Exports/ApplicationsExport.php
│   ├── Http/Controllers/
│   ├── Imports/ApplicationsImport.php
│   ├── Models/
│   └── Services/AuditService.php
├── database/migrations/
├── resources/views/
├── routes/web.php, api.php
└── public/ (PWA manifest, service worker)
```

## Environment

```env
WHATSAPP_NUMBER=9600000000
DB_DATABASE=water_services
```

## Deploy on Vercel

See **[DEPLOY_VERCEL.md](DEPLOY_VERCEL.md)** for step-by-step instructions (external MySQL, env vars, CLI).

Quick summary: push to GitHub → import on Vercel → set `APP_KEY`, `DB_*`, and `/tmp` cache paths → redeploy.

## License

MIT
