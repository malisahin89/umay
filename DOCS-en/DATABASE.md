# Database Layer

## Purpose
Documents the database access layer: connection management, raw queries, and transactions.

## Overview
`Core\Database` is a static wrapper around the Illuminate (Eloquent) **Capsule Manager**. It configures a single connection, boots Eloquent globally, and exposes raw query and transaction helpers. All model-based access flows through this same connection.

## Initialization
`Database::init(array $config): Capsule` (idempotent — guarded by a static instance):
- **sqlite** driver (used for tests / `:memory:`) or **mysql** driver (default).
- MySQL options set `ERRMODE_EXCEPTION`, `FETCH_OBJ`, disable emulated prepares, and pin `SET NAMES … COLLATE …`.
- Registers a real Illuminate event `Dispatcher` so Model events (creating/saved/deleted) and observers work.
- Calls `setAsGlobal()` and `bootEloquent()`.
- In debug mode, registers a query listener that feeds the profiler (`DebugBar::addQuery`) when `UMAY_PROFILING` is defined.

Configuration comes from `config/database.php`; the test bootstrap can instead init an in-memory SQLite DB.

## Public API (static)
- `getConnection(string $name = 'default'): Connection` — throws `RuntimeException` if not initialized.
- Raw queries: `statement()`, `select()`, `selectOne()`, `insert()`, `update()` (affected rows), `delete()` (affected rows).
- Transactions: `transaction(callable)`, `beginTransaction()`, `commit()`, `rollBack()`.
- Lifecycle: `closeConnection()`, `closeAllConnections()`, `getActiveConnectionCount()`.

Usage is also exposed via the `DB` facade (`Core\Facades\DB`), e.g. `DB::table('users')->where('id',1)->first()`.

## Cross References
- **ORM:** `Core\Model` extends Eloquent over this connection (see `DOCS/ORM.md`)
- **Facade:** `Core\Facades\DB` (see `DOCS/core/Facades/DB.md`)
- **Migrations/Seeders/Factories:** see `DOCS/database/index.md`
- **Bootstrapped in:** `config/database.php`, `tests/bootstrap.php`

## Security Observations
- Prepared statements with bindings across `select/insert/update/delete`; emulated prepares disabled.

## Source References
- `core/Database.php:26-225`
- `composer.json:28-30` (illuminate/database, illuminate/events)
