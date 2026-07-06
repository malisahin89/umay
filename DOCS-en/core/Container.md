# File Report: core/Container.php

## Purpose
Dependency Injection (DI) Container.

## Overview
A PSR-11 compliant container that manages class dependencies. It supports singleton bindings, factory bindings, and automatic "auto-wiring" via PHP Reflection.

## File Location
`core/Container.php`

## Namespace
`Core`

## Imports
- `Core\Exceptions\ContainerException`
- `Core\Exceptions\EntryNotFoundException`
- `Psr\Container\ContainerInterface`

## Classes
- `class Container implements ContainerInterface`

## Properties
- `static ?self $instance`: Singleton instance of the container.
- `array $bindings`: Registry of abstract-to-concrete mappings.
- `array $instances`: Cache for singleton instances.
- `array $resolving`: Tracker for circular dependency detection.
- `array $reflectionCache`: Cache for `ReflectionClass` objects.

## Methods
- `getInstance(): static`: Returns the singleton instance.
- `singleton(string $abstract, callable|string $concrete): void`: Binds a class as a singleton.
- `bind(string $abstract, callable|string $concrete): void`: Binds a class as a factory.
- `instance(string $abstract, mixed $instance): void`: Binds a specific object instance.
- `make(string $abstract): mixed`: Resolves and returns an instance of the requested class.
- `get(string $id): mixed`: PSR-11 method to retrieve a registered entry.
- `has(string $abstract): bool`: Checks if an entry is registered.
- `build(string $concrete): mixed`: Internal method that uses Reflection to auto-wire dependencies.

## Internal Workflow (Auto-wiring)
1. Checks for a constructor.
2. For each parameter, it determines the type.
3. If the type is a class/interface, it recursively calls `make()` to resolve it.
4. Supports Union Types (PHP 8.0+), picking the first resolvable type.
5. Falls back to default values if available.

## Dependencies
- `Psr\Container\ContainerInterface` (Implements)

## Source References
- `core/Container.php:1-197`
