# Validator.php

## Purpose
The `Validator` class is a standalone validation engine used to verify data against a set of rules. It is used by the `validate()` helper and `Core\FormRequest`.

## Metadata
- **Namespace**: `Core`
- **File Location**: `core/Validator.php`

## Dependencies
- `Core\Database` (via `DB` facade for `unique` and `exists` rules)

## Key Methods
- `make(array $data, array $rules, array $messages)`: Factory method that creates a validator instance and runs the validation.
- `fails()`: Returns true if validation errors were found.
- `passes()`: Returns true if no validation errors were found.
- `errors()`: Returns an array of validation error messages.

## Internal Workflow
1. **Rule Processing**: The `run()` method iterates through the defined rules.
2. **Conditional Validation**: The `sometimes` rule allows skipping validation if a field is missing from the input.
3. **Value Handling**:
    - **Raw Value**: Used for length and equality checks (min/max/confirmed) to preserve leading/trailing spaces.
    - **Trimmed Value**: Used for format checks (email/numeric/url).
4. **Rule Application**: A `match` expression maps rule names (e.g., `required`, `email`, `unique`) to specific internal validation methods.
5. **Error Collection**: When a rule fails, `addError()` is called. It looks for a custom message in the following order: `field.rule` $\to$ `field` $\to$ `default`.

## Supported Rules
The validator supports a wide array of rules:
- **Presence**: `required`, `sometimes`.
- **String/Numeric**: `min`, `max`, `numeric`, `integer`, `digits`, `digits_between`, `alpha`, `alpha_num`.
- **Format**: `email`, `url`, `regex`, `date`.
- **Comparison**: `confirmed`, `same`.
- **Set Membership**: `in`, `not_in`.
- **Database**: `unique`, `exists`.
- **Date Range**: `before`, `after`.
