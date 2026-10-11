# File Report: core/Migration.php

## Purpose
Base class for database migrations.

## Overview
Provides a structured way to define database schema changes. Every migration file extends this class and implements `up()` (to apply changes) and `down()` (to reverse them).

## File Location
`core/Migration.php`

## Namespace
`Core`

## Imports
- `Illuminate\Database\Capsule\Manager as DB`

## Classes
- `abstract class Migration`

## Methods
- `up(): void`: Abstract method to implement the migration logic.
- `down(): void`: Abstract method to implement the rollback logic.
- `execute(string $sql): void`: Executes a raw SQL statement via Eloquent.
- `query(string $sql, array $params = []): array`: Executes a prepared select query.
- `tableExists(string $table): bool`: Checks if a table exists in the database (driver-aware).
- `columnExists(string $table, string $column): bool`: Checks if a column exists in a table (driver-aware).

## Source References
- `core/Migration.php:1-65`
