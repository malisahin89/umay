# Request Lifecycle

Understanding how an HTTP request is processed and returned as a response to the user in the Umay Framework is the most important step in grasping the architecture.

## 1. Entry Point: `public/index.php`

All web requests are routed to the `public/index.php` file. This file is responsible for bootstrapping the framework.
Here, system dependencies (Composer `autoload.php`) are loaded, and the `Core\Application` class is instantiated.

```php
require_once __DIR__ . '/../vendor/autoload.php';

$app = new Core\Application(dirname(__DIR__));
```

## 2. Kernel and Middleware Layer

After the `Application` object is initialized, the request is forwarded to the `Core\Http\Kernel` (HTTP Kernel). The Kernel receives the request, passes it through the global middleware, and delivers it to the Route engine.

## 3. Route Resolution (Routing)

The `Core\Route` module checks the routes defined in `routes/web.php` or `routes/api.php`. If the request's URL and HTTP method match:
- Specific Middleware assigned to the route are executed.
- If the route is a Closure function, that function is executed.
- If the route is a Controller method, the corresponding method of the Controller is called (e.g., `UserController@index`).

## 4. Business Logic (Controller)

Your business logic runs inside the Controller. Database operations are performed via Models, validation is carried out, and finally, a response is prepared.

```php
public function index()
{
    $users = User::where('status', 'active')->get();
    
    // Return as View
    View::render('users.index', ['users' => $users]);
}
```

## 5. Sending the Response (Response)

In the final stage of the request lifecycle, the data returned from the Controller or Route (HTML String, JSON, or Redirect object) is sent to the client. After the process is complete, the PHP process ends and memory is cleared.
