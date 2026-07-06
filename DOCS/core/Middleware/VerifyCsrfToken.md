# File Report: core/Middleware/VerifyCsrfToken.php

## Purpose
CSRF protection middleware.

## Overview
Verifies that state-changing requests (POST, PUT, PATCH, DELETE) contain a valid CSRF token that matches the one stored in the session.

## File Location
`core/Middleware/VerifyCsrfToken.php`

## Namespace
`Core\Middleware`

## Classes
- `class VerifyCsrfToken implements MiddlewareInterface`

## Methods
- `handle(Request $request, \Closure $next): mixed`:
    1. Skips verification for GET, HEAD, and OPTIONS requests.
    2. Extracts the token from the `csrf_token` input or the `X-CSRF-TOKEN` header.
    3. Uses `Csrf::check()` to verify the token.
    4. If invalid, throws a `CsrfException`, which results in a 419 status code.

## Dependencies
- `Core\Contracts\MiddlewareInterface` (Implements)
- `Core\Request` (Uses)
- `Core\Csrf` (Uses)

## Source References
- `core/Middleware/VerifyCsrfToken.php:1-90`
