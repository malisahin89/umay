# File Report: config/middleware.php

## Purpose
Middleware and CORS configuration.

## Overview
Defines middleware groups ('web', 'api'), global middleware, and CORS settings. It also specifies the resolution order for middleware names to classes.

## File Location
`config/middleware.php`

## Configuration
- `api_prefix`: Prefix for API routes from `API_PREFIX` (default: '/api').
- `global`: Array of middleware running on every request (currently empty).
- `web`: Middleware for session-based requests: `['RememberMe', 'SecurityHeaders', 'VerifyCsrfToken']`.
- `api`: Middleware for stateless requests: `['Cors', 'throttle:60,60']`.
- `cors_origin`: Allowed origins from `CORS_ORIGIN` (default: '*').
- `cors_methods`: Allowed HTTP methods.
- `cors_headers`: Allowed HTTP headers.
- `cors_credentials`: Boolean from `CORS_CREDENTIALS`.
- `cors_max_age`: Seconds from `CORS_MAX_AGE` (default: 86400).

## Middleware Resolution
The router resolves middleware names using these templates:
1. `App\\Middleware\\{name}Middleware`
2. `Core\\Middleware\\{name}`

## Middleware Aliases
- `throttle` -> `Throttle`
- `cors` -> `Cors`

## Source References
- `config/middleware.php:1-186`
