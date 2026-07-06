# File Report: tests/Unit/MiddlewareInterfaceTest.php

## Purpose
Consistency test ensuring all middlewares honor the middleware contract.

## Overview
Verifies that every middleware class implements `Core\Contracts\MiddlewareInterface`.

## File Location
`tests/Unit/MiddlewareInterfaceTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class MiddlewareInterfaceTest extends Tests\TestCase`

## Subject Under Test
- `Core\Contracts\MiddlewareInterface` and all implementing middleware classes.

## Test Methods
- `test_all_middlewares_implement_interface` — `:17`

## Cross References
- **Tests:** `Core\Contracts\MiddlewareInterface` (see `DOCS/core/Contracts/MiddlewareInterface.md`) and `Core\Middleware\*` (see `DOCS/core/Middleware/index.md`)

## Source References
- `tests/Unit/MiddlewareInterfaceTest.php:1-43`
