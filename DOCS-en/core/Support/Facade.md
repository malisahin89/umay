# File Report: core/Support/Facade.php

## Purpose
Base class for all framework Facades.

## Overview
Implements the Facade pattern by providing a static `__callStatic` method that resolves the underlying service from the `Container` and calls the requested method on it. Resolved instances are cached in `$resolvedInstances`.

## File Location
`core/Support/Facade.php`

## Namespace
`Core\Support`

## Classes
- `class Facade` (abstract)

## Methods
- `getFacadeAccessor(): string`: Abstract method returning the Container key or class name for this Facade; every subclass must implement it.
- `getFacadeRoot(): object`: Returns the underlying service instance; serves it from cache if already resolved, otherwise resolves via `Container::make()` (auto-wiring).
- `__callStatic(string $method, array $args): mixed`: Intercepts static calls, resolves the root service from the container, and executes the method.
- `swap(object $instance): void`: Replaces the resolved instance for testing/mocking; also updates the Container binding so other resolvers see the same instance.
- `clearResolvedInstances(): void`: Clears all resolved Facade instances; also forgets the Container singleton behind each accessor via `forgetInstance()`, so a `swap()`ed mock or stale singleton cannot leak into the next test (call between tests).
- `clearResolvedInstance(string $accessor): void`: Clears a single resolved Facade instance.

## Dependencies
- `Core\Container` (Uses)

## Source References
- `core/Support/Facade.php:1-161`
