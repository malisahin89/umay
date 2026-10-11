# Directory Report: stubs

## Purpose
Holds the code-generation templates (`*.stub`) used by the console's `make:*` commands to scaffold new application classes.

## Child Directories
- None.

## Source Files
- `controller.stub` — RESTful controller (see `controller.stub.md`)
- `event.stub` — event class (see `event.stub.md`)
- `factory.stub` — model factory (see `factory.stub.md`)
- `listener.stub` — event listener (see `listener.stub.md`)
- `mail.stub` — mailable (see `mail.stub.md`)
- `middleware.stub` — HTTP middleware (see `middleware.stub.md`)
- `migration.stub` — table-creating migration (see `migration.stub.md`)
- `migration-soft-deletes.stub` — soft-delete column migration (see `migration-soft-deletes.stub.md`)
- `model.stub` — model (see `model.stub.md`)
- `request.stub` — form request (see `request.stub.md`)
- `test.stub` — test case (see `test.stub.md`)

## Public Entry Points
- None. Templates are read internally by the console kernel.

## Internal Dependencies
- All stubs are read and interpolated by `Core\Console\Kernel::renderStub()`.

## External Dependencies
- None.

## Cross References
- **Consumed By:** `Core\Console\Kernel` (`core/Console/Kernel.php:699-703`; per-command references at lines 155, 180, 203, 232, 260, 288, 312, 336, 361, 395).

## Source References
- `stubs/` directory
- `core/Console/Kernel.php:44-49`, `core/Console/Kernel.php:699-703`
