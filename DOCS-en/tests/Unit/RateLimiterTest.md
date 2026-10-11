# File Report: tests/Unit/RateLimiterTest.php

## Purpose
Unit tests for the rate limiter.

## Overview
Verifies `Core\RateLimiter`: initial state allows attempts, `hit` increments the counter, the limit triggers `tooManyAttempts`, `clear` resets, `remaining` decreases with hits, and named limiters can be registered.

## File Location
`tests/Unit/RateLimiterTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class RateLimiterTest extends Tests\TestCase`

## Subject Under Test
- `Core\RateLimiter`

## Test Methods
- `test_not_too_many_attempts_initially` — `:26`
- `test_hit_increments_counter` — `:31`
- `test_too_many_attempts_after_limit` — `:38`
- `test_clear_resets_counter` — `:46`
- `test_remaining_decreases_with_hits` — `:55`
- `test_named_limiter_registration` — `:62`

## Cross References
- **Tests:** `Core\RateLimiter` (see `DOCS/core/RateLimiter.md`)

## Source References
- `tests/Unit/RateLimiterTest.php:1-69`
