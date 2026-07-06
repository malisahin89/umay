# Authorization

> [!NOTE]
> The starting skeleton **does not include a ready-made RBAC (role/permission) system**. This is because every application's authorization needs are different — Umay does not impose it, you add as much as you need. Below is a step-by-step guide on how to add authorization to your own project.

For identity verification (who the user is), see the [Authentication](security/authentication.md) page. This page covers **what** a logged-in user can do (permission).

## 1) Add a role field to the model

Add a `role` column to the `users` table with a migration and put a helper method in the `User` model:

```php
// app/Models/User.php
public function isAdmin(): bool
{
    return $this->role === 'admin';
}
```

> To protect sensitive fields like `role` from mass assignment, keep them out of `$fillable`; assign them explicitly as `$user->role = 'admin'`.

## 2) Create your own authorization middleware

```bash
php umay make:middleware Admin
```

```php
// app/Middleware/AdminMiddleware.php
namespace App\Middleware;

use Core\Contracts\MiddlewareInterface;
use Core\Request;
use Core\Response;

class AdminMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next): mixed
    {
        $user = auth();

        if (! $user || ! $user->isAdmin()) {
            Response::forbidden('You do not have permission to access this page.');
            return null;
        }

        return $next($request);
    }
}
```

The Router automatically resolves the name `admin` to `App\Middleware\AdminMiddleware` (see `config/middleware.php` $\to$ `namespaces`). Now you can use it in routes:

```php
Route::prefix('/admin')->middleware('admin')->group(function () {
    Route::get('/settings', 'AdminSettingsController@index');
});
```

An unauthorized user receives a 403 (Forbidden) response.

## 3) Authorization check inside code

```php
$user = auth();

if (! $user || ! $user->isAdmin()) {
    flash('error', 'You do not have permission for this operation!');
    redirect('home');
    return;
}

// Perform operation...
```

## Permission-based authorization

If you want fine-grained permissions (e.g., `posts.delete`), you can set up a `permissions` / `role_permissions` schema and add a `hasPermission()` method to the `User` model, then write a `permission` middleware similar to the one above. Thanks to Eloquent ORM's relation support, this structure can be set up in minutes.

> Tip: If you want to see a working example of this full RBAC setup, check the `App\Models\Permission`, `App\Middleware\PermissionMiddleware` and related migrations in the `demo-app` branch.

(End of file - total 80 lines)
