# Authorization

## Purpose
Documents access control: token abilities (scopes) for the API.

## Overview
Umay's verified authorization mechanism is **token abilities** on personal access tokens, enforced by the API auth middleware. There is no separate role/policy/gate system in the analyzed core; web authorization is expressed through middleware and `abort()` in controllers.

## Token Abilities (API)
Verified from `Core\Middleware\ApiAuth`, `Core\Auth\HasApiTokens`, `Core\Auth\PersonalAccessToken`, and their tests:
- Tokens are issued via `HasApiTokens::createToken('name', ['ability', …])` and stored **hashed** (`PersonalAccessToken`).
- `Core\Middleware\ApiAuth` is applied per-route as `api-auth` or `api-auth:ability`:
  - `api-auth` — requires a valid, unexpired token; missing/invalid/expired tokens are rejected with **401**.
  - `api-auth:ability` — additionally requires the token to hold `ability`.
  - A wildcard ability (`*`) grants every ability.
- On success the token owner is set as the current user via `Auth::setUser()`, and `last_used_at` is recorded.

## Web Authorization
- Route/middleware groups gate access (`web`/`api`); controllers can `abort(403|404|…)` (see `core/helpers.php` `abort()` → `HttpException`) which `Core\ExceptionHandler` renders as an error page/JSON.

## Cross References
- `DOCS/core/Middleware/ApiAuth.md`, `DOCS/core/Auth/HasApiTokens.md`, `DOCS/core/Auth/PersonalAccessToken.md`
- `DOCS/AUTHENTICATION.md`, `DOCS/tests/Unit/ApiAuthTest.md`, `DOCS/ERROR_HANDLING.md`

## Source References
- `core/Middleware/ApiAuth.php:1-83`
- `core/Auth/HasApiTokens.php:1-111`, `core/Auth/PersonalAccessToken.php:1-105`
- `tests/Unit/ApiAuthTest.php:119-151`
