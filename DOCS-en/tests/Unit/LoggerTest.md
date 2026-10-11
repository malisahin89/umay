# File Report: tests/Unit/LoggerTest.php

## Purpose
Unit tests for the logger.

## Overview
Verifies `Core\Logger`: log files are created, entries carry the correct level (error/warning/info), each entry includes IP and user agent, files use daily rotation, and multiple logs append to the same file.

## File Location
`tests/Unit/LoggerTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class LoggerTest extends Tests\TestCase`

## Subject Under Test
- `Core\Logger`

## Test Methods
- `test_info_creates_log_file` — `:44`
- `test_error_log_contains_error_level` — `:54`
- `test_warning_log_contains_warning_level` — `:64`
- `test_info_log_contains_info_level` — `:73`
- `test_log_includes_ip_and_user_agent` — `:84`
- `test_log_file_uses_daily_rotation` — `:95`
- `test_multiple_logs_appended_to_same_file` — `:108`

## Cross References
- **Tests:** `Core\Logger` (see `DOCS/core/Logger.md`)

## Source References
- `tests/Unit/LoggerTest.php:1-124`
