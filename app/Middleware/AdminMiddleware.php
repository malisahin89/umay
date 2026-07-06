<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Models\User;
use Core\Contracts\MiddlewareInterface;
use Core\Facades\Auth;
use Core\Request;

/**
 * Restricts a route to admin users.
 * Bir route'u yalnızca admin kullanıcılara kısıtlar.
 *
 * Assumes the 'auth' middleware ran first (identity guaranteed). Stack them:
 * ->middleware(['auth', 'admin']).
 *
 * 'auth' middleware'inin önce çalıştığını varsayar (kimlik garanti). Birlikte
 * kullanın: ->middleware(['auth', 'admin']).
 */
class AdminMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next): mixed
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user === null || ! $user->isAdmin()) {
            flash('error', 'Bu sayfaya erişim yetkiniz yok.');
            redirect('dashboard');

            return null;
        }

        return $next($request);
    }
}
