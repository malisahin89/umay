# Routing

Umay Framework has an extremely fast and readable routing infrastructure. All your routes are defined in the `routes/` folder.

## Basic Routing

The simplest Umay routes accept a URI and a Closure (anonymous function). Routes are defined in `routes/web.php`.

```php
use Core\Route;

Route::get('/', function () {
    return 'Hello World!';
});

Route::post('/contact', function () {
    return 'Message received!';
});
```

The following HTTP methods are supported:
`Route::get()`, `Route::post()`, `Route::put()`, `Route::patch()`, `Route::delete()`, `Route::options()`.

## Routing to Controller

The best method is to bind your routes to Controller classes instead of Closures:

```php
// Calls the index method in HomeController
Route::get('/about', 'HomeController@index');

// Calls a controller in a sub-namespace → App\Controllers\Admin\ReportController
Route::get('/panel/report', 'Admin\\ReportController@index');
```

## Route Parameters

Curly braces `{}` are used to retrieve dynamic values (ID, slug, etc.) via the URL.

```php
Route::get('/user/{id}', function (Core\Request $request, string $id) {
    return 'User ID: ' . $id;
});

Route::get('/post/{slug}', 'PostController@show');
```

## Named Routes

Giving names to routes allows you to generate URLs dynamically throughout your application (especially in Views or Redirect operations).

```php
Route::get('/myprofile', 'ProfileController@index')->name('profile.index');
```

To go to this route elsewhere, you can use the `route()` helper function:

```php
$url = route('profile.index');
// Output: /myprofile

// Parameterized route generation
$url = route('user.show', ['id' => 5]);
```

## Route Groups and Middleware

You can group routes that share the same properties (Prefix, Middleware, etc.):

```php
Route::prefix('/admin')->middleware('throttle:60,60')->group(function () {
    
    Route::get('/', 'AdminController@index')->name('admin.dashboard');
    Route::get('/users', 'AdminController@users')->name('admin.users');
    
});
```
In this example, `/admin` and `/admin/users` routes are created and both pass through the `throttle` middleware. The only route-level middleware provided in the starting skeleton is `throttle`; you can create your own middleware with `php umay make:middleware Auth` and use it as `->middleware('auth')`.

## Special Route Definitions

### View Routes
If a route will only return a template (view), you can use `Route::view`:

```php
Route::view('/about', 'pages.about', ['title' => 'About Us']);
```

### Resource Routes
A single line is enough to create a Controller with a RESTful structure:

```php
Route::resource('users', 'UserController');
```
This line automatically creates `index`, `create`, `store`, `show`, `edit`, `update`, and `destroy` routes.
