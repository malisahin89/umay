# File Report: stubs/controller.stub

## Purpose
Code-generation template for a RESTful controller.

## Overview
Template rendered by the console generator to scaffold a new controller under `App\Controllers`. It produces a class extending `Controller` with the seven RESTful action methods (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`).

## File Location
`stubs/controller.stub`

## Generated Artifact
- **Namespace:** `App\Controllers`
- **Class:** `{{ClassName}} extends Controller`
- **Imports:** `Core\Facades\View`, `Core\Request`
- **Methods:** `index()`, `create()`, `store(Request)`, `show(Request, string $id)`, `edit(Request, string $id)`, `update(Request, string $id)`, `destroy(Request, string $id)`

## Placeholders
- `{{ClassName}}` — generated controller class name.
- `{{viewPrefix}}` — view path prefix used in `View::render()` calls.

## Cross References
- **Consumed By:** `Core\Console\Kernel::renderStub()` — `core/Console/Kernel.php:155`, `core/Console/Kernel.php:699-703`

## Source References
- `stubs/controller.stub:1-46`
- `core/Console/Kernel.php:155-176`
