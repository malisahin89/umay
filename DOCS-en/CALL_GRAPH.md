# Call Graph

## Purpose
Documents the primary runtime call flow of a request. See `DOCS/REQUEST_LIFECYCLE.md` and `DOCS/BOOT_PROCESS.md` for narrative detail.

## Front Controller → Dispatch (`public/index.php`)
1. Define `BASE_PATH`; require `vendor/autoload.php`, `config/database.php`, `core/helpers.php`.
2. `Profiler::init()`; define `UMAY_PROFILING = Profiler::isEnabled()`.
3. Detect API vs web (`config('middleware.api_prefix')`).
4. Web only: set secure session ini flags, `session_name()`, `session_start()`, idle-timeout check (`config('session.lifetime')`), record `last_activity`.
5. `date_default_timezone_set(config('app.timezone'))`.
6. `register_shutdown_function` → `Profiler::finish()` when enabled.
7. Boot: `Application::getInstance()` → `captureRequest()` → `register(FacadeServiceProvider, EventServiceProvider, RouteServiceProvider)` → `boot()`.
8. `Route::dispatch()` inside try/catch; on `Throwable` → `$app->handleException($e)`.

## Boot (`Core\Application`)
- `getInstance()` composes over `Container::getInstance()`.
- `captureRequest()` → `Request::capture()` bound as `Request::class` instance.
- `register($provider)` instantiates the provider (must be a `ServiceProvider`) and runs `register()`.
- `boot()` runs each provider's `boot()` once (`$booted` guard). `RouteServiceProvider::boot()` loads `routes/web.php` + `routes/api.php`.

## Dispatch (`Core\Route::dispatch`)
- Matches method + URI against compiled routes → resolves middleware (via `config('middleware.namespaces')`) → runs the middleware pipeline (`handle($request, $next)`) → invokes the controller action (string `Controller@method` resolved via `config('app.controller_namespace')`, or a closure) → sends the response.

## Error Path
- Any `Throwable` → `Application::handleException()` → `ExceptionHandler::handle()` (resolved from container if bound) → web HTML error page or API JSON (see `DOCS/ERROR_HANDLING.md`).

## Rendering Path
- Controller → `View::render($template, $data)` → Plates engine → (profiler measures + toolbar injection when enabled) → echo.

## Cross References
- `DOCS/REQUEST_LIFECYCLE.md`, `DOCS/BOOT_PROCESS.md`, `DOCS/ROUTING_SYSTEM.md`, `DOCS/ERROR_HANDLING.md`, `DOCS/VIEW_ENGINE.md`

## Source References
- `public/index.php:1-125`
- `core/Application.php:52-165`
