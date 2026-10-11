# Service Matrix

## Purpose
Lists services registered in the container and how they are resolved.

## Overview
`Core\Providers\FacadeServiceProvider::register()` binds the core services as **singletons** in `Core\Container`. They are resolved via `Container::make()` — directly or through their facades (`Core\Facades\*`), whose short aliases are registered in `boot()` from `config('app.aliases')`.

## Registered Singletons
| Service (abstract) | Concrete / Factory | Lifetime | Resolved via | Source |
|--------------------|--------------------|----------|--------------|--------|
| `Core\Cache` | `new Cache` | singleton | `Cache` facade / `make()` | `core/Providers/FacadeServiceProvider.php:48` |
| `Core\Auth` | `new Auth` | singleton | `Auth` facade / `make()` | `:52` |
| `Core\Logger` | `new Logger` | singleton | `Log` facade / `make()` | `:56` |
| `Core\Route` | `new Route(null, null)` | singleton | `Route` facade / `make()` | `:65` |
| `Core\Database` | `new Database` | singleton | `DB` facade / `make()` | `:74` |
| `Core\Events\Dispatcher` | `Dispatcher::getInstance()` | singleton | `Event` facade / `make()` | `:78` |
| `Core\Validator` | anonymous proxy → `Validator::make()` | singleton | `Validator` facade / `make()` | `:89` |
| `Core\View` | `new View` | singleton | `View` facade / `make()` | `:99` |
| `Core\RateLimiter` | `new RateLimiter` | singleton | `RateLimiter` facade / `make()` | `:103` |

Also bound at runtime: `Core\Request` (as an instance via `Application::captureRequest()`), and `Core\ExceptionHandler` (resolved from the container if bound, else instantiated).

## Facade Aliases (`config/app.php` → `aliases`)
`Cache`, `Auth`, `Log`, `DB`, `Event`, `Validator`, `RateLimiter` (Route and View aliases intentionally disabled).

## Cross References
- `DOCS/CONTAINER.md`, `DOCS/SERVICE_PROVIDERS.md`, `DOCS/core/Providers/FacadeServiceProvider.md`, `DOCS/core/Support/Facade.md`

## Source References
- `core/Providers/FacadeServiceProvider.php:37-124`
- `config/app.php:106-116`, `core/Application.php:159-165`
