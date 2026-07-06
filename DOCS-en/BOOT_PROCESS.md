# Boot Process

This report details the sequence of events that occur from the moment the server receives a request until the application is fully initialized and ready to dispatch.

## Sequence of Initialization

### 1. Entry Point Execution
The process begins in `public/index.php`.

### 2. Application Instantiation
The `Core\Application` singleton is retrieved via `Application::getInstance()`. This creates the application instance and initializes the `Core\Container`.

### 3. Service Provider Registration (`register` phase)
The application registers various service providers. A critical provider is the `Core\Providers\FacadeServiceProvider`, which is typically registered first.
- **Registration**: The `register()` method of each provider is executed.
- **Core Services**: `FacadeServiceProvider::register()` binds the following as singletons in the container:
    - `Core\Cache`
    - `Core\Auth`
    - `Core\Logger`
    - `Core\Route`
    - `Core\Database`
    - `Core\Events\Dispatcher`
    - `Core\Validator` (via a factory wrapper)
    - `Core\View`
    - `Core\RateLimiter`

### 4. Request Capture
`Application::captureRequest()` is called:
- It invokes `Core\Request::capture()`.
- The resulting `Request` instance is bound to the container as a singleton, making it accessible to all subsequent services.

### 5. Service Provider Booting (`boot` phase)
Once all providers are registered and the request is captured, `Application::boot()` is called. This executes the `boot()` method of every registered provider.
- **Facade Aliasing**: `FacadeServiceProvider::boot()` reads the `aliases` array from `config/app.php` and uses `class_alias()` to map facade classes to short global names (e.g., `\Core\Facades\Cache` $\to$ `Cache`).
- **Route Loading**: `App\Providers\RouteServiceProvider::boot()` loads the route definitions from `routes/web.php` and `routes/api.php`.

## Summary Timeline

| Step | Action | Component | Purpose |
| :--- | :--- | :--- | :--- |
| 1 | `getInstance()` | `Application` | Entry & Container setup |
| 2 | `register()` | `ServiceProvider` | Bind services to Container |
| 3 | `captureRequest()` | `Application` | Bind current HTTP request |
| 4 | `boot()` | `ServiceProvider` | Run boot logic & load routes |
| 5 | `dispatch()` | `Route` | Match request to action |
