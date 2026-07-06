# Service Container

At the heart of the Umay Framework is the Service Container, an incredibly powerful tool that manages your application's classes and dependencies.

Being PSR-11 compliant, the Container allows you to define how classes should be instantiated and access these classes from anywhere in your application.

## Bind Process

To add a service to the Container, you use the `bind` or `singleton` methods. When using a singleton, the class is produced only once throughout the application lifecycle.

Typically, service binding operations are performed in classes within `app/Providers/`.

```php
use Core\Container;

// Produces a new instance on every call
Container::getInstance()->bind('logger', function() {
    return new \App\Services\LoggerService();
});

// Produces a single instance (Singleton) throughout the entire application
Container::getInstance()->singleton('db', function() {
    return new \Core\Database();
});
```

## Resolve Process

Calling a bound service is very easy.

```php
// Retrieve via Container
$logger = Container::getInstance()->get('logger');
$logger->info('Operation successful');

// Using helper function (More practical)
$db = app('db');
```

## Auto-wiring (Automatic Dependency Resolution)

Umay Framework's Container architecture uses the Reflection API to automatically resolve (inject) the dependencies required in the constructors of your Controllers or services.

```php
namespace App\Controllers;

use Core\Request;

class UserController 
{
    // The Request class is automatically injected by the Container!
    public function store(Request $request)
    {
        $name = $request->post('name');
    }
}
```

This capability makes writing tests and using mock objects extremely easy.
