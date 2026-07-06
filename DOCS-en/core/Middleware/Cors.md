# File Report: core/Middleware/Cors.php

## Purpose
Cross-Origin Resource Sharing (CORS) middleware.

## Overview
Sets the appropriate HTTP headers to allow or restrict cross-origin requests based on the configuration in `config/middleware.php`.

## File Location
`core/Middleware/Cors.php`

## Namespace
`Core\Middleware`

## Classes
- `class Cors implements MiddlewareInterface`

## Methods
- `handle(Request $request, \Closure $next): mixed`:
    1. If the request is an `OPTIONS` (preflight) request, it immediately returns a 204 response with the configured CORS headers.
    2. Otherwise, it adds the CORS headers to the response of the next handler.

## Dependencies
- `Core\Contracts\MiddlewareInterface` (Implements)
- `Core\Request` (Uses)

## Source References
- `core/Middleware/Cors.php:1-80`
