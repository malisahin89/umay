# File Report: core/RateLimiter.php

## Purpose
Cache-backed rate limiting system.

## Overview
Tracks request attempts for a given key (usually IP + route) and determines if a limit has been exceeded. It supports "named limiters" that can be defined globally in the application.

## File Location
`core/RateLimiter.php`

## Namespace
`Core`

## Classes
- `class RateLimiter`

## Properties
- `array $limiters`: Registry of named limiters with their max attempts and decay time.
- `Cache $cache`: The cache instance used for storage.

## Methods
- `for(string $name, int $maxAttempts, int $decaySeconds = 60): void`: Defines a named limiter.
- `limiter(string $name): ?array`: Retrieves a named limiter's configuration.
- `tooManyAttempts(string $key, int $maxAttempts, int $decaySeconds = 60): bool`: Checks if the attempts for a key have exceeded the limit.
- `hit(string $key, int $decaySeconds = 60): int`: Increments the hit counter for a key using an atomic operation.
- `clear(string $key): void`: Resets the counter for a key.
- `attempts(string $key): int`: Returns the current number of attempts.
- `remaining(string $key, int $maxAttempts): int`: Returns the number of attempts left before the limit is reached.
- `availableIn(string $key, int $decaySeconds): int`: Returns seconds remaining until the limit resets.
- `key(string $prefix, ?string $suffix = null): string`: Generates a hashed cache key.

## Internal Workflow
- `hit()`: Uses `Cache::atomic()` to ensure that concurrent requests do not cause a race condition when incrementing the counter.

## Dependencies
- `Core\Cache` (Uses)
- `Core\Container` (Uses)

## Source References
- `core/RateLimiter.php:1-155`
