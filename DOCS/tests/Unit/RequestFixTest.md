# File Report: tests/Unit/RequestFixTest.php

## Purpose
Regression tests for form request state and JSON body parsing.

## Overview
Verifies that a `FormRequest` subclass reuses parent request state and that JSON request bodies are parsed. Defines a `TestFormRequest` fixture extending `Core\FormRequest`.

## File Location
`tests/Unit/RequestFixTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class TestFormRequest extends Core\FormRequest` (`:11`)
- `class RequestFixTest extends Tests\TestCase` (`:19`)

## Subject Under Test
- `Core\FormRequest`, `Core\Request`

## Test Methods
- `test_form_request_uses_parent_state` — `:21`
- `test_json_body_parsing` — `:48`

## Cross References
- **Tests:** `Core\FormRequest` (see `DOCS/core/FormRequest.md`), `Core\Request` (see `DOCS/core/Request.md`)

## Source References
- `tests/Unit/RequestFixTest.php:1-69`
