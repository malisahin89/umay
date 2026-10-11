# Directory Report: tests/Feature

## Purpose
Feature-level tests that exercise framework subsystems through higher-level behavior.

## Child Directories
- None.

## Source Files
- `AuthTest.php` — session-based auth and login validation (see `AuthTest.md`)

## Public Entry Points
- Executed by PHPUnit (see `DOCS/phpunit.xml.md`).

## Internal Dependencies
- Extends `Tests\TestCase`; exercises `Core\Auth` and the `validate()` helper.

## External Dependencies
- PHPUnit.

## Cross References
- **Base Class:** `Tests\TestCase` (see `DOCS/tests/TestCase.md`)

## Source References
- `tests/Feature/`
