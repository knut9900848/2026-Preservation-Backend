# 2026-Preservation-Backend

Equipment Maintenance and Preservation Management System — a Laravel 12 REST API with role-based access control.

## Tech Stack

- **Backend:** PHP 8.2+, Laravel 12, Eloquent ORM
- **Auth:** Laravel Sanctum (token-based), Spatie Permission (RBAC)
- **Database:** SQLite (dev), supports multi-DB
- **Frontend:** Minimal — Blade, Vite 7, Tailwind CSS v4, Axios
- **Testing:** PHPUnit 11.5

## Key Directories

- `app/Http/Controllers/` — 14 controllers (REST + `API/AuthenticationController`)
- `app/Http/Resources/` — 7 API Resource transformers
- `app/Models/` — 14 Eloquent models
- `database/migrations/` — 25 migrations
- `routes/api.php` — All API endpoints (~40+)
- `database/seeders/` — Database seeders

## Core Domain

**CheckSheets** are the primary feature — maintenance worksheets that flow through: Draft → Completed → Reviewed → Approved. They are generated from **Activities** assigned to **Equipment**, with auto-generated sheet numbers (`{tag_no}-{activity_code}-{round}`). Photos, comments, and history tracking are attached.

Key models: `CheckSheet`, `Equipment`, `Activity`, `ActivityItem`, `PhotoGroup`, `Photo`, `Comment`

## Commands

```bash
# Run tests
composer test          # or: php artisan test

# Code formatting
./vendor/bin/pint

# Serve locally (via Laravel Herd or)
php artisan serve

# Migrations
php artisan migrate
php artisan migrate:fresh --seed

# Clear caches
php artisan optimize:clear
```

## Code Conventions

- PSR-4 autoloading, `App\` namespace
- CamelCase classes, snake_case DB columns
- Standard Laravel JSON response with pagination (`total`, `current_page`, `per_page`, `last_page`)
- Polymorphic comments (morph relation on CheckSheets, Equipment, etc.)
- Soft deletes on `Comment` model
- Query filtering via request params in controllers (search, sort, paginate)
- Eager loading with `with()` on relationships

## API Authentication

All endpoints except `POST /api/register`, `POST /api/login` require `auth:sanctum` middleware.