# File Report: core/Profiler/Profiler.php

## Purpose
Application performance and diagnostic profiler.

## Overview
Collects a wide range of metrics during a request, including database queries, rendered views, events, logs, and execution timing. This data is stored as JSON files in `storage/profiler/` and can be viewed via the Debug Toolbar.

## File Location
`core/Profiler/Profiler.php`

## Namespace
`Core\Profiler`

## Classes
- `class Profiler`

## Properties
- `array $data`: The collected metrics for the current request.
- `ProfilerStorage $storage`: The storage handler for persisting profiles.

## Methods
- `init(): void`: Initializes the profiler.
- `isEnabled(): bool`: Checks if profiling is active.
- `startMeasure(string $name): void`: Starts a timer for a specific block of code.
- `stopMeasure(string $name): void`: Stops a timer and records the elapsed time.
- `addQuery(array $q): void`: Records a database query.
- `addLog(string $level, string $message, array $context = []): void`: Records a log entry.
- `addView(string $template, array $data = []): void`: Records a rendered view.
- `addEvent(string $eventClass, mixed $payload = null): void`: Records a dispatched event.
- `addCacheOp(string $type, string $key, bool $hit = false): void`: Records a cache operation.
- `addMail(array $mail): void`: Records a sent email.
- `setRoute(array $info): void`: Records the matched route.
- `addException(\Throwable $e): void`: Records a caught exception.
- `addMiddlewareTiming(string $name, float $ms): void`: Records middleware execution time.
- `finish(): void`: Persists the collected data to disk.
- `renderToolbar(): string`: Generates the HTML for the debug toolbar.

## Internal Workflow
- **Data Collection**: Various core components call `Profiler` methods throughout the request lifecycle.
- **Persistence**: `finish()` generates a unique token and saves the data to `storage/profiler/{token}.json`.
- **Analysis**: `detectNPlusOne()` analyzes database queries to find common N+1 patterns.

## Dependencies
- `Core\Profiler\ProfilerStorage` (Uses)
- `Core\Profiler\Contracts\DataCollectorInterface` (Uses)

## Source References
- `core/Profiler/Profiler.php:1-1581`
