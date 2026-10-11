# Cookies

## Purpose
Documents the cookies the framework sets and their security attributes.

## Overview
Umay does not provide a dedicated cookie abstraction; cookies are set with PHP's `setcookie()` (and native session cookies). Two application cookies are used: the session cookie and the "remember me" cookie.

## Cookies
### Session cookie
- Name from `config('session.cookie')` (default `umay_session`).
- Attributes from `config/session.php`: `http_only = true`, `same_site = Strict`, `secure` under HTTPS.
- Cleared on logout via `setcookie(session_name(), '', past, …)` using `session_get_cookie_params()`.

### `remember_me` cookie (`Core\Auth`)
- Set by `Auth::login($user, true)`: value `userId:token`, where only `sha256(token)` is stored server-side (via `UserProvider::updateRememberToken`).
- Attributes: `expires` = 30 days, `path=/`, `httponly=true`, `samesite=Lax`, `secure` under HTTPS.
- Consumed by `Core\Middleware\RememberMe` to restore the session; cleared on `Auth::logout()`.

## Security Observations
- `HttpOnly` on both cookies (no JS access); `Secure` under HTTPS.
- Remember-token stored hashed server-side; plaintext lives only in the cookie.
- `SameSite=Strict` for the session cookie, `Lax` for remember-me (so it survives top-level navigations).

## Cross References
- `Core\Auth` (see `DOCS/AUTHENTICATION.md`), `Core\Middleware\RememberMe` (see `DOCS/core/Middleware/RememberMe.md`), `config/session.php` (see `DOCS/config/session.md`)

## Source References
- `core/Auth.php:188-238`
- `config/session.php:12-27`
