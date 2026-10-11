# File Report: core/Database.php

## Purpose
Database connection and Eloquent ORM wrapper.

## Overview
Initializes and manages the Eloquent Capsule Manager. It provides a static API for executing raw SQL queries and managing transactions, while enabling the use of Eloquent models across the application.

## File Location
`core/Database.php`

## Namespace
`Core`

## Imports
- `Illuminate\Container\Container`
- `Illuminate\Database\Capsule\Manager as Capsule`
- `Illuminate\Database\Connection`
- `Illuminate\Events\Dispatcher`
- `PDO`

## Classes
- `class Database`

## Properties
- `static ?Capsule $instance`: The Eloquent Capsule instance.

## Methods
- `init(array $config): Capsule`: Initializes the Eloquent Capsule with the provided configuration (MySQL or SQLite). Sets up the event dispatcher and boots Eloquent.
- `getConnection(string $name = 'default'): Connection`: Returns the Eloquent connection object.
- `statement(string $sql, array $bindings = []): bool`: Executes a raw SQL statement.
- `select(string $sql, array $bindings = []): array`: Executes a select query and returns results.
- `selectOne(string $sql, array $bindings = []): ?object`: Executes a select query and returns the first result.
- `insert(string $sql, array $bindings = []): bool`: Executes an insert query.
- `update(string $sql, array $bindings = []): int`: Executes an update query and returns affected rows.
- `delete(string $sql, array $bindings = []): int`: Executes a delete query and returns affected rows.
- `transaction(callable $callback): mixed`: Runs a callback within a database transaction.
- `beginTransaction(): void`: Starts a transaction.
- `commit(): void`: Commits the current transaction.
- `rollBack(): void`: Rolls back the current transaction.

## Internal Workflow
- `init()`: Configures the PDO connection, sets up `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`, and attaches a query listener for the `DebugBar` if debug mode is enabled.

## Dependencies
- `Illuminate\Database\Capsule\Manager` (Uses)
- `Illuminate\Events\Dispatcher` (Uses)
- `Core\DebugBar` (Optional profiling)

## Source References
- `core/Database.php:1-225`
