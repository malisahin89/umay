# File Report: core/Seeder.php

## Purpose
Base class for database seeders.

## Overview
Provides a structure for populating the database with initial or dummy data. Seeders can call other seeders to organize data population.

## File Location
`core/Seeder.php`

## Namespace
`Core`

## Imports
- `Illuminate\Database\Capsule\Manager as DB`

## Classes
- `abstract class Seeder`

## Methods
- `run(): void`: Abstract method where the seeding logic is implemented.
- `call(string|array $seederClass): void`: Runs other seeder classes.
- `insert(string $table, array $data): void`: Inserts raw data into a table.
- `truncateAndInsert(string $table, array $rows): void`: Clears a table (ignoring foreign keys) and inserts new rows.
- `count(string $table): int`: Returns the number of records in a table.

## Source References
- `core/Seeder.php:1-69`
