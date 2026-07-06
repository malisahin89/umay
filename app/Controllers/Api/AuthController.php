<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Models\User;
use Core\Request;
use Core\Response;

/**
 * Stateless API auth — issues Bearer tokens via the core HasApiTokens trait.
 * Stateless API auth — çekirdek HasApiTokens trait'i ile Bearer token üretir.
 *
 * No session/CSRF here (API routes are stateless). The returned plainTextToken
 * is sent back once and used as: Authorization: Bearer <token>.
 * Burada session/CSRF yok (API route'ları stateless). Dönen plainTextToken bir kez
 * verilir ve şöyle kullanılır: Authorization: Bearer <token>.
 */
class AuthController
{
    public function login(Request $request): void
    {
        $email = trim(is_string($e = $request->post('email')) ? $e : '');
        $password = is_string($p = $request->post('password')) ? $p : '';

        /** @var User|null $user */
        $user = User::query()->where('email', $email)->first();

        if ($user === null || ! password_verify($password, $user->getAuthPassword())) {
            Response::json(['error' => 'invalid_credentials', 'message' => 'E-posta veya şifre hatalı.'], 401);

            return;
        }

        // Abilities scoped to the user's role: admins get '*', members read-only.
        // Yetkiler kullanıcı rolüne göre: adminler '*', üyeler salt-okunur.
        $abilities = $user->isAdmin() ? ['*'] : ['posts.view', 'users.view'];

        ['plainTextToken' => $plain] = $user->createToken('api-login', $abilities);

        Response::json([
            'token' => $plain,
            'abilities' => $abilities,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }
}
