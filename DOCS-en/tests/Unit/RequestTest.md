# File Report: tests/Unit/RequestTest.php

## Purpose
Unit tests for the HTTP request object.

## Overview
Verifies `Core\Request`: Bearer token extraction from the Authorization header, `expectsJson` detection (bearer token / Accept header), access to POST and GET data, `only` field filtering, `filled` semantics, and HTTP method detection.

## File Location
`tests/Unit/RequestTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class RequestTest extends Tests\TestCase`

## Subject Under Test
- `Core\Request`

## Test Methods
- `test_bearer_token_extracted_from_authorization_header` — `:11`
- `test_bearer_token_returns_null_when_no_header` — `:17`
- `test_bearer_token_returns_null_when_not_bearer_scheme` — `:24`
- `test_expects_json_true_when_bearer_token_present` — `:30`
- `test_expects_json_true_when_accept_json` — `:36`
- `test_post_data_accessible` — `:42`
- `test_get_data_accessible` — `:49`
- `test_only_filters_fields` — `:56`
- `test_filled_returns_false_for_empty_string` — `:65`
- `test_filled_returns_true_for_non_empty` — `:71`
- `test_method_detection` — `:77`

## Cross References
- **Tests:** `Core\Request` (see `DOCS/core/Request.md`)

## Source References
- `tests/Unit/RequestTest.php:1-84`
