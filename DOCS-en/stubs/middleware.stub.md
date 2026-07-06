# File Report: stubs/middleware.stub

## Purpose
Code-generation template for an HTTP middleware.

## Overview
Template rendered by the console generator to scaffold a new middleware under `App\Middleware`, implementing `Core\Contracts\MiddlewareInterface` with a `handle()` method that forwards the request to the `$next` closure.

## File Location
`stubs/middleware.stub`

## Generated Artifact
- **Namespace:** `App\Middleware`
- **Class:** `{{ClassName}} implements Core\Contracts\MiddlewareInterface`
- **Imports:** `Core\Contracts\MiddlewareInterface`, `Core\Request`
- **Methods:** `handle(Request $request, \Closure $next): mixed`

## Placeholders
- `{{ClassName}}` — generated middleware class name (the `Middleware` suffix is enforced by the generator).

## Cross References
- **Consumed By:** `Core\Console\Kernel::renderStub()` — `core/Console/Kernel.php:232`, `core/Console/Kernel.php:699-703`
- **Implements Contract:** `Core\Contracts\MiddlewareInterface` (see `DOCS/core/Contracts/MiddlewareInterface.md`)

## Source References
- `stubs/middleware.stub:1-18`
- `core/Console/Kernel.php:213-233`
