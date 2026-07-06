# Boot Process (Application Bootstrapping)

The path the system takes to become ready to handle a request is highly systematic.

## Initialization Sequence

The process begins with the execution of the `public/index.php` file:

### 1. Application and Container Setup
When `Application::getInstance()` is called, the application singleton instance is created and the `Core\Container` (Dependency Injection System) is initialized.

### 2. Registration Phase (`register`)
Service providers (`ServiceProvider`) are registered sequentially. In this phase, only how services are bound to the container (binding) is defined; no logic is executed yet.
- Example: `FacadeServiceProvider` is called to define services such as `Cache`, `Auth`, and `DB`.

### 3. Request Capture (`captureRequest`)
The system captures the current HTTP request (`Core\Request`) and registers it as a singleton in the container. This allows the current request to be accessed from anywhere in the application.

### 4. Booting Phase (`boot`)
After all providers are registered, `Application::boot()` is called. In this phase:
- **Facade Aliases**: Short names (`Cache`, `Route`, etc.) defined in `config/app.php` are registered.
- **Route Loading**: Route definitions in `routes/web.php` and `routes/api.php` are read and the routing table is created.

### 5. Routing and Response (`dispatch`)
In the final step, `Route::dispatch()` is called. The request passes through defined middleware, reaches the appropriate controller method, and returns a `Response`.
