# File Report: tests/Unit/FacadeTest.php

## Purpose
Unit tests for the facade base class.

## Overview
Verifies `Core\Support\Facade`: static calls forward to the resolved instance, `swap` replaces the instance, clearing resolved instances forces re-resolution, an unresolvable accessor throws, `getFacadeRoot` returns the correct instance, and a single resolved instance can be cleared.

## File Location
`tests/Unit/FacadeTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class FacadeTest extends Tests\TestCase`

## Subject Under Test
- `Core\Support\Facade`

## Test Methods
- `test_facade_forwards_static_calls_to_resolved_instance` — `:36`
- `test_swap_replaces_resolved_instance` — `:64`
- `test_clear_resolved_instances_forces_re_resolution` — `:99`
- `test_facade_throws_when_accessor_cannot_be_resolved` — `:132`
- `test_get_facade_root_returns_correct_instance` — `:148`
- `test_clear_single_resolved_instance` — `:170`

## Cross References
- **Tests:** `Core\Support\Facade` (see `DOCS/core/Support/Facade.md`)

## Source References
- `tests/Unit/FacadeTest.php:1-193`
