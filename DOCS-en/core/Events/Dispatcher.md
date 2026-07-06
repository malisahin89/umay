# File Report: core/Events/Dispatcher.php

## Purpose
Global event bus for the application.

## Overview
Manages the registration and dispatching of events. It allows decoupled components to communicate by triggering events that one or more listeners can respond to.

## File Location
`core/Events/Dispatcher.php`

## Namespace
`Core\Events`

## Classes
- `class Dispatcher`

## Properties
- `static ?self $instance`: Singleton instance of the dispatcher.
- `array $listeners`: Mapping of event classes to their registered listeners.

## Methods
- `getInstance(): self`: Returns the singleton instance.
- `listen(string $eventClass, string|\Closure $listener): void`: Registers a listener for a specific event or a wildcard `*` (all events).
- `subscribe(array $map): void`: Bulk registers multiple event-listener mappings.
- `once(string $eventClass, string|\Closure $listener): void`: Registers a listener that is removed after its first execution.
- `forget(string $eventClass, ?\Closure $specific = null): void`: Removes listeners for a specific event.
- `dispatch(Event $event): Event`: Triggers an event, executing all registered listeners in order.
- `hasListeners(string $eventClass): bool`: Checks if an event has any registered listeners.
- `flush(): void`: Clears all registered listeners.

## Internal Workflow
- **Execution**: When `dispatch()` is called, the dispatcher first executes listeners specific to that event class, followed by wildcard listeners. It respects `stopPropagation()` called on the event object.
- **Listener Resolution**: If a listener is provided as a class name, it is resolved via the `Container` to support dependency injection.

## Dependencies
- `Core\Container` (Uses)
- `Core\DebugBar` (Optional profiling)

## Source References
- `core/Events/Dispatcher.php:1-192`
