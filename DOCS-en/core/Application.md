# File Report: core/Application.php

## Purpose
Framework bootstrap orchestrator.

## Overview
The central class that manages the application lifecycle, including ServiceProvider registration, booting, and exception handling. It sits on top of the `Container`.

## File Location
`core/Application.php`

## Namespace
`Core`

## Classes
- `class Application`

## Properties
- `static ?self $instance`: Singleton instance of the application.
- `ServiceProvider[] $providers`: List of registered service providers.
- `bool $booted`: Flag indicating if the application has been booted.
- `Container $container`: The dependency injection container.

## Methods
- `getInstance(): self`: Returns the singleton instance.
- `container(): Container`: Returns the container instance.
- `make(string $abstract): mixed`: Shortcut to resolve a class from the container.
- `instance(string $abstract, mixed $concrete): void`: Binds a specific instance to the container.
- `singleton(string $abstract, callable|string $concrete): void`: Binds a singleton to the container.
- `register(string $providerClass): static`: Registers a `ServiceProvider` and calls its `register()` method.
- `boot(): static`: Calls the `boot()` method of all registered service providers.
- `handleException(\Throwable $e): void`: Delegates exception handling to the `ExceptionHandler`.
- `captureRequest(): static`: Captures the current HTTP request and binds it to the container.

## Dependencies
- `Core\Container` (Uses)
- `Core\ServiceProvider` (Uses)
- `Core\ExceptionHandler` (Uses)
- `Core\Request` (Uses)

## Source References
- `core/Application.php:1-166`
