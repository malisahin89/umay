# File Report: tests/Unit/ApplicationTest.php

## Purpose
Unit tests for the application container/lifecycle orchestrator.

## Overview
Verifies the singleton behavior of `Core\Application`, its delegation to the `Container` (`make`, `singleton`), provider registration, single-boot guarantee, and request capture binding.

## File Location
`tests/Unit/ApplicationTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class ApplicationTest extends Tests\TestCase`

## Subject Under Test
- `Core\Application`

## Test Methods
- `test_get_instance_returns_same_instance` — `:23`
- `test_container_returns_container_instance` — `:33`
- `test_make_delegates_to_container` — `:43`
- `test_singleton_delegates_to_container` — `:54`
- `test_register_calls_provider_register` — `:71`
- `test_boot_runs_only_once` — `:82`
- `test_capture_request_binds_to_container` — `:96`

## Cross References
- **Tests:** `Core\Application` (see `DOCS/core/Application.md`), `Core\Container` (see `DOCS/core/Container.md`)

## Source References
- `tests/Unit/ApplicationTest.php:1-109`
