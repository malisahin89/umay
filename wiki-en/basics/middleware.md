# Middleware

Middleware are layers that intercept an HTTP request before it reaches the controller or intercept the response before it is returned to the user.

## How Middleware Works

Umay uses the "Onion" architecture. A request passes through each middleware layer, reaches the innermost controller, and then passes back through the same layers as a response.

## Built-in Middleware

Some important middleware provided with the framework:

- **`VerifyCsrfToken`**: Checks the CSRF token during form submissions.
- **`ApiAuth`**: Performs API authentication via Bearer Token.
- **`SecurityHeaders`**: Adds security headers such as `X-Frame-Options`, `X-Content-Type-Options`.
- **`Cors`**: Manages Cross-Origin Resource Sharing settings.
- **`RememberMe`**: Checks "Remember Me" cookies.

## Assigning Middleware

### Global Middleware
Middleware added to the `global` array in `config/middleware.php` run on every request.

### Route-based Middleware
You can assign middleware to specific routes only:

```php
Route::get('/profile', 'UserController@profile')->middleware('auth');
```

### Grouping
```php
Route::prefix('/admin')->middleware('admin')->group(function () {
    Route::get('/dashboard', 'AdminController@index');
});
```
