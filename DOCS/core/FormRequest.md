# File Report: core/FormRequest.php

## Purpose
Specialized Request class for automated validation and authorization.

## Overview
Extends the base `Request` class to provide a structured way to define validation rules and authorization logic. It is designed to be injected into controller methods.

## File Location
`core/FormRequest.php`

## Namespace
`Core`

## Classes
- `abstract class FormRequest extends Request`

## Properties
- `array $validatedData`: Stores data that has passed validation.

## Methods
- `rules(): array`: Abstract method to define validation rules.
- `messages(): array`: Returns custom error messages for the rules.
- `authorize(): bool`: Performs authorization check; returns `false` to trigger a 403 Forbidden.
- `createFrom(Request $parent): static`: Factory method to create a `FormRequest` from a standard `Request`, preserving its state.
- `validated(): array`: Returns the data that was successfully validated.

## Internal Workflow
1. `resolve()`: Triggered upon creation.
2. **Authorization**: Calls `authorize()`. If `false`, returns 403.
3. **Validation**: Uses `Validator::make()` with `rules()` and `messages()`.
4. **Failure Handling**: If validation fails:
    - For JSON requests: Returns a 422 response.
    - For Web requests: Flashes errors and old input to the session and redirects back.
5. **Data Filtering**: Stores only the keys defined in `rules()` into `$validatedData`.

## Dependencies
- `Core\Request` (Extends)
- `Core\Validator` (Uses)
- `Core\Response` (Uses)

## Source References
- `core/FormRequest.php:1-108`
