# File Report: stubs/request.stub

## Purpose
Code-generation template for a form request.

## Overview
Template rendered by the console generator to scaffold a new form request under `App\Requests`, extending `Core\FormRequest` with `authorize()`, `rules()`, and `messages()` methods.

## File Location
`stubs/request.stub`

## Generated Artifact
- **Namespace:** `App\Requests`
- **Class:** `{{ClassName}} extends Core\FormRequest`
- **Imports:** `Core\FormRequest`
- **Methods:** `authorize(): bool` (returns `true`), `rules(): array`, `messages(): array`

## Placeholders
- `{{ClassName}}` — generated form request class name.

## Cross References
- **Consumed By:** `Core\Console\Kernel::renderStub()` — `core/Console/Kernel.php:260`, `core/Console/Kernel.php:699-703`
- **Base Class:** `Core\FormRequest` (see `DOCS/core/FormRequest.md`)

## Source References
- `stubs/request.stub:1-29`
- `core/Console/Kernel.php:260`
