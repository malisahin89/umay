# File Report: phpunit.xml

## Purpose
PHPUnit testing framework configuration.

## Overview
Defines the test suites (Unit, Feature), bootstrap file, and environment variables for the testing environment.

## File Location
`phpunit.xml`

## Configuration
- `bootstrap`: `tests/bootstrap.php`
- `cacheDirectory`: `.phpunit.cache`
- `Test Suites`:
    - `Unit`: `tests/Unit`
    - `Feature`: `tests/Feature`
- `Environment Variables`:
    - `APP_ENV`: testing
    - `DB_DRIVER`: sqlite

## Source References
- `phpunit.xml:1-33`
