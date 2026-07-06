# Facade Pattern

In the Umay Framework, Facades provide a "static" interface (Static Proxy) to classes registered in your application's Service Container.

Thanks to Facades, instead of creating objects with the `new` keyword or using the `app('service')` helper, you can make easily readable and testable static calls.

## Usage Example

For example, under normal circumstances, you can access the Log class using the Container:
```php
app('log')->info('User logged in.');
```

However, using a Facade, you can write this much more elegantly:
```php
use Core\Facades\Log;

Log::info('User logged in.');
Log::error('Database connection error!');
```

## Available Built-in Facades

Umay Framework provides the following Facades out of the box:

- `Core\Facades\Log`: For logging operations (Monolog/Umay Logger runs in the background).
- `Core\Facades\Cache`: For caching operations.
- `Core\Facades\Event`: For event listeners.

> [!TIP]
> Using Facades does not make the code "static". In the background, the actual class instance is retrieved via the Service Container. Therefore, unlike real static methods, Facades are testable (Mockable).
