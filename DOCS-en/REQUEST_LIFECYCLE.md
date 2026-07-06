# Request Lifecycle

This report describes the flow of an HTTP request through the Umay framework.

## 1. Entry Point
Every request starts at the public entry point (typically `public/index.php`).

## 2. Bootstrapping
The `Core\Application` instance is retrieved via `getInstance()`.
1. **Container Initialization**: The application initializes the `Core\Container` for dependency management.
2. **Provider Registration**: Service Providers (e.g., `RouteServiceProvider`) are registered using `$app->register()`, triggering their `register()` methods.
3. **Request Capture**: `Application::captureRequest()` is called, which:
    - Uses `Core\Request::capture()` to create a request object from PHP globals.
    - Binds this `Request` instance into the `Container` as a singleton.
4. **Application Boot**: `Application::boot()` is called, executing the `boot()` methods of all registered providers.

## 3. Routing & Dispatching
The `Core\Route::dispatch()` method is invoked:
1. **URI Normalization**: The request URI is captured and trimmed.
2. **Canonical Redirect**: If the request is a GET/HEAD and has a trailing slash, it is 301-redirected to the slashless version (if a matching route exists).
3. **Profiler Intercept**: Requests starting with `/_profiler` are handled immediately by `ProfilerController`.
4. **Method Spoofing**: If the method is POST, the router checks for a `_method` parameter to spoof PUT, PATCH, or DELETE.
5. **Route Matching**:
    - **Exact Match**: Checks if the URI exists as a key in the routes table.
    - **Pattern Match**: Iterates through compiled regex patterns to find a match.
    - If no match is found, `abort(404)` is called.
6. **Parameter Binding**: Matched route parameters (e.g., `{id}`) are extracted and bound to the `Request` object.

## 4. The Middleware Pipeline
The router identifies all applicable middleware:
- **Global Middleware**: Defined in `config/middleware.php` under `global`.
- **Group Middleware**: Defined under `web` or `api` depending on the route group.
- **Route Middleware**: Specific middleware attached to the route definition.

These are wrapped in an "onion" pipeline using `array_reduce`. Each middleware receives the request and a `$next` closure to pass the request further down the chain.

## 5. Action Execution
Once the pipeline reaches the end, the route action is executed:
- **View Route**: Renders a template via `Core\View`.
- **Redirect Route**: Sends a location header and terminates.
- **Controller Action**: 
    - The controller is resolved from the `Container`.
    - The method is called using reflection to inject dependencies (Request, FormRequest, or route parameters).
- **Closure**: The closure is executed directly.

## 6. Response Delivery
The result of the action is handled:
- If a `Core\ResponseBuilder` is returned, its `send()` method is called.
- If a string or number is returned, it is echoed.
- If an array or object is returned, it is JSON-encoded and echoed.

## 7. Exception Handling
If any `Throwable` occurs during this process, `Application::handleException()` catches it and delegates it to the `Core\ExceptionHandler`.
