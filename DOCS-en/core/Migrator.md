# File Report: core/Migrator.php

## Purpose
Database migration and seeding manager.

## Overview
Orchestrates the execution of migration files and seeders. It tracks which migrations have already run using a `migrations` table in the database. It is primarily designed for use via the console or a specialized route.

## File Location
`core/Migrator.php`

## Namespace
`Core`

## Imports
- `Core\Facades\Log`
- `Database\Seeders\DatabaseSeeder`
- `Illuminate\Database\Capsule\Manager as DB`
- `Illuminate\Database\Schema\Blueprint`

## Classes
- `class Migrator`

## Methods
- `runMigrationsOnly(): array`: Executes only pending migrations.
- `runSeedersOnly(): void`: Executes all seeders starting from `DatabaseSeeder`.
- `runFresh(): array`: Drops all tables, runs all migrations, and executes seeders.
- `runSingleMigration(string $filename, bool $force = false): void`: Executes a specific migration file.
- `runSingleSeeder(string $className): void`: Executes a specific seeder class.
- `run(): void`: Legacy method for backward compatibility.

## Internal Workflow
- `ensureMigrationsTable()`: Creates the `migrations` table if it doesn't exist.
- `runPendingMigrations()`: Scans `database/migrations/*.php`, sorts them, and runs those not present in the `migrations` table, assigning them a batch number.
- `dropAllTables()`: Uses the Eloquent Schema builder to remove all tables from the database.
- `disableForeignKeys()` / `enableForeignKeys()`: Toggles foreign key checks based on the driver (MySQL vs SQLite).
- `executeSeeders()`: Loads all seeder files and runs `DatabaseSeeder::run()`.

## Dependencies
- `Core\Migration` (Uses)
- `Core\Facades\Log` (Uses)
- `Database\Seeders\DatabaseSeeder` (Uses)
- `Illuminate\Database\Capsule\Manager` (Uses)

## Source References
- `core/Migrator.php:1-327`
