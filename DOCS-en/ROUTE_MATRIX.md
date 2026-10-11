# Route Matrix

## Purpose
Lists the verified, actively-registered routes.

## Overview
Routes are defined in `routes/web.php` (session-based, `web` group) and `routes/api.php` (stateless, `api` group, `api_prefix` default `/api`). In the analyzed source, only one route is actively registered; all other route definitions in both files are commented-out examples.

## Web Routes (`routes/web.php`)
| Method | URI | Name | Controller / Action | Middleware | Source |
|--------|-----|------|---------------------|-----------|--------|
| GET | `/` | `home` | `Route::view` → renders `welcome` template (`title` set) | `web` group (`RememberMe`, `SecurityHeaders`, `VerifyCsrfToken`) | `routes/web.php:8` |

## API Routes (`routes/api.php`)
No active routes. The file documents usage patterns (`api-auth`, `api-auth:ability`, `Route::apiResource`) as commented examples only.

> No further verified routes found in the analyzed source code. Additional routes shown in comments are examples, not registered routes.

## Cross References
- `DOCS/ROUTING_SYSTEM.md`, `DOCS/MIDDLEWARE.md`, `DOCS/routes/index.md`

## Source References
- `routes/web.php:8`
- `routes/api.php:1-78` (examples only)
- `config/middleware.php:53` (api_prefix), `config/middleware.php:84-105` (groups)
