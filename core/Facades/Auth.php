<?php

declare(strict_types=1);

namespace Core\Facades;

use Core\Support\Facade;

/**
 * Auth Facade — static proxy for Core\Auth.
 * Auth Facade — Core\Auth için statik proxy.
 *
 * Usage / Kullanım:
 *   Auth::check();
 *   Auth::user();
 *   Auth::id();
 *   Auth::guest();
 *   Auth::login($user);
 *   Auth::logout();
 *   Auth::attempt(['email' => ..., 'password' => ...]);
 *
 * @method static ?\Core\Contracts\Authenticatable user()
 * @method static int|string|null id()
 * @method static bool check()
 * @method static bool guest()
 * @method static \Core\Contracts\UserProvider provider()
 * @method static void setProvider(\Core\Contracts\UserProvider $provider)
 * @method static void setUser(\Core\Contracts\Authenticatable $user)
 * @method static void login(\Core\Contracts\Authenticatable $user, bool $remember = false)
 * @method static void logout()
 * @method static bool attempt(array $credentials)
 * @method static void clearCache()
 *
 * @see \Core\Auth
 */
class Auth extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Core\Auth::class;
    }
}
