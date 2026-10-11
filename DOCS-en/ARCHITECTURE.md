# Umay Framework Architecture Overview

## 1. System Design
Umay is a minimal, high-performance PHP MVC framework designed for both traditional Web and stateless API applications. It employs a **Front Controller** pattern where all requests are routed through `public/index.php`.

## 2. Core Components

### Dependency Injection & Container
The `Core\Container` is the backbone of the framework. It manages service lifecycles (singletons vs factories) and allows for automatic dependency resolution.

### Routing System
The `Core\Route` system supports:
- **Web Routes**: Statefull, session-enabled, CSRF-protected.
- **API Routes**: Stateless, prefixed (default `/api`), optimized for Bearer token auth.
- **Features**: Named routes, Route Groups, Resource Controllers (`resource` and `apiResource`).

### Middleware Pipeline
Requests pass through a pipeline of middleware before reaching the controller.
- **Global Middleware**: Applied to all requests.
- **Group Middleware**: Applied to specific route groups (e.g., `api` group).
- **Route-specific Middleware**: Applied via `->middleware()`.

### Authentication & Authorization
- **Guards**: Supports multiple authentication guards.
- **API Auth**: Implements Bearer tokens stored in `personal_access_tokens` with ability-based (permission) checks.
- **User Providers**: Extensible via `UserProvider` interface; default implementation uses Eloquent.

### Database & ORM
Integrated with `illuminate/database` (Eloquent).
- **Migrations**: Version-controlled schema changes.
- **Seeders & Factories**: Automated test data generation.
- **Model**: Base `Core\Model` provides active-record capabilities.

### View Engine
Uses `league/plates` for templating.
- **Layouts**: Master templates with sections.
- **Partials**: Reusable UI components.
- **Security**: Native support for CSP nonces via `$this->nonce()`.

### Debugging & Profiling
A built-in `Profiler` system captures request metrics:
- **Storage**: Snapshots saved as JSON in `storage/profiler/`.
- **Toolbar**: A compact overlay on web pages for quick diagnostics.
- **Control**: Independent configuration via `PROFILER_ENABLED`.

## 3. Request Lifecycle
1. **Entry**: Request hits `public/.htaccess` $\rightarrow$ `public/index.php`.
2. **Bootstrapping**:
    - `Profiler::init()`
    - `Application` singleton instantiation.
    - `FacadeServiceProvider`, `EventServiceProvider`, `RouteServiceProvider` registration.
    - `Application::boot()` (resolves providers and loads routes).
3. **Dispatch**: `Route::dispatch()` matches the URI to a handler.
4. **Pipeline**: Middleware are executed in order.
5. **Execution**: Controller method is called $\rightarrow$ returns a `Response`.
6. **Termination**: `Profiler::finish()` saves diagnostic data.

## 4. Directory Structure Summary
- `app/`: Application-specific logic (Controllers, Models, Providers).
- `config/`: Configuration files for all core services.
- `core/`: The framework kernel (Container, Route, Auth, View, etc.).
- `database/`: Migrations, Seeders, and Factories.
- `public/`: Web root (index.php, assets).
- `routes/`: Route definition files (`web.php`, `api.php`).
- `storage/`: Writable area for logs, cache, and profiles.
- `views/`: Template files.
