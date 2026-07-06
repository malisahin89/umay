<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Models\User;
use Core\Facades\Auth;
use Core\Response;

/**
 * Authenticated API endpoints — reached only through the 'api-auth' middleware,
 * which resolves the Bearer token and sets Auth::user() for this request.
 * Kimlik doğrulamalı API uç noktaları — yalnızca 'api-auth' middleware üzerinden
 * erişilir; o middleware Bearer token'ı çözüp bu istek için Auth::user()'ı set eder.
 */
class UserController
{
    public function me(): void
    {
        /** @var User $user */
        $user = Auth::user();

        Response::json([
            'id' => $user->id,
            'name' => $user->name,
            'surname' => $user->surname,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
            'full_name' => $user->full_name,
        ]);
    }
}
