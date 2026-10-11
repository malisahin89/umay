# File Report: core/Middleware/ApiAuth.php

## Purpose
Stateless API authentication middleware.

## Overview
Protects API routes by verifying a Bearer token in the `Authorization` header. If a valid token is found, the associated user is bound to the `Auth` guard for the current request.

## File Location
`core/Middleware/ApiAuth.php`

## Namespace
`Core\Middleware`

## Classes
- `class ApiAuth implements MiddlewareInterface`

## Methods
- `handle(Request $request, \Closure $next): mixed`:
    1. Extracts the Bearer token from the request.
    2. Uses `PersonalAccessToken::findToken()` to resolve the token.
    3. If valid and not expired, binds the user to `Auth::setUser()`.
    4. If invalid, returns a 401 Unauthorized JSON response.
    5. Supports ability checks (e.g., `api-auth:posts.write`).

## Dependencies
- `Core\Contracts\MiddlewareInterface` (Implements)
- `Core\Request` (Uses)
- `Core\Auth\PersonalAccessToken` (Uses)
- `Core\Auth` (Uses)
- `Core\Response` (Uses)

## Source References
- `core/Middleware/ApiAuth.php:1-100`
