# File Report: core/Console/Kernel.php

## Purpose
Console command runner for the framework.

## Overview
Processes CLI commands entered via `php umay <command>`. It handles everything from generating application stubs (controllers, models, etc.) to managing database migrations and running tests.

## File Location
`core/Console/Kernel.php`

## Namespace
`Core\Console`

## Classes
- `class Kernel`

## Methods
- `handle(array $argv): int`: The main entry point. Dispatches the command based on the first argument.
- `keyGenerate(array $args): int`: Generates a random `APP_KEY` and updates the `.env` file.
- `makeController()`, `makeModel()`, `makeMigration()`, `makeMiddleware()`, `makeRequest()`, `makeMail()`, `makeEvent()`, `makeListener()`, `makeFactory()`, `makeTest()`: Generate new files based on stubs.
- `migrate()`: Runs pending migrations via `Migrator`.
- `migrateRollback()`: Rolls back the most recent migration.
- `migrateFresh()`: Wipes the database and re-runs all migrations and seeders.
- `storageLink(): int`: Creates a symlink from `public/storage` to `storage/app/public` for web access to uploaded files.
- `dbSeed(array $args): int`: Runs the database seeders.
- `routeList(): int`: Outputs a table of all registered routes and their handlers.
- `cacheClear(): int`: Deletes all cache files from the storage directory.
- `runTests(array $args): int`: Executes PHPUnit tests.

## Internal Workflow
- **Stub Rendering**: Uses `renderStub()` to replace placeholders in `.stub` files with actual class names and variables.
- **Case Conversion**: Includes `studlyCase()`, `snakeCase()`, and `pluralSnake()` for proper naming conventions.

## Dependencies
- `Core\Migration` (Uses)
- `Core\Migrator` (Uses)
- `Core\Route` (Uses)
- `Core\Seeder` (Uses)

## Source References
- `core/Console/Kernel.php:1-781`
