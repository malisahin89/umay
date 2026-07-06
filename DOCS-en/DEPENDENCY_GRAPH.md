# Dependency Graph

## Purpose
Documents external (Composer) and key internal dependencies.

## External Dependencies (composer.json)
| Package | Used by |
|---------|---------|
| `illuminate/database ^10.0` | `Core\Database` (Capsule), `Core\Model` (Eloquent), `Core\Validator` (`DB::table` in unique/exists) |
| `illuminate/events ^10.0` | Eloquent event dispatcher wired in `Core\Database::init` |
| `illuminate/pagination ^10.0` | `Core\Paginator` |
| `league/plates ^3.4` | `Core\View` template engine |
| `vlucas/phpdotenv ^5.6` | `.env` loading (front controller / test bootstrap) |
| `php >= 8.2` | Whole framework |

Dev-only: `laravel/pint`, `phpstan/phpstan ^2.1`, `phpunit/phpunit ^10.0`.

## Key Internal Dependencies
- `Core\Application` → `Core\Container` (composition); resolves `Core\ExceptionHandler`.
- `Core\Route` → middleware classes (resolved via `config('middleware.namespaces')`), controllers (via `config('app.controller_namespace')`), `Core\Request`.
- `Core\RateLimiter` → `Core\Cache` (resolved from `Container`).
- `Core\Auth` → `Core\Contracts\UserProvider` / `Authenticatable` (concrete from `config/auth.php`), sessions, cookies.
- `Core\View` → `League\Plates\Engine`, `Core\Csrf`, `Core\Csp`, `Core\Profiler\Profiler`, `route()/asset()/old()` helpers.
- `Core\Cache`, `Core\Logger`, `Core\Database`, `Core\View` → `Core\DebugBar` (profiling hooks, guarded by `UMAY_PROFILING`).
- `Core\Providers\FacadeServiceProvider` → binds singletons for cache/auth/logger/route/database/dispatcher/view/rate-limiter/validator.

## Container-Resolved Services (singletons)
`Cache`, `Auth`, `Logger`, `Route`, `Database`, `Events\Dispatcher`, `View`, `RateLimiter`, `Validator` (proxy) — see `DOCS/SERVICE_PROVIDERS.md` and `DOCS/SERVICE_MATRIX.md`.

## Cross References
- `DOCS/PACKAGE_STRUCTURE.md`, `DOCS/CONTAINER.md`, `DOCS/SERVICE_PROVIDERS.md`

## Source References
- `composer.json:26-38`
- `core/RateLimiter.php:30-35`, `core/Providers/FacadeServiceProvider.php`, `config/middleware.php:163-166`, `config/app.php:45`
