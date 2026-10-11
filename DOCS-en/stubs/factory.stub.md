# File Report: stubs/factory.stub

## Purpose
Code-generation template for a model factory.

## Overview
Template rendered by the console generator to scaffold a new factory under `Database\Factories`, extending `Core\Factory` and bound to a target model via the `$model` property.

## File Location
`stubs/factory.stub`

## Generated Artifact
- **Namespace:** `Database\Factories`
- **Class:** `{{ClassName}} extends Core\Factory`
- **Imports:** `App\Models\{{ModelClass}}`, `Core\Factory`
- **Properties:** `protected string $model = {{ModelClass}}::class`
- **Methods:** `definition(): array`

## Placeholders
- `{{ClassName}}` — generated factory class name.
- `{{ModelClass}}` — target model class the factory produces.

## Cross References
- **Consumed By:** `Core\Console\Kernel::renderStub()` — `core/Console/Kernel.php:361`, `core/Console/Kernel.php:699-703`

## Source References
- `stubs/factory.stub:1-20`
- `core/Console/Kernel.php:361`
