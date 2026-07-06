# Security

## Purpose
Consolidates the framework's security mechanisms.

## Overview
Umay's defenses span CSRF protection, a nonce-based Content-Security-Policy with security headers, rate limiting, input validation/sanitization, log-injection defense, and session/token hardening.

## CSRF Protection
- `Core\Csrf`: generates a 64-char hex token stored in `$_SESSION['csrf_token']`; `check()` uses `hash_equals` and rejects non-string/empty/mismatched tokens.
- Enforced by `Core\Middleware\VerifyCsrfToken` in the `web` group.
- Token rotated on login (`Core\Auth::login` unsets `csrf_token`).

## Security Headers & CSP (`Core\Middleware\SecurityHeaders`)
- `X-Content-Type-Options: nosniff`, `X-Frame-Options: DENY`, `Referrer-Policy: strict-origin-when-cross-origin`, `X-XSS-Protection: 0` (legacy filter intentionally disabled).
- **HSTS** sent only over active HTTPS and never in local (`max-age=31536000; includeSubDomains`).
- **CSP** is nonce-based: `local` env allows `unsafe-inline`; production/staging use a strict policy (`object-src 'none'`, `base-uri 'self'`, `form-action 'self'`, `frame-ancestors 'none'`). The nonce is request-local (`Core\Csp`, not session-shared) to avoid concurrent-request races.
- Production HTTP→HTTPS redirect builds the host from `config('app.url')`, never the client `Host` header (host-header injection defense).

## Rate Limiting
- `Core\RateLimiter` (cache-backed) with `hit`/`tooManyAttempts`/`clear`/`remaining`/`availableIn` and named limiters (`for`).
- Counter increments use `Cache::atomic` (cross-process lock, fail-closed) → TOCTOU-safe.
- `App\Middleware\ThrottleMiddleware` exposes it as `throttle:max,decay`.

## Input Validation & Sanitization
- `Core\Validator` rejects array values, uses raw vs trimmed values correctly for length/equality rules, and sanitizes table/column names in `unique`/`exists` rules (regex allow-list) before querying.

## Log Injection Defense
- `Core\Logger` strips CR/LF from message, IP, and user-agent before writing.

## Authentication Hardening
- `session_regenerate_id(true)` on login (fixation defense); remember-token stored hashed; `HttpOnly`/`SameSite` cookies (see `DOCS/COOKIE.md`).

## Trusted Proxies / Real IP
- `config('app.trusted_proxies')` + `get_real_ip()` prevent `X-Forwarded-For` spoofing for throttling/logging (see `DOCS/core/helpers.md`).

## Cross References
- `DOCS/AUTHENTICATION.md`, `DOCS/AUTHORIZATION.md`, `DOCS/VALIDATION.md`, `DOCS/CACHE.md`
- `Core\Csrf`, `Core\Csp`, `Core\Middleware\SecurityHeaders`, `Core\RateLimiter`

## Source References
- `core/Csrf.php:9-40`, `core/Csp.php:24-45`, `core/Middleware/SecurityHeaders.php:14-101`
- `core/RateLimiter.php:62-104`, `core/Validator.php:93-101`, `core/Validator.php:300-303`, `core/Logger.php:49-54`, `core/Auth.php:171-199`
