# Package Structure

## Purpose
Describes the on-disk layout of the Umay project and the PSR-4 autoloading that maps namespaces to directories.

## Overview
Umay is a minimal PHP MVC framework (PHP ≥ 8.2). Application code lives under `app/`, the framework core under `core/`, and supporting concerns (config, routes, views, database, tests) in dedicated top-level directories.

## Directory Layout
| Directory | Role |
|-----------|------|
| `app/` | Application code: `Controllers/`, `Middleware/`, `Models/`, `Providers/`, `Services/` |
| `core/` | Framework internals (routing, container, ORM base, auth, cache, mail, profiler, …) |
| `config/` | Configuration files returning arrays (`app`, `auth`, `cache`, `database`, `mail`, `middleware`, `profiler`, `session`) |
| `routes/` | Route definitions (`web.php`, `api.php`) |
| `views/` | Plates templates (`layouts/`, `partials/`, `errors/`) |
| `database/` | `migrations/`, `seeders/`, `factories/` |
| `stubs/` | Code-generation templates used by the console `make:*` commands |
| `public/` | Web root and front controller (`index.php`) |
| `storage/` | Runtime artifacts (cache, logs, profiler) |
| `tests/` | PHPUnit suite (`Unit/`, `Feature/`) |

## PSR-4 Autoloading
From `composer.json`:

- `App\` → `app/`
- `Core\` → `core/`
- `Database\Seeders\` → `database/seeders/`
- `Database\Factories\` → `database/factories/`
- `Tests\` → `tests/` (autoload-dev)

Global functions are loaded from `core/helpers.php` (via the front controller / test bootstrap), not autoloaded.

## Runtime Dependencies (composer.json)
- `php >= 8.2`
- `illuminate/database ^10.0`, `illuminate/events ^10.0`, `illuminate/pagination ^10.0`
- `league/plates ^3.4`
- `vlucas/phpdotenv ^5.6`

Dev: `laravel/pint`, `phpstan/phpstan ^2.1`, `phpunit/phpunit ^10.0`.

## Composer Scripts
`test`, `test:unit`, `test:feature`, `format` (pint), `format:test`, `phpstan`.

## Source References
- `composer.json:26-59`
- `core/helpers.php`, `stubs/`, `config/`, `public/index.php`
