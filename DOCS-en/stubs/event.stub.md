# File Report: stubs/event.stub

## Purpose
Code-generation template for an event class.

## Overview
Template rendered by the console generator to scaffold a new event under `App\Events`, extending `Core\Events\Event` with an empty constructor.

## File Location
`stubs/event.stub`

## Generated Artifact
- **Namespace:** `App\Events`
- **Class:** `{{ClassName}} extends Core\Events\Event`
- **Imports:** `Core\Events\Event`
- **Methods:** `__construct()`

## Placeholders
- `{{ClassName}}` — generated event class name.

## Cross References
- **Consumed By:** `Core\Console\Kernel::renderStub()` — `core/Console/Kernel.php:312`, `core/Console/Kernel.php:699-703`

## Source References
- `stubs/event.stub:1-15`
- `core/Console/Kernel.php:312`
