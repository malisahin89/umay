# File Report: tests/Unit/FileUploadTest.php

## Purpose
Unit tests for file upload handling and safety.

## Overview
Verifies `Core\FileUpload`: filename sanitization (Turkish/special-character stripping, uniqid fallback, path-traversal defense), path containment within `public/`, allowed MIME types (JPEG allowed, PHP rejected), the 2 MB size limit, and safe `rename`/`delete` behavior for default/empty/nonexistent paths.

## File Location
`tests/Unit/FileUploadTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class FileUploadTest extends Tests\TestCase`

## Subject Under Test
- `Core\FileUpload`

## Test Methods
- `test_sanitize_removes_turkish_characters` — `:28`
- `test_sanitize_removes_special_characters` — `:34`
- `test_sanitize_allows_alphanumeric_dash_underscore` — `:40`
- `test_sanitize_returns_uniqid_for_empty_result` — `:46`
- `test_sanitize_handles_path_traversal_attempt` — `:54`
- `test_path_inside_public_allows_valid_path` — `:71`
- `test_path_outside_public_throws_exception` — `:86`
- `test_allowed_types_includes_jpeg` — `:97`
- `test_allowed_types_does_not_include_php` — `:109`
- `test_max_size_is_2mb` — `:122`
- `test_rename_returns_false_for_default_png` — `:133`
- `test_rename_returns_false_for_empty_path` — `:139`
- `test_delete_returns_false_for_default_png` — `:147`
- `test_delete_returns_false_for_empty_path` — `:153`
- `test_delete_returns_false_for_nonexistent_file` — `:159`

## Cross References
- **Tests:** `Core\FileUpload` (see `DOCS/core/FileUpload.md`)

## Source References
- `tests/Unit/FileUploadTest.php:1-164`
