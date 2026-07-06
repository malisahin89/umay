# File Report: core/Logger.php

## Purpose
File-based application logging.

## Overview
Records application events and errors to daily log files in `storage/logs/`. It includes protections against log injection and integrates with the `DebugBar` for real-time viewing.

## File Location
`core/Logger.php`

## Namespace
`Core`

## Classes
- `class Logger`

## Properties
- `string $logPath`: Path to the logs directory (`storage/logs`).

## Methods
- `error(string $message, array $context = []): void`: Logs an error message.
- `warning(string $message, array $context = []): void`: Logs a warning message.
- `info(string $message, array $context = []): void`: Logs an informational message.
- `log(string $level, string $message, array $context = []): void`: The internal method that formats the log entry (including timestamp, IP, and User-Agent) and appends it to the daily file using `FILE_APPEND | LOCK_EX`.

## Internal Workflow
1. Sanitize message and headers (CR/LF removal) to prevent log injection.
2. Format the entry: `[timestamp] LEVEL: message | IP: ... | Context: ... | User-Agent: ...`.
3. Write to `storage/logs/YYYY-MM-DD.log`.
4. If `DebugBar` is enabled, add the log to the profiler.

## Dependencies
- `Core\DebugBar` (Optional profiling)

## Source References
- `core/Logger.php:1-73`
