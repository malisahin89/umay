# File Report: core/Middleware/SecurityHeaders.php

## Purpose
Security-focused HTTP header middleware.

## Overview
Injects essential security headers (e.g., `X-Content-Type-Options`, `X-Frame-Options`, `Content-Security-Policy`) into every response to protect the application from common web vulnerabilities.

## File Location
`core/Middleware/SecurityHeaders.php`

## Namespace
`Core\Middleware`

## Classes
- `class SecurityHeaders implements MiddlewareInterface`

## Methods
- `handle(Request $request, \Closure $next): mixed`:
    1. Generates a fresh CSP nonce using `Csp::reset()`.
    2. Sets several security headers.
    3. Ensures the response is sent over HTTPS if configured.

## Dependencies
- `Core\Contracts\MiddlewareInterface` (Implements)
- `Core\Csp` (Uses)

## Source References
- `core/Middleware/SecurityHeaders.php:1-110`
