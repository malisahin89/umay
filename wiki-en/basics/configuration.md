# Configuration

Umay Framework uses the `config/` directory to manage application settings centrally. Settings are managed both via PHP files and environment variables through a `.env` file.

## Configuration Files

The following basic configuration files are available:

- **`app.php`**: Application name, version, timezone, and Facade alias definitions.
- **`auth.php`**: Authentication guards and user provider settings.
- **`cache.php`**: Cache driver and TTL settings.
- **`database.php`**: Database connection information (Eloquent/Capsule).
- **`mail.php`**: Email delivery settings and transport options.
- **`middleware.php`**: Global middleware and API prefix definitions.
- **`profiler.php`**: Debug profiler activation and storage settings.
- **`session.php`**: Session lifetime, cookie settings, and security options.

## The `config()` Helper

The `config()` helper function is used to access any configuration value. Deep access can be provided using dot notation:

```php
// Retrieves the 'timezone' value from app.php
$timezone = config('app.timezone');

// Usage with a default value
$timezone = config('app.timezone', 'UTC');
```

## Environment Variables (.env)

Sensitive data (passwords, API keys) are stored in the `.env` file. Config files use the `env()` function to read these values:

```php
// config/database.php
'host' => env('DB_HOST', '127.0.0.1'),
```
