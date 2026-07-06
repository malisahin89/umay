# Container

This report describes the Dependency Injection (DI) Container implementation in the Umay framework.

## Overview
The `Core\Container` is a PSR-11 compliant service container that manages class dependencies and object lifecycles. It allows for decoupled architecture by resolving dependencies automatically through reflection (auto-wiring).

## Core Functionality

### 1. Binding
The container allows mapping an "abstract" (usually an interface or class name) to a "concrete" implementation.

- **Simple Binding (`bind`)**: A new instance is created every time the abstract is resolved.
- **Singleton Binding (`singleton`)**: The first resolved instance is cached and returned for all subsequent requests.
- **Instance Binding (`instance`)**: A pre-existing object instance is bound to the abstract.

### 2. Resolution (`make` / `get`)
- **`make($abstract)`**: The primary method for resolving an entry. It attempts to resolve bindings first, then falls back to auto-wiring.
- **`get($id)`**: A PSR-11 compliant method. Unlike `make()`, it throws an `EntryNotFoundException` if the entry is not registered, disabling the auto-wire fallback.

### 3. Auto-Wiring (`build`)
When no binding exists, the container uses PHP's `ReflectionClass` to automatically resolve dependencies:
1. **Constructor Inspection**: It inspects the class constructor for type-hinted parameters.
2. **Recursive Resolution**: Each parameter is resolved recursively via `make()`.
3. **Union Type Support**: For PHP 8.0+ union types, the container attempts to resolve the first type that is registered or exists as a class.
4. **Default Values**: If a parameter cannot be resolved but has a default value, that value is used.
5. **Scalar Casting**: Built-in types (int, string, etc.) are not auto-wired and must have default values or be provided.

## Advanced Features

### Circular Dependency Protection
The container tracks classes currently being resolved in the `resolving` array. If a class attempts to resolve itself (directly or indirectly) during its own creation, a `ContainerException` is thrown, preventing infinite loops.

### Performance Optimization
To minimize the overhead of reflection, the container maintains a `reflectionCache`. Once a `ReflectionClass` is created for a concrete class, it is stored and reused.

## Summary of Methods

| Method | Description |
| :--- | :--- |
| `getInstance()` | Returns the singleton instance of the Container. |
| `bind($abs, $conc)` | Registers a factory for a class. |
| `singleton($abs, $conc)` | Registers a singleton for a class. |
| `instance($abs, $inst)` | Binds an existing instance. |
| `make($abs)` | Resolves the instance (with auto-wiring). |
| `get($id)` | Resolves the instance (PSR-11, no auto-wire). |
| `has($abs)` | Checks if a binding or instance exists. |
