# File Report: core/DebugBar.php

## Purpose
Facade for the Application Profiler.

## Overview
Provides a static API to collect diagnostic information (queries, logs, views, etc.) during a request. All calls are delegated to the `Core\Profiler\Profiler` class.

## File Location
`core/DebugBar.php`

## Namespace
`Core`

## Imports
- `Core\Profiler\Profiler`

## Classes
- `class DebugBar`

## Methods
- `init(): void`: Initializes the profiler.
- `isEnabled(): bool`: Checks if the profiler is active.
- `startMeasure(string $name, ?float $start = null): void`: Starts a timer for a specific operation.
- `stopMeasure(string $name): void`: Stops a timer.
- `addQuery(array $q): void`: Records a database query.
- `addLog(string $level, string $message, array $context = []): void`: Records a log entry.
- `addView(string $template, array $data = []): void`: Records a rendered view.
- `addEvent(string $eventClass, mixed $payload = null): void`: Records a dispatched event.
- `addCacheOp(string $type, string $key, bool $hit = false): void`: Records a cache operation.
- `addMail(array $mail): void`: Records a sent email.
- `setRoute(array $info): void`: Records the current route.
- `addException(\Throwable $e): void`: Records a caught exception.
- `addMiddlewareTiming(string $name, float $ms): void`: Records middleware execution time.
- `render(): string`: Returns the HTML for the debug toolbar.
- `findCaller(): array`: Analyzes the backtrace to find the application code that triggered an operation.

## Dependencies
- `Core\Profiler\Profiler` (Delegates to)

## Source References
- `core/DebugBar.php:1-104`
