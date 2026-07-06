# File Report: tests/TestCase.php

## Purpose
Project base test case extending PHPUnit's `TestCase` with framework-aware setup and helpers.

## Overview
Abstract base class for all tests. Before each test it resets session, superglobals, the container-bound `Auth` cache, and the event dispatcher. It provides request-building and assertion helpers.

## File Location
`tests/TestCase.php`

## Namespace
`Tests`

## Imports
- `App\Models\User`
- `Core\Auth`, `Core\Container`, `Core\Events\Dispatcher`, `Core\Request`
- `PHPUnit\Framework\TestCase as BaseTestCase`

## Classes
- `abstract class TestCase extends BaseTestCase`

## Methods
- `setUp(): void` — starts an array-based session, resets `$_SESSION/$_POST/$_GET/$_FILES/$_COOKIE`, clears `Auth` cache, flushes `Dispatcher`.
- `tearDown(): void` — flushes `Dispatcher`, clears `Auth` cache.
- `makeRequest(string $method, string $uri, array $data, array $headers): Request` — builds a `Request` from simulated superglobals via `Request::capture()`.
- `actingAs(User $user): static` — sets `$_SESSION['user_id']` and clears the `Auth` cache.
- `withSession(array $data): static` — merges data into `$_SESSION`.
- `assertSessionHas(string $key, mixed $value = null): void`
- `assertSessionMissing(string $key): void`
- `assertFlash(string $type, ?string $message = null): void`

## Cross References
- **Extended By:** every test class in `tests/Unit/` and `tests/Feature/`.
- **Uses:** `Core\Auth`, `Core\Container`, `Core\Events\Dispatcher`, `Core\Request`, `App\Models\User`.

## Source References
- `tests/TestCase.php:1-166`
