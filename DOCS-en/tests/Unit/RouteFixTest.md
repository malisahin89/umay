# File Report: tests/Unit/RouteFixTest.php

## Purpose
Regression tests for resource routing and dispatch.

## Overview
Verifies `Core\Route` resource registration filters (`only`, `except`) and that route dispatch reuses the same `Request` instance.

## File Location
`tests/Unit/RouteFixTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class RouteFixTest extends Tests\TestCase`

## Subject Under Test
- `Core\Route`, `Core\ResourceRegistrar`, `Core\Request`

## Test Methods
- `test_resource_only` — `:29`
- `test_resource_except` — `:41`
- `test_route_dispatch_uses_same_request_instance` — `:56`

## Cross References
- **Tests:** `Core\Route` (see `DOCS/core/Route.md`), `Core\ResourceRegistrar` (see `DOCS/core/ResourceRegistrar.md`)

## Source References
- `tests/Unit/RouteFixTest.php:1-80`
