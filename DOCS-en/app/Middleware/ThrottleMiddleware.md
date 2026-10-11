# File Report: app/Middleware/ThrottleMiddleware.php

## Purpose
Rate limiting middleware for controlling request frequency.

## Overview
Limits the number of requests a client can make within a specified time window. Supports both inline configuration (e.g., `throttle:5,300`) and named limiters defined via `RateLimiter::for`.

## File Location
`app/Middleware/ThrottleMiddleware.php`

## Namespace
`App\Middleware`

## Imports
- `Core\Contracts\MiddlewareInterface`
- `Core\Facades\RateLimiter`
- `Core\Request`
- `Core\TerminateException`

## Classes
- `class ThrottleMiddleware implements MiddlewareInterface`

## Properties
- `int $maxAttempts`: Maximum allowed attempts.
- `int $decaySeconds`: Time window for the limit.
- `string $limiterName`: Name of the limiter if using a named limiter.

## Methods
- `__construct(string $param = '60,60')`: Initializes the middleware with either a comma-separated "max,decay" string or a named limiter.
- `handle(Request $request, \Closure $next): mixed`: Processes the request, checks the rate limit, and either allows the request to proceed or returns a 429 Too Many Requests response.

## Internal Workflow
1. Resolves the `RateLimiter` facade root.
2. Determines the cache key based on the limiter name or a route-specific key (`throttle:method:path`) combined with the client's IP.
3. Increments the hit counter using `RateLimiter::hit`.
4. If `attempts > maxAttempts`:
    - If the request expects JSON, returns a 429 response with `Retry-After` header and JSON error.
    - Otherwise, flashes an error message and redirects back.
5. If within limits, calls the next middleware/controller.

## Dependencies
- `Core\RateLimiter`

## Cross References
- `Core\Contracts\MiddlewareInterface` (Implements)
- `Core\Facades\RateLimiter` (Uses)
- `Core\Request` (Uses)
- `Core\TerminateException` (Throws)

## Source References
- `app/Middleware/ThrottleMiddleware.php:1-108`
