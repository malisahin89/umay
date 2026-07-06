# File Report: tests/Unit/ValidatorExtendedTest.php

## Purpose
Extended unit tests for the validator's rule set.

## Overview
Verifies `Core\Validator` rules beyond the basics: `sometimes`, `confirmed`, `same`, `in`, `not_in`, `alpha`, `alpha_num`, `url`, `regex`, `digits`, `digits_between`, `date`, `before`, `after`, array-value type rejection, custom messages, empty-value skipping of non-required rules, combined rules, `passes`, and array-form rules.

## File Location
`tests/Unit/ValidatorExtendedTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class ValidatorExtendedTest extends Tests\TestCase`

## Subject Under Test
- `Core\Validator`

## Test Methods
`sometimes` `:22-31`; `confirmed` `:42-51`; `same` `:62-71`; `in`/`not_in` `:82-111`; `alpha`/`alpha_num` `:122-142`; `url` `:150-156`; `regex` `:164-170`; `digits`/`digits_between` `:178-198`; `date`/`before`/`after` `:206-240`; array rejection `:248`; custom messages `:259`; empty-value skip `:274`; combined rules `:285`; `passes` `:299`; array-form rules `:313`.

## Cross References
- **Tests:** `Core\Validator` (see `DOCS/core/Validator.md`)

## Source References
- `tests/Unit/ValidatorExtendedTest.php:1-322`
