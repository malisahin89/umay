# File Report: stubs/test.stub

## Purpose
Code-generation template for a test case.

## Overview
Template rendered by the console generator to scaffold a new test class under the `Tests` namespace, extending `Tests\TestCase` with a single passing example test.

## File Location
`stubs/test.stub`

## Generated Artifact
- **Namespace:** `Tests\{{namespace}}`
- **Class:** `{{ClassName}} extends Tests\TestCase`
- **Imports:** `Tests\TestCase`
- **Methods:** `test_example(): void`

## Placeholders
- `{{namespace}}` — sub-namespace under `Tests` (e.g. `Unit`, `Feature`).
- `{{ClassName}}` — generated test class name.

## Cross References
- **Consumed By:** `Core\Console\Kernel::renderStub()` — `core/Console/Kernel.php:395`, `core/Console/Kernel.php:699-703`
- **Base Class:** `Tests\TestCase` (see `DOCS/tests/TestCase.md`)

## Source References
- `stubs/test.stub:1-15`
- `core/Console/Kernel.php:395`
