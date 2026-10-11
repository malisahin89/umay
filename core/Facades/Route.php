<?php

declare(strict_types=1);

namespace Core\Facades;

use Core\Support\Facade;

/**
 * Route Facade — static proxy for Core\Route.
 * Route Facade — Core\Route için statik proxy.
 *
 * Usage / Kullanım:
 *   Route::get('/users', 'UserController@index');
 *   Route::post('/users', 'UserController@store');
 *   Route::prefix('/admin')->middleware('auth')->group(function() { ... });
 *   Route::resource('posts', 'PostController');
 *   Route::url('users.show', ['id' => 5]);
 *
 * @method static static get(string $uri, \Closure|string $action)
 * @method static static post(string $uri, \Closure|string $action)
 * @method static static put(string $uri, \Closure|string $action)
 * @method static static patch(string $uri, \Closure|string $action)
 * @method static static delete(string $uri, \Closure|string $action)
 * @method static static match(array $methods, string $uri, \Closure|string $action)
 * @method static static any(string $uri, \Closure|string $action)
 * @method static static view(string $uri, string $view, array $data = [])
 * @method static static redirect(string $from, string $to, int $status = 302)
 * @method static \Core\ResourceRegistrar resource(string $name, string $controller, array $options = [])
 * @method static \Core\ResourceRegistrar apiResource(string $name, string $controller, array $options = [])
 * @method static static prefix(string $prefix)
 * @method static string url(string $name, array $params = [])
 * @method static void dispatch()
 * @method static void setGroup(string $group)
 * @method static string getGroup()
 * @method static array getRoutes()
 *
 * @see \Core\Route
 */
class Route extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Core\Route::class;
    }
}
