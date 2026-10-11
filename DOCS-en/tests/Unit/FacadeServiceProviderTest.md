# File Report: tests/Unit/FacadeServiceProviderTest.php

## Purpose
Unit tests for the facade service provider bindings.

## Overview
Verifies `Core\Providers\FacadeServiceProvider` registers singletons for cache, auth, logger, route, database, dispatcher, view, and rate limiter, binds the validator proxy, and that the validator proxy creates a `Validator` instance.

## File Location
`tests/Unit/FacadeServiceProviderTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class FacadeServiceProviderTest extends Tests\TestCase`

## Subject Under Test
- `Core\Providers\FacadeServiceProvider`

## Test Methods
- `test_register_binds_cache_singleton` — `:38`
- `test_register_binds_auth_singleton` — `:50`
- `test_register_binds_logger_singleton` — `:62`
- `test_register_binds_route_singleton` — `:70`
- `test_register_binds_database_singleton` — `:78`
- `test_register_binds_dispatcher_singleton` — `:86`
- `test_register_binds_view_singleton` — `:94`
- `test_register_binds_rate_limiter_singleton` — `:102`
- `test_register_binds_validator_proxy` — `:112`
- `test_validator_proxy_creates_validator_instance` — `:124`

## Cross References
- **Tests:** `Core\Providers\FacadeServiceProvider` (see `DOCS/core/Providers/FacadeServiceProvider.md`)

## Source References
- `tests/Unit/FacadeServiceProviderTest.php:1-135`
