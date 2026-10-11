# File Report: stubs/model.stub

## Purpose
Code-generation template for an Eloquent-style model.

## Overview
Template rendered by the console generator to scaffold a new model under `App\Models`, extending `Core\Model` with a `$table` name and an empty `$fillable` array.

## File Location
`stubs/model.stub`

## Generated Artifact
- **Namespace:** `App\Models`
- **Class:** `{{ClassName}} extends Core\Model`
- **Imports:** `Core\Model`
- **Properties:** `protected $table = '{{tableName}}'`, `protected $fillable = []`

## Placeholders
- `{{ClassName}}` — generated model class name.
- `{{tableName}}` — backing database table.

## Cross References
- **Consumed By:** `Core\Console\Kernel::renderStub()` — `core/Console/Kernel.php:180`, `core/Console/Kernel.php:699-703`
- **Base Class:** `Core\Model` (see `DOCS/core/Model.md`)

## Source References
- `stubs/model.stub:1-16`
- `core/Console/Kernel.php:180`
