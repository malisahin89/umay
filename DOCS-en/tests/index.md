# Directory Report: tests

## Purpose
Automated test suite for the framework, run under PHPUnit.

## Child Directories
- `Feature/` — feature-level tests (see `Feature/index.md`)
- `Unit/` — component unit tests (see `Unit/index.md`)

## Source Files
- `bootstrap.php` — PHPUnit bootstrap: env, database, helpers (see `bootstrap.md`)
- `TestCase.php` — base test case with framework-aware setup and helpers (see `TestCase.md`)

## Public Entry Points
- `tests/bootstrap.php` (referenced by `phpunit.xml`).

## Internal Dependencies
- `Core\Database`, `core/helpers.php`, `Core\Auth`, `Core\Container`, `Core\Events\Dispatcher`, `Core\Request`, `App\Models\User`.

## External Dependencies
- PHPUnit, `vlucas/phpdotenv`.

## Cross References
- **Configured By:** `phpunit.xml` (see `DOCS/phpunit.xml.md`)
- **Bootstraps:** `Core\Database`, `core/helpers.php`

## Source References
- `tests/`
- `tests/bootstrap.php:1-32`, `tests/TestCase.php:1-166`
