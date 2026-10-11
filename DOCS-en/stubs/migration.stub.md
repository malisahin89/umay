# File Report: stubs/migration.stub

## Purpose
Code-generation template for a table-creating migration.

## Overview
Template rendered by the console generator to scaffold a new migration file under `database/migrations/`. It returns an anonymous class extending `Core\Migration`. The `up()` method creates a table (guarded by `tableExists()`) with `id`, `created_at`, and `updated_at` columns; `down()` drops it.

## File Location
`stubs/migration.stub`

## Generated Artifact
- **Class:** anonymous `class extends Core\Migration` (returned via `return new class ...`)
- **Imports:** `Core\Migration`
- **Methods:** `up(): void`, `down(): void`
- **Schema:** `id` (INT AUTO_INCREMENT PK), `created_at`, `updated_at`; engine InnoDB, charset `utf8mb4`.

## Placeholders
- `{{tableName}}` — target table name.
- `{{ClassName}}` — supplied by the generator (see `core/Console/Kernel.php:203-206`), though the emitted class is anonymous.

## Cross References
- **Consumed By:** `Core\Console\Kernel::renderStub()` — `core/Console/Kernel.php:203`, `core/Console/Kernel.php:699-703`
- **Base Class:** `Core\Migration` (see `DOCS/core/Migration.md`)

## Source References
- `stubs/migration.stub:1-27`
- `core/Console/Kernel.php:203-208`
