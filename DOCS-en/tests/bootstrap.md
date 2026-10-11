# File Report: tests/bootstrap.php

## Purpose
PHPUnit bootstrap file that prepares the test environment.

## Overview
Loaded before the test suite runs. It defines `BASE_PATH`, includes the Composer autoloader, loads environment variables (`.env.testing` if present, otherwise `.env`), initializes the database, and loads the global helper functions.

## File Location
`tests/bootstrap.php`

## Namespace
Global (no namespace).

## Imports
- `Core\Database`
- `Dotenv\Dotenv` (vendor)

## Internal Workflow
1. Defines `BASE_PATH` as the project root.
2. Requires `vendor/autoload.php`.
3. Loads env via `Dotenv::createImmutable()` using `.env.testing` when it exists, else `.env` (`safeLoad()`).
4. If `DB_DRIVER=sqlite`, calls `Database::init()` with an in-memory SQLite database; otherwise includes `config/database.php`.
5. Requires `core/helpers.php`.

## Cross References
- **Calls:** `Core\Database::init()` (see `DOCS/core/Database.md`)
- **Loads:** `core/helpers.php` (see `DOCS/core/helpers.md`)
- **Referenced By:** `phpunit.xml` bootstrap setting (see `DOCS/phpunit.xml.md`)

## Source References
- `tests/bootstrap.php:1-32`
