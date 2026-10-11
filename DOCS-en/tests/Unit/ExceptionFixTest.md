# File Report: tests/Unit/ExceptionFixTest.php

## Purpose
Regression tests for exception class relationships and resolution.

## Overview
Verifies that `RedirectException` extends `TerminateException` and that `ExceptionHandler` can be resolved from the container.

## File Location
`tests/Unit/ExceptionFixTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class ExceptionFixTest extends Tests\TestCase`

## Subject Under Test
- `Core\RedirectException`, `Core\TerminateException`, `Core\ExceptionHandler`

## Test Methods
- `test_redirect_exception_extends_terminate_exception` — `:25`
- `test_exception_handler_resolves_from_container` — `:33`

## Cross References
- **Tests:** `Core\RedirectException` (see `DOCS/core/RedirectException.md`), `Core\TerminateException` (see `DOCS/core/TerminateException.md`), `Core\ExceptionHandler` (see `DOCS/core/ExceptionHandler.md`)

## Source References
- `tests/Unit/ExceptionFixTest.php:1-61`
