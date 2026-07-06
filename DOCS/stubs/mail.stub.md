# File Report: stubs/mail.stub

## Purpose
Code-generation template for a mailable class.

## Overview
Template rendered by the console generator to scaffold a new mailable under `App\Mail`, extending `Core\Mail\Mailable`. The generated `build()` method returns a fluent chain setting a subject and text body.

## File Location
`stubs/mail.stub`

## Generated Artifact
- **Namespace:** `App\Mail`
- **Class:** `{{ClassName}} extends Core\Mail\Mailable`
- **Imports:** `Core\Mail\Mailable`
- **Methods:** `__construct()`, `build(): static`

## Placeholders
- `{{ClassName}}` — generated mailable class name.

## Cross References
- **Consumed By:** `Core\Console\Kernel::renderStub()` — `core/Console/Kernel.php:288`, `core/Console/Kernel.php:699-703`

## Source References
- `stubs/mail.stub:1-22`
- `core/Console/Kernel.php:288`
