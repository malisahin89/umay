# File Report: core/Support/Facade.php

## Purpose
Base class for all framework Facades.

## Overview
Implements the Facade pattern by providing a static `__callStatic` method that resolves the underlying service from the `Container` and calls the requested method on it.

## File Location
`core/Support/Facade.php`

## Namespace
`Core\Support`

## Classes
- `class Facade`

## Methods
- `getFacadeRoot(): mixed`: Abstract method that returns the class name of the service being proxied.
- `__callStatic(string $method, array $args): mixed`: Intercepts static calls, resolves the root service from the container, and executes the method.

## Dependencies
- `Core\Container` (Uses)

## Source References
- `core/Support/Facade.php:1-50`
