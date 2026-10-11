# Session

## Purpose
Documents session usage and configuration.

## Overview
Umay uses native PHP sessions (`$_SESSION`). There is no dedicated session manager class; components start the session on demand (`session_status() === PHP_SESSION_NONE` → `session_start()`) and read/write `$_SESSION` directly. Session settings are declared in `config/session.php`.

## Configuration (`config/session.php`)
- `lifetime` — minutes, default 30 (`SESSION_LIFETIME`).
- `cookie` — session cookie name, default `umay_session` (`SESSION_COOKIE`).
- `secure` — auto-enabled over HTTPS, forceable via `SESSION_SECURE`.
- `http_only` — `true`.
- `same_site` — default `Strict` (`SESSION_SAME_SITE`).

## Session Usage Across the Framework
- **CSRF** (`Core\Csrf`): stores/reads `$_SESSION['csrf_token']`; starts the session if needed.
- **Auth** (`Core\Auth`): reads `$_SESSION['user_id']`; `login()` calls `session_regenerate_id(true)`, sets `user_id`/`login_time`, and rotates the CSRF token; `logout()` clears `$_SESSION`, expires the session cookie, and calls `session_destroy()`.
- **View** (`Core\View`): starts the session and consumes flashed data (`_flash`, `_flash_errors`, `_old`) during render.
- **Tests** (`Tests\TestCase`): use an array-backed session and reset it per test.

## Security Observations
- `http_only` + `SameSite=Strict` by default; `secure` under HTTPS.
- Session id regenerated on login (fixation defense); CSRF token rotated on privilege change.

## Cross References
- `Core\Csrf` (see `DOCS/core/Csrf.md`), `Core\Auth` (see `DOCS/AUTHENTICATION.md`), `Core\View` (see `DOCS/VIEW_ENGINE.md`)

## Source References
- `config/session.php:5-28`
- `core/Csrf.php:9-40`, `core/Auth.php:171-241`, `core/View.php:241-305`
