# Middleware System

## Purpose
Documents the HTTP middleware pipeline, the web/API middleware groups, and how middleware names are resolved to classes.

## Overview
Middleware are classes implementing `Core\Contracts\MiddlewareInterface::handle(Request $request, \Closure $next): mixed`. They run around request handling; each either forwards to `$next($request)` or short-circuits. Groups and resolution are configured in `config/middleware.php`, and applied by the router (`Core\Route`).

## Middleware Groups (`config/middleware.php`)
- **`global`** — runs on every request (empty by default; session/CSRF must NOT go here).
- **`web`** — `RememberMe`, `SecurityHeaders`, `VerifyCsrfToken` (session-based requests, `routes/web.php`).
- **`api`** — `Cors`, `throttle:60,60` (stateless requests, `routes/api.php`); session/CSRF excluded, token auth added per-route.

`Route::setGroup('web'|'api')` selects the group for a route file.

## Name → Class Resolution
The router resolves a StudlyCase middleware name against ordered templates in `config('middleware.namespaces')`; the first existing class wins:

1. `App\Middleware\{name}Middleware`
2. `Core\Middleware\{name}`

This lets the core router resolve middleware without hard-coding `App\`/`Core\` namespaces. Aliases (`throttle` → `Throttle`, `cors` → `Cors`) are defined in `config('middleware.aliases')`. Parameterized middleware use `name:arg1,arg2` syntax (e.g. `throttle:60,60`, `api-auth:permission`).

## Core Middleware
| Middleware | Role | Source |
|-----------|------|--------|
| `Core\Middleware\SecurityHeaders` | Security headers + nonce CSP + HSTS + HTTPS redirect | `core/Middleware/SecurityHeaders.php` |
| `Core\Middleware\VerifyCsrfToken` | CSRF validation for state-changing web requests | `core/Middleware/VerifyCsrfToken.php` |
| `Core\Middleware\RememberMe` | Restores session from `remember_me` cookie | `core/Middleware/RememberMe.php` |
| `Core\Middleware\Cors` | CORS headers / preflight for API | `core/Middleware/Cors.php` |
| `Core\Middleware\ApiAuth` | Bearer-token authentication + abilities | `core/Middleware/ApiAuth.php` |
| `App\Middleware\ThrottleMiddleware` | Rate limiting (`throttle:max,decay`) | `app/Middleware/ThrottleMiddleware.php` |

> `Core\Middleware` (`core/Middleware.php`) is a `@deprecated` empty class; CSRF is now handled by `Core\Middleware\VerifyCsrfToken`.

## Cross References
- **Contract:** `Core\Contracts\MiddlewareInterface` (see `DOCS/core/Contracts/MiddlewareInterface.md`)
- **Applied By:** `Core\Route` (see `DOCS/ROUTING_SYSTEM.md`)
- **Per-file reports:** `DOCS/core/Middleware/index.md`

## Source References
- `config/middleware.php:36-186`
- `core/Middleware.php:1-14`
- `core/Middleware/SecurityHeaders.php:1-102`
