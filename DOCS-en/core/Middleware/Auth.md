# File Report: core/Middleware/Auth.php

## Purpose
Authentication guard for session-based web routes, resolved via the `auth` middleware alias.

## Overview
Handles guest requests: if the request expects JSON (AJAX / `Accept: application/json` / Bearer token) it aborts with a `401` JSON response, otherwise it redirects to `config('auth.login_path', '/login')` (throws `RedirectException`). Authenticated requests pass through `$next($request)`.

## File Location
`core/Middleware/Auth.php`

## Namespace
`Core\Middleware`

## Classes
- `class Auth implements MiddlewareInterface`

## Methods
- `handle(Request $request, \Closure $next): mixed`:
    1. Resolves the `Core\Auth` guard from the `Container`.
    2. When `check()` is true, continues with `$next($request)`.
    3. Guest JSON-expecting requests `abort(401, ...)`.
    4. Web requests `redirect()` to `config('auth.login_path')` (throws `RedirectException`).

## Dependencies
- `Core\Contracts\MiddlewareInterface` (Implements)
- `Core\Request` (Uses)
- `Core\Auth` (Uses)

## Source References
- `core/Middleware/Auth.php:1-50`