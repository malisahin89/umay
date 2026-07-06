<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Models\User;
use Core\Contracts\MiddlewareInterface;
use Core\Facades\Auth;
use Core\Request;
use Core\Response;

/**
 * Restricts a route to users holding a specific permission.
 * Bir route'u belirli bir izne sahip kullanıcılara kısıtlar.
 *
 * Usage / Kullanım:
 *   Route::get('/posts/create', ...)->middleware('permission:posts.create');
 *
 * Admins pass every check (User::hasPermission short-circuits on role === 'admin').
 * Adminler her kontrolü geçer (User::hasPermission, role === 'admin' ise kısa devre yapar).
 */
class PermissionMiddleware implements MiddlewareInterface
{
    public function __construct(private ?string $permission = null) {}

    public function handle(Request $request, \Closure $next): mixed
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user === null) {
            flash('error', 'Lütfen giriş yapın.');
            redirect('login.show');

            return null;
        }

        if ($this->permission !== null && ! $user->hasPermission($this->permission)) {
            Response::forbidden('Bu işlem için yetkiniz bulunmamaktadır.');

            return null;
        }

        return $next($request);
    }
}
