# File Report: stubs/migration-soft-deletes.stub

## Purpose
Code-generation template for a migration that adds a soft-delete column.

## Overview
Template returning an anonymous class extending `Core\Migration`. The `up()` method issues an `ALTER TABLE ... ADD COLUMN deleted_at TIMESTAMP NULL` statement; `down()` drops the column. It complements the soft-delete behavior in `Core\Concerns\SoftDeletes`.

## File Location
`stubs/migration-soft-deletes.stub`

## Generated Artifact
- **Class:** anonymous `class extends Core\Migration`
- **Imports:** `Core\Migration`
- **Methods:** `up(): void`, `down(): void`
- **Schema change:** adds / drops `deleted_at TIMESTAMP NULL DEFAULT NULL`.

## Placeholders
- `{{tableName}}` — target table to alter.

## Cross References
- **Base Class:** `Core\Migration` (see `DOCS/core/Migration.md`)
- **Related:** `Core\Concerns\SoftDeletes` (see `DOCS/core/Concerns/SoftDeletes.md`)

> No verified `renderStub('migration-soft-deletes')` call was found in the analyzed source code (`core/Console/Kernel.php`). The template exists but its generator invocation could not be confirmed.

## Source References
- `stubs/migration-soft-deletes.stub:1-24`
