<?php

declare(strict_types=1);

namespace Core\Middleware;

use Core\Auth;
use Core\Auth\PersonalAccessToken;
use Core\Container;
use Core\Contracts\Authenticatable;
use Core\Contracts\MiddlewareInterface;
use Core\Request;

/**
 * ApiAuth — stateless Bearer-token authentication for API routes.
 * ApiAuth — API route'ları için stateless Bearer-token kimlik doğrulama.
 *
 * Resolved from the middleware name 'api-auth' (→ Core\Middleware\ApiAuth).
 * 'api-auth' middleware adından çözülür (→ Core\Middleware\ApiAuth).
 *
 *   Route::get('/me', 'Api\\UserController@me')->middleware('api-auth');
 *   Route::post('/posts', 'Api\\PostController@store')->middleware('api-auth:posts.write');
 *
 * Reads "Authorization: Bearer {id}|{token}", verifies it against
 * personal_access_tokens, optionally checks an ability, then marks the owning
 * user authenticated for this request (Auth::setUser) so auth()/Auth::user() work.
 *
 * "Authorization: Bearer {id}|{token}" okur, personal_access_tokens'a karşı doğrular,
 * opsiyonel bir ability kontrol eder, sonra sahibi kullanıcıyı bu istek için
 * kimliği doğrulanmış işaretler (Auth::setUser) — böylece auth()/Auth::user() çalışır.
 */
class ApiAuth implements MiddlewareInterface
{
    /** Required ability (scope), e.g. 'posts.write'. Empty = any valid token. */
    private string $ability;

    public function __construct(string $ability = '')
    {
        $this->ability = $ability;
    }

    public function handle(Request $request, \Closure $next): mixed
    {
        $bearer = $request->bearerToken();
        if ($bearer === null || $bearer === '') {
            abort(401, 'Unauthenticated. // Kimlik doğrulanamadı.');
        }

        $token = PersonalAccessToken::findToken($bearer);
        if ($token === null) {
            abort(401, 'Invalid API token. // Geçersiz API token.');
        }

        // Ability (scope) check — the param after the colon, e.g. api-auth:posts.write
        // Ability (scope) kontrolü — iki nokta sonrası param, örn. api-auth:posts.write
        if ($this->ability !== '' && ! $token->can($this->ability)) {
            abort(403, 'This token lacks the required ability. // Token gerekli yetkiye sahip değil.');
        }

        $user = $token->tokenable;
        if ($user === null) {
            abort(401, 'Token owner not found. // Token sahibi bulunamadı.');
        }

        // Record usage and bind the token to the user (for $user->tokenCan(...)).
        // Kullanımı kaydet ve token'ı kullanıcıya bağla ($user->tokenCan(...) için).
        $token->forceFill(['last_used_at' => date('Y-m-d H:i:s')])->save();
        if (method_exists($user, 'withAccessToken')) {
            $user->withAccessToken($token);
        }

        // Mark authenticated for THIS request only (stateless — no session write).
        // YALNIZCA bu istek için kimliği doğrulanmış işaretle (stateless — session yazılmaz).
        if ($user instanceof Authenticatable) {
            $auth = Container::getInstance()->make(Auth::class);
            if ($auth instanceof Auth) {
                $auth->setUser($user);
            }
        }

        return $next($request);
    }
}
