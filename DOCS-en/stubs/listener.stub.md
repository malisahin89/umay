# File Report: stubs/listener.stub

## Purpose
Code-generation template for an event listener.

## Overview
Template rendered by the console generator to scaffold a new listener under `App\Listeners`, extending `Core\Events\Listener` with a `handle()` method that receives an `Event`.

## File Location
`stubs/listener.stub`

## Generated Artifact
- **Namespace:** `App\Listeners`
- **Class:** `{{ClassName}} extends Core\Events\Listener`
- **Imports:** `Core\Events\Event`, `Core\Events\Listener`
- **Methods:** `handle(Event $event): void`

## Placeholders
- `{{ClassName}}` — generated listener class name.

## Cross References
- **Consumed By:** `Core\Console\Kernel::renderStub()` — `core/Console/Kernel.php:336`, `core/Console/Kernel.php:699-703`

## Source References
- `stubs/listener.stub:1-16`
- `core/Console/Kernel.php:336`
