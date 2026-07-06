# Logging

## Purpose
Documents the application logger.

## Overview
`Core\Logger` is a file-based logger with daily rotation, instance-based and resolved from the container (via the `Log` facade). Logs are written to `storage/logs/Y-m-d.log`.

## API
- `info(string $message, array $context = [])`
- `warning(string $message, array $context = [])`
- `error(string $message, array $context = [])`

## Log Format
Each line: `[timestamp] LEVEL: message | IP: … | Context: <json> | User-Agent: …`.
- Timestamp `Y-m-d H:i:s`; IP from `REMOTE_ADDR`; user agent from `HTTP_USER_AGENT`.
- Writes with `FILE_APPEND | LOCK_EX`.
- The log directory is created on demand with mode `0700`.

## Security Observations
- **Log-injection defense:** CR/LF are stripped from `message`, `IP`, and `User-Agent` before writing.

## Profiler Integration
- When `DebugBar` is enabled, each entry is also forwarded via `DebugBar::addLog(level, message, context)`.

## Consumers
- `Core\ExceptionHandler` logs CSRF/HTTP warnings and uncaught errors; other components log via the `Log` facade.

## Cross References
- `DOCS/core/Logger.md`, `DOCS/core/Facades/Log.md`, `DOCS/ERROR_HANDLING.md`, `DOCS/PERFORMANCE.md`
- Tests: `DOCS/tests/Unit/LoggerTest.md`

## Source References
- `core/Logger.php:19-73`
- `core/Logger.php:49-54` (injection defense), `core/Logger.php:66-67` (daily file + append)
