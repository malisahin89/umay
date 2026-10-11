# Controller Classes

Controllers are classes that process HTTP requests and coordinate the business logic between Models and Views. They are located in the `app/Controllers/` directory.

## Creating a Basic Controller

You can create a new Controller in seconds using the Umay CLI:

```bash
php umay make:controller PostController
```

The resulting Controller file looks like this:

```php
<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Request;
use Core\View;

class PostController
{
    public function index(Request $request)
    {
        return 'Post list';
    }
}
```

## Dependency Injection

Umay Framework automatically injects parameters into your Controller methods via the Service Container (Auto-wiring).

For example, to access the `Request` object, simply add it to the method signature:

```php
public function store(Request $request, string $id)
{
    $name = $request->post('name');
    
    // Operations...
}
```

## RESTful Resource Controller

If you want to standardize CRUD (Create, Read, Update, Delete) operations, you can create a Resource Controller:

```bash
php umay make:controller ProductController --resource
```

This command creates a class with predefined `index`, `create`, `store`, `show`, `edit`, `update`, and `destroy` methods. You can then bind it with a single line in `routes/web.php`:

```php
use Core\Route;

Route::resource('products', 'ProductController');
```
