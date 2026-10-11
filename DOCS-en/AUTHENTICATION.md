# Authentication

## Purpose
Documents session-based and token-based authentication.

## Overview
`Core\Auth` is a request-scoped, cached authentication guard resolved from the container (via the `Auth` facade). It is fully decoupled from `App\Models\User`: it talks only to a `Core\Contracts\UserProvider` and `Core\Contracts\Authenticatable`, with concrete classes chosen in `config/auth.php`.

## Provider Wiring (`config/auth.php`)
- `default` selects an active provider (default `eloquent`).
- Each provider has a `driver` (a `UserProvider` class); the eloquent driver also takes a `model` (an `Authenticatable`).
- Default: `EloquentUserProvider` + `App\Models\User`.
- `Core\Auth::provider()` resolves the driver lazily, validating it implements `UserProvider`; `setProvider()` allows override (tests/runtime).

## Session Authentication API
- Read: `user(): ?Authenticatable` (queries storage once per request, then caches), `id(): int|string|null` (preserves native key type), `check(): bool`, `guest(): bool`.
- Write: `login(Authenticatable, bool $remember=false)`, `logout()`, `attempt(array $credentials): bool`.
- `login()` regenerates the session id, rotates the CSRF token, sets `user_id`/`login_time`, and (if remember) stores a hashed remember-token + sets the `remember_me` cookie.
- `attempt()` delegates lookup (`retrieveByCredentials`) and password check (`validateCredentials`) to the provider.
- `setUser()` marks a user authenticated for the current request only (no session) — used by stateless token guards.

## Token Authentication
- `Core\Middleware\ApiAuth` authenticates Bearer tokens and calls `Auth::setUser()` for the request (stateless). See `DOCS/AUTHORIZATION.md` for abilities and `DOCS/core/Auth/HasApiTokens.md` / `DOCS/core/Auth/PersonalAccessToken.md`.

## Contracts
- `Core\Contracts\UserProvider` — `retrieveById`, `retrieveByCredentials`, `validateCredentials`, `updateRememberToken`.
- `Core\Contracts\Authenticatable` — `getAuthIdentifier`, `getRememberToken`, etc.

## Cross References
- `DOCS/core/Auth.md`, `DOCS/core/Auth/EloquentUserProvider.md`, `DOCS/core/Contracts/UserProvider.md`, `DOCS/core/Contracts/Authenticatable.md`
- `DOCS/config/auth.md`, `DOCS/tests/Feature/AuthTest.md`, `DOCS/tests/Unit/ApiAuthTest.md`

## Source References
- `core/Auth.php:38-277`
- `config/auth.php:21-48`
