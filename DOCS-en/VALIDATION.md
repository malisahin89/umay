# Validation

## Purpose
Documents input validation.

## Overview
`Core\Validator` is a standalone validator used by the `validate()` helper and `Core\FormRequest`. It is created via `Validator::make($data, $rules, $messages)`, immediately runs, and exposes `errors()`, `fails()`, `passes()`.

## Rule Syntax
Rules are given per field as a `|`-delimited string or an array. `sometimes` skips a field that is absent; most rules no-op on empty values (so non-required fields pass when blank). Array values are rejected early with a `type` error.

## Supported Rules
`required`, `sometimes`, `min:N`, `max:N`, `email`, `numeric`, `integer`, `confirmed`, `same:field`, `in:a,b,c`, `not_in:a,b`, `alpha`, `alpha_num` (`alphanumeric`), `url`, `regex:/pattern/`, `digits:N`, `digits_between:min,max`, `date`, `before:date`, `after:date`, `unique:table,col[,ignoreId]`, `exists:table,col`.

- `alpha`/`alpha_num` accept Turkish letters.
- `min`/`max`/`confirmed`/`same` compare the **raw** (un-trimmed) value so values like `" secret "` are measured/compared byte-for-byte; other rules use the trimmed value.
- `unique`/`exists` query the DB (`DB::table`) and sanitize table/column names with a regex allow-list before use.

## Error Messages
Custom messages resolve by priority `"field.rule"` → `"field"` → built-in default. Messages are bilingual (English // Turkish) by default.

## Integration
- `validate($data, $rules, $messages): ?array` (helper) returns `null` when valid, else the error array.
- `Core\FormRequest` uses the validator with `authorize()`/`rules()`/`messages()` for request-object validation.

## Cross References
- `DOCS/core/Validator.md`, `DOCS/core/FormRequest.md`, `DOCS/core/helpers.md`
- `DOCS/core/Facades/Validator.md`, tests: `DOCS/tests/Unit/ValidatorTest.md`, `DOCS/tests/Unit/ValidatorExtendedTest.md`

## Source References
- `core/Validator.php:19-335`
- `core/Validator.php:117-141` (rule dispatch), `core/Validator.php:289-334` (unique/exists sanitization)
