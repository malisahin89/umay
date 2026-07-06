# Config Folder Documentation

This folder contains all the configuration files for the Umay framework. These files return arrays of settings used across the application.

## Configuration Files

- `app.php`: Global application settings including name, URL, timezone, and encryption key.
- `cache.php`: File-based cache settings — `path`, `prefix` and `default_ttl`.
- `database.php`: Database connection settings (host, port, user, password, charset, etc.).
- `mail.php`: Driver-based mail config. Defines the active `default` mailer and each mailer's `transport` (a `Core\Contracts\MailTransport` class). Ships only the `log` transport (writes to storage/logs); plug your own transport for real delivery — see `docs/digging-deeper/mail.md`.
- `profiler.php`: Settings for the DebugBar/Profiler, including data collectors and exclusion rules.
- `session.php`: Session management settings, including lifetime, secure cookies, and storage drivers.

## Usage

You can access these configuration values anywhere in your application using the `config()` helper function:

```php
// Get application name
$appName = config('app.name');

// Get database host
$dbHost = config('database.host');
```