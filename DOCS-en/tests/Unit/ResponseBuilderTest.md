# File Report: tests/Unit/ResponseBuilderTest.php

## Purpose
Unit tests for the response builder.

## Overview
Verifies `Core\ResponseBuilder`: constructor body/status, `json` (content type, body, status), `html` body, fluent `header`/`withHeaders`/`status` methods, a chained fluent API, and that `download` throws when the file is missing.

## File Location
`tests/Unit/ResponseBuilderTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class ResponseBuilderTest extends Tests\TestCase`

## Subject Under Test
- `Core\ResponseBuilder`

## Test Methods
- `test_constructor_accepts_body_and_status` — `:20`
- `test_json_sets_content_type_and_body` — `:30`
- `test_json_with_status_code` — `:39`
- `test_html_sets_body` — `:49`
- `test_header_returns_fluent_instance` — `:59`
- `test_with_headers_merges_headers` — `:67`
- `test_status_method_returns_fluent` — `:80`
- `test_chained_fluent_api` — `:90`
- `test_download_throws_when_file_not_found` — `:103`

## Cross References
- **Tests:** `Core\ResponseBuilder` (see `DOCS/core/ResponseBuilder.md`)

## Source References
- `tests/Unit/ResponseBuilderTest.php:1-110`
