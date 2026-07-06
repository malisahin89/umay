# Service Providers

This report describes the Service Provider pattern used in the Umay framework to manage bootstrapping and service registration.

## Overview
Service Providers are the central place to configure the application. They allow for the modular registration of services into the `Core\Container`, ensuring that the framework core remains decoupled from specific implementations.

## The `Core\ServiceProvider` Base Class
All providers extend the `Core\ServiceProvider` class, which provides access to the `Container`.

### Lifecycle Methods

#### 1. `register()`
The `register()` method is called first. Its sole responsibility is to bind services into the container.
- **Rule**: Never attempt to resolve other services or use application logic inside `register()`, as other providers may not have been registered yet.

#### 2. `boot()`
The `boot()` method is called after all providers have been registered.
- **Rule**: This is the safe place to perform actions that require other services to be available, such as loading routes or registering event listeners.

## Core Provider Implementations

### `Core\Providers\FacadeServiceProvider`
This is the most critical core provider. It performs two main tasks:
1. **Core Service Binding**: Binds essential framework services as singletons in the container, including `Cache`, `Auth`, `Logger`, `Route`, `Database`, `Dispatcher`, `Validator`, `View`, and `RateLimiter`.
2. **Facade Aliasing**: Reads the `aliases` config from `config/app.php` and uses `class_alias()` to create global short names for facade classes.

### `App\Providers\RouteServiceProvider`
This application-level provider handles the loading of route files:
- **Web Routes**: Loads `routes/web.php` under the `web` middleware group.
- **API Routes**: Loads `routes/api.php` under the `api` middleware group and applies the `api_prefix` configured in `config/middleware.php`.

## Summary of Execution Flow
1. `Application::register(Provider::class)` $\to$ `Provider::register()` (Binds services).
2. `Application::boot()` $\to$ `Provider::boot()` (Execute boot logic).
