# File Report: tests/Unit/HelpersTest.php

## Purpose
Unit tests for the global helper functions.

## Overview
Verifies the helpers defined in `core/helpers.php`: string helpers (`str_slug`, `str_limit`), date helpers (`now`, `today`), form/HTML helpers (`method_field`, `asset`, `old`, `csrf`, `csrf_token`), flash messaging (`flash`), IP helpers (`ip_in_cidr`, `is_cloudflare_ip`, `get_real_ip` including trusted-proxy handling), validation (`validate`), `abort`, and `config`.

## File Location
`tests/Unit/HelpersTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class HelpersTest extends Tests\TestCase`

## Subject Under Test
- `core/helpers.php` global functions.

## Test Methods
Covers `str_slug` (`:20-40`), `str_limit` (`:47-57`), `now`/`today` (`:64-82`), `method_field` (`:90-104`), `asset` (`:112-117`), `flash` (`:124-135`), `old` (`:144-163`), `ip_in_cidr` (`:172-192`), `is_cloudflare_ip` (`:199-209`), `get_real_ip` (`:216-246`), `validate` (`:256-262`), `abort` (`:271`), `csrf`/`csrf_token` (`:279-286`), and `config` (`:294-300`).

## Cross References
- **Tests:** `core/helpers.php` (see `DOCS/core/helpers.md`)

## Source References
- `tests/Unit/HelpersTest.php:1-304`
