# File Report: tests/Unit/ExceptionClassesTest.php

## Purpose
Unit tests for the exception classes.

## Overview
Verifies that `ContainerException` and `EntryNotFoundException` implement the PSR-11 exception interfaces and carry messages, and that `HttpException` exposes a status code with correct defaults (403 / 500).

## File Location
`tests/Unit/ExceptionClassesTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class ExceptionClassesTest extends Tests\TestCase`

## Subject Under Test
- `Core\Exceptions\ContainerException`, `Core\Exceptions\EntryNotFoundException`, `Core\Exceptions\HttpException`

## Test Methods
- `test_container_exception_implements_psr11` — `:24`
- `test_container_exception_has_message` — `:30`
- `test_entry_not_found_implements_psr11` — `:38`
- `test_entry_not_found_has_message` — `:44`
- `test_http_exception_has_status_code` — `:52`
- `test_http_exception_default_403` — `:60`
- `test_http_exception_default_500` — `:67`

## Cross References
- **Tests:** `Core\Exceptions\ContainerException` (see `DOCS/core/Exceptions/ContainerException.md`), `Core\Exceptions\EntryNotFoundException` (see `DOCS/core/Exceptions/EntryNotFoundException.md`), `Core\Exceptions\HttpException` (see `DOCS/core/Exceptions/HttpException.md`)

## Source References
- `tests/Unit/ExceptionClassesTest.php:1-74`
