![Umay Framework Logo](../umay.png)

# Umay Framework

A minimal PHP MVC framework built from scratch.

> This is the English README that ships with the `DOCS-en/` documentation set. For the full reference documentation, start at [INDEX.md](INDEX.md).

## Requirements

- PHP 8.2+
- Composer
- MySQL / MariaDB (schema is portable — SQLite is used by the test suite, PostgreSQL is also supported)

## Dependencies

- `illuminate/database` ^10.0 — Eloquent ORM
- `illuminate/events` ^10.0 — Model Events & Observers
- `illuminate/pagination` ^10.0 — Eloquent Pagination
- `league/plates` ^3.4 — Template engine
- `vlucas/phpdotenv` ^5.6 — .env loader
- `phpunit/phpunit` ^10.0 (dev) — Testing framework
- `laravel/pint` ^1.29 (dev) — Code style fixer
- `phpstan/phpstan` ^2.1 (dev) — Static analysis

## Installation

```bash
composer install
cp .env.example .env  # or create the .env file manually
# Edit .env file (DB credentials, APP_KEY, etc.)
```

> Copy `.env.example` to `.env` and fill in `APP_KEY`, database credentials, etc. To quickly generate an `APP_KEY`: `php umay key:generate`.

### Production Deployment

```bash
composer install --no-dev --optimize-autoloader --classmap-authoritative
```

> The `--classmap-authoritative` flag forces the autoloader to resolve classes from a pre-built classmap instead of scanning the filesystem, providing significantly faster class loading.

## Quick Start

```bash
php umay migrate
php umay db:seed
php -S localhost:8000 -t public
```

## Console Commands

Umay provides an Artisan-like CLI tool. Usage: `php umay <command> [arguments]`

Run `php umay help` (or just `php umay`) at any time to see the full, authoritative command list.

### Setup

| Command | Description |
|---------|-------------|
| `key:generate` | Generate a new `APP_KEY` and write it to `.env` |
| `key:generate --show` | Generate an `APP_KEY` and print it without writing |
| `storage:link` | Symlink `public/storage` → `storage/app/public` for web-served uploads |

### Code Generators

| Command | Description | Output |
|---------|-------------|--------|
| `make:controller Name` | Create a controller | `app/Controllers/NameController.php` |
| `make:model Name` | Create a model | `app/Models/Name.php` |
| `make:migration name` | Create a migration | `database/migrations/YYYY_MM_DD_HHMMSS_name.php` |
| `make:middleware Name` | Create a middleware | `app/Middleware/NameMiddleware.php` |
| `make:request Name` | Create a FormRequest | `app/Requests/NameRequest.php` |
| `make:mail Name` | Create a Mailable | `app/Mail/NameMail.php` |
| `make:event Name` | Create an event | `app/Events/Name.php` |
| `make:listener Name` | Create a listener | `app/Listeners/Name.php` |
| `make:factory Name` | Create a factory | `database/factories/NameFactory.php` |
| `make:test Name` | Create a feature test | `tests/Feature/NameTest.php` |
| `make:test Name --unit` | Create a unit test | `tests/Unit/NameTest.php` |

### Database Commands

| Command | Description |
|---------|-------------|
| `migrate` | Run all pending migrations |
| `migrate:rollback` | Rollback the last migration |
| `migrate:fresh` | Drop all tables and re-run all migrations |
| `db:seed` | Run database seeders |

### Utility Commands

| Command | Description |
|---------|-------------|
| `route:list` | List all registered routes |
| `cache:clear` | Clear all cache files |
| `test` | Run PHPUnit tests |
| `test --unit` | Run only unit tests |
| `test --feature` | Run only feature tests |
| `help` | Show available commands |

## Profiler / DebugBar

Umay includes a built-in AJAX-based profiler toolbar that displays at the bottom of every page during development.

### Activation

The profiler is **automatically enabled** when `APP_DEBUG=true` in your `.env` file. No additional setup required.

You can also control it via environment variables:

```env
PROFILER_ENABLED=true
PROFILER_TTL=7200
PROFILER_MAX_ENTRIES=200
PROFILER_IP_WHITELIST=127.0.0.1,::1
```

### What It Shows

- **Response Time** — Total request duration
- **Memory Usage** — Peak memory consumption
- **Database Queries** — SQL queries with execution time, bindings, and caller info
- **Views** — Rendered templates
- **Auth** — Current authenticated user
- **Route** — Matched route, controller, and middleware chain
- **Events** — Dispatched events
- **Mail** — Sent emails
- **Exceptions** — Caught exceptions with stack trace

### Profiler API

The profiler stores data as JSON and exposes an API endpoint:

```
GET /_profiler           → List recent profiler entries
GET /_profiler/{token}   → Get a specific profile detail
```

> **Security:** Access is restricted by `ip_whitelist` in `config/profiler.php`. Only whitelisted IPs can view profiler data. In production, the profiler is disabled by default.

## Helper Functions

Umay includes a variety of global "helper" functions to speed up development:

- **Routing & HTTP:** `route()`, `redirect()`, `back()`, `asset()`, `abort()`, `response()`, `method_field()`
- **State & Auth:** `env()`, `config()`, `flash()`, `old()`, `auth()`, `csrf()`, `csrf_token()`
- **Validation:** `validate()`
- **Utilities:** `str_slug()`, `str_limit()`, `now()`, `today()`
- **Advanced:** `collect()` (Illuminate Collections), `cache()`, `event()`, `paginator()`, `factory()`
- **Security:** `getRealIP()` (Securely resolves real IP even behind Cloudflare proxies)

## Features

- **Routing:** Named routes, prefix/group, middleware, resource routes, method spoofing, `Route::view`/`redirect`, canonical URI redirection
- **IoC Container:** PSR-11 compatible, Front Controller pattern, reflection-based autowiring & parameter injection
- **Auth:** Pluggable guard (session, remember-me), personal access tokens for stateless APIs (`HasApiTokens` trait with abilities/scopes & expiry, Bearer auth), permission-based access control
- **Middleware:** Pipeline-based (global + route level) with class resolution caching
- **Validation:** 30+ rules, FormRequest support
- **Cache:** HMAC-signed file-based caching
- **Events:** Singleton event bus + listener
- **Mail:** SMTP + log driver
- **Database:** Full native Eloquent ORM integration (Scopes, Relations, Mutators, Soft Deletes)
- **Migration/Seeder:** Automated database management
- **Authorization:** Roll-your-own (middleware-based); full RBAC example on the `demo-app` branch
- **Rate Limiting:** Cache-based throttle
- **Pagination:** Eloquent-compatible pagination
- **Security:** CSP, CSRF, XSS protection
- **Console CLI:** Artisan-like command system
- **Profiler/DebugBar:** AJAX-based performance monitor
- **Helpers:** Global utilities for routing, HTTP responses, caching, and string manipulation
- **Network Security:** Built-in Cloudflare IP validation to prevent IP spoofing
- **Performance:** Singleton Plates template engine with component system and built-in CSP nonce support, pre-compiled route regex, reflection caching, single DB connection, optimized autoloader

## Request Lifecycle
`public/index.php` $\rightarrow$ `Profiler::init()` $\rightarrow$ `Application::boot()` (Service Providers & Routes) $\rightarrow$ `Route::dispatch()` $\rightarrow$ `Middleware Pipeline` $\rightarrow$ `Controller Execution` $\rightarrow$ `Response` $\rightarrow$ `Profiler::finish()`

## Directory Structure

```
├── core/           ← Framework core
├── app/            ← Application code (clean starter skeleton)
│   ├── Controllers/   (base Controller)
│   ├── Middleware/    (ThrottleMiddleware)
│   ├── Models/        (generic User)
│   ├── Providers/
│   └── Services/
├── config/         ← Configuration
├── database/       ← Migrations, seeders, factories
├── DOCS/           ← Framework documentation (Turkish)
├── DOCS-en/        ← Framework documentation (English)
├── public/         ← Entry point (index.php)
├── routes/         ← Web and API routes
├── stubs/          ← Code generator templates (make:* commands read from here)
├── storage/        ← Cache, logs
├── tests/          ← PHPUnit tests
└── views/          ← Plates template files
```

> The `make:request`, `make:mail`, `make:event`, and `make:listener` generators create the `app/Requests/`, `app/Mail/`, `app/Events/`, and `app/Listeners/` directories on demand — they are not part of the initial skeleton.

## Namespaces

- `Core\` — Framework core
- `App\` — Application code
- `Database\Seeders\` — Seeder files
- `Database\Factories\` — Factory files
- `Tests\` — Test files (dev-only autoload)

> Migrations are anonymous classes (no namespace) returned from their files and loaded directly by the Migrator, not via PSR-4 autoloading.

## Testing & Code Quality

Composer scripts wrap the dev toolchain:

| Script | Command | Description |
|--------|---------|-------------|
| `composer test` | `phpunit` | Run the full test suite |
| `composer test:unit` | `phpunit --testsuite Unit` | Run unit tests only |
| `composer test:feature` | `phpunit --testsuite Feature` | Run feature tests only |
| `composer format` | `pint` | Auto-fix code style |
| `composer format:test` | `pint --test` | Check code style without writing |
| `composer phpstan` | `phpstan analyse` | Run static analysis |

> The `php umay test [--unit|--feature]` command is available as a CLI equivalent of the PHPUnit scripts.

## License

MIT License — see [LICENSE](../LICENSE) for details.
