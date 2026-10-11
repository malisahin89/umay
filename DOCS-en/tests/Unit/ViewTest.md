# File Report: tests/Unit/ViewTest.php

## Purpose
Unit tests for the view engine integration.

## Overview
Verifies `Core\View`: the engine returns a Plates instance that is a singleton per view instance, and registers the template helper functions (`csrf`, `csrf_token`, escape, `old`, `route`, `flash`, `dd`).

## File Location
`tests/Unit/ViewTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class ViewTest extends Tests\TestCase`

## Subject Under Test
- `Core\View`

## Test Methods
- `test_engine_returns_plates_instance` — `:35`
- `test_engine_is_singleton_per_instance` — `:44`
- `test_engine_has_csrf_function` — `:57`
- `test_engine_has_csrf_token_function` — `:66`
- `test_engine_has_escape_function` — `:75`
- `test_engine_has_old_function` — `:85`
- `test_engine_has_route_function` — `:94`
- `test_engine_has_flash_function` — `:103`
- `test_engine_has_dd_function` — `:112`

## Cross References
- **Tests:** `Core\View` (see `DOCS/core/View.md`)

## Source References
- `tests/Unit/ViewTest.php:1-120`
