# Configuration

## Purpose
Documents how configuration is defined, loaded, and accessed.

## Overview
Configuration files live in `config/` and each returns a PHP array. Values are typically sourced from environment variables (loaded via `vlucas/phpdotenv` from `.env`) with in-code defaults. Configuration is read through the global `config('file.key', $default)` helper (dot notation).

## Configuration Files
| File | Concern | Example keys |
|------|---------|--------------|
| `config/app.php` | App identity & environment | `name`, `version`, `url`, `env`, `debug`, `timezone`, `key`, `controller_namespace`, `trusted_proxies`, `aliases` |
| `config/auth.php` | Auth providers | `default`, `providers.<name>.driver`, `providers.<name>.model` |
| `config/cache.php` | File cache | `path`, `prefix`, `default_ttl` |
| `config/database.php` | DB connection | (driver/host/port/database/credentials/charset/collation) |
| `config/mail.php` | Mail transport | (mailer settings) |
| `config/middleware.php` | Middleware groups & CORS | `api_prefix`, `global`, `web`, `api`, `cors_*`, `namespaces`, `aliases` |
| `config/profiler.php` | Profiler | (enable/settings) |
| `config/session.php` | Session/cookie | `lifetime`, `cookie`, `secure`, `http_only`, `same_site` |

## Access Pattern
- `config('app.name')` in PHP and `$this->config('app.name')` in Plates templates.
- `Core\Application` boot and `Core\Auth`, `Core\Cache`, `Core\Route`, etc. read config rather than hard-coding values (e.g. `controller_namespace`, `middleware.namespaces`, `cache.prefix`).

## Environment Overrides
Most values fall back to `$_ENV['...'] ?? default`. Booleans use `filter_var(..., FILTER_VALIDATE_BOOLEAN)`; lists (e.g. `trusted_proxies`) are comma-split and trimmed.

## Cross References
- **Config Matrix:** see `DOCS/CONFIGURATION_MATRIX.md`
- **Helper:** `config()` in `core/helpers.php` (see `DOCS/core/helpers.md`)
- **Per-file config reports:** `DOCS/config/index.md`

## Source References
- `config/app.php:13-117`, `config/auth.php:21-48`, `config/cache.php:12-30`, `config/session.php:5-28`, `config/middleware.php:36-186`
