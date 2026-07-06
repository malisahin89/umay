# File Report: tests/Unit/CacheTest.php

## Purpose
Unit tests for the file-based cache.

## Overview
Verifies `Core\Cache`: set/get for scalar, array, integer, boolean values; `has`, `forget`, `flush`, `pull`, `remember`; default handling for missing keys; and resilience against tampered, malformed, or expired cache files. Also verifies config-driven default TTL and prefix-based filenames.

## File Location
`tests/Unit/CacheTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class CacheTest extends Tests\TestCase`

## Subject Under Test
- `Core\Cache`

## Test Methods
- `test_set_and_get_returns_stored_value` — `:49`
- `test_get_returns_default_when_key_not_exists` — `:57`
- `test_get_returns_null_default_when_key_not_exists` — `:63`
- `test_cache_stores_array_values` — `:71`
- `test_cache_stores_integer_values` — `:79`
- `test_cache_stores_boolean_values` — `:85`
- `test_has_returns_true_for_existing_key` — `:93`
- `test_has_returns_false_for_missing_key` — `:99`
- `test_forget_removes_key` — `:106`
- `test_flush_removes_all_keys` — `:117`
- `test_pull_returns_value_and_removes_key` — `:132`
- `test_pull_returns_default_when_key_not_exists` — `:141`
- `test_remember_stores_and_returns_callback_value` — `:149`
- `test_tampered_cache_file_returns_default` — `:174`
- `test_invalid_cache_format_returns_default` — `:192`
- `test_expired_cache_returns_default` — `:206`
- `test_set_uses_config_default_ttl_when_omitted` — `:226`
- `test_prefix_applied_to_cache_filename` — `:239`

## Cross References
- **Tests:** `Core\Cache` (see `DOCS/core/Cache.md`)

## Source References
- `tests/Unit/CacheTest.php:1-251`
