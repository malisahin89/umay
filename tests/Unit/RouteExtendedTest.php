<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Route;
use Tests\TestCase;

/**
 * Route sistemi genişletilmiş testler.
 *
 * Route tanımlamaları (get, post, put, patch, delete, match, any),
 * prefix/group, middleware atama, named route ve URL üretimi,
 * view/redirect route, regex derleme ve parametre çıkarımı test edilir.
 */
class RouteExtendedTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Route'ların statik state'ini temizle (Reflection ile)
        $ref = new \ReflectionClass(Route::class);

        $routes = $ref->getProperty('routes');
        $routes->setAccessible(true);
        $routes->setValue(null, []);

        $namedRoutes = $ref->getProperty('namedRoutes');
        $namedRoutes->setAccessible(true);
        $namedRoutes->setValue(null, []);

        $prefixStack = $ref->getProperty('prefixStack');
        $prefixStack->setAccessible(true);
        $prefixStack->setValue(null, []);

        $middlewareStack = $ref->getProperty('middlewareStack');
        $middlewareStack->setAccessible(true);
        $middlewareStack->setValue(null, []);
    }

    // ── HTTP method routing ─────────────────────────────────────────────────

    public function test_get_route_registered(): void
    {
        Route::get('/test-get', 'TestController@index');

        $routes = Route::getRoutes();
        $this->assertArrayHasKey('GET', $routes);
        $this->assertArrayHasKey('/test-get', $routes['GET']);
    }

    public function test_post_route_registered(): void
    {
        Route::post('/test-post', 'TestController@store');

        $routes = Route::getRoutes();
        $this->assertArrayHasKey('POST', $routes);
        $this->assertArrayHasKey('/test-post', $routes['POST']);
    }

    public function test_put_route_registered(): void
    {
        Route::put('/test-put', 'TestController@update');

        $routes = Route::getRoutes();
        $this->assertArrayHasKey('PUT', $routes);
        $this->assertArrayHasKey('/test-put', $routes['PUT']);
    }

    public function test_patch_route_registered(): void
    {
        Route::patch('/test-patch', 'TestController@patch');

        $routes = Route::getRoutes();
        $this->assertArrayHasKey('PATCH', $routes);
    }

    public function test_delete_route_registered(): void
    {
        Route::delete('/test-delete', 'TestController@destroy');

        $routes = Route::getRoutes();
        $this->assertArrayHasKey('DELETE', $routes);
    }

    // ── match() birden fazla method ─────────────────────────────────────────

    public function test_match_registers_multiple_methods(): void
    {
        Route::match(['GET', 'POST'], '/search', 'SearchController@index');

        $routes = Route::getRoutes();
        $this->assertArrayHasKey('/search', $routes['GET']);
        $this->assertArrayHasKey('/search', $routes['POST']);
    }

    // ── any() tüm methodlar ─────────────────────────────────────────────────

    public function test_any_registers_all_methods(): void
    {
        Route::any('/webhook', 'WebhookController@handle');

        $routes = Route::getRoutes();
        $this->assertArrayHasKey('/webhook', $routes['GET']);
        $this->assertArrayHasKey('/webhook', $routes['POST']);
        $this->assertArrayHasKey('/webhook', $routes['PUT']);
        $this->assertArrayHasKey('/webhook', $routes['PATCH']);
        $this->assertArrayHasKey('/webhook', $routes['DELETE']);
    }

    // ── match()/any() + fluent middleware/name TÜM metotlara uygulanmalı (H-1) ──
    // ── match()/any() + fluent middleware/name must apply to ALL methods (H-1) ──

    public function test_match_middleware_applies_to_all_methods(): void
    {
        Route::match(['GET', 'POST'], '/secure-multi', 'SecureController@handle')->middleware('auth');

        $routes = Route::getRoutes();
        $this->assertContains('auth', $routes['GET']['/secure-multi']['middleware']);
        $this->assertContains('auth', $routes['POST']['/secure-multi']['middleware']);
    }

    public function test_any_middleware_applies_to_all_methods(): void
    {
        Route::any('/secure-any', 'SecureController@handle')->middleware(['auth', 'throttle:5,60']);

        $routes = Route::getRoutes();
        foreach (['GET', 'POST', 'PUT', 'PATCH', 'DELETE'] as $method) {
            $this->assertContains('auth', $routes[$method]['/secure-any']['middleware']);
            $this->assertContains('throttle:5,60', $routes[$method]['/secure-any']['middleware']);
        }
    }

    public function test_match_name_applies_to_all_methods(): void
    {
        Route::match(['GET', 'POST'], '/named-multi', 'SearchController@index')->name('named.multi');

        $routes = Route::getRoutes();
        $this->assertSame('named.multi', $routes['GET']['/named-multi']['name']);
        $this->assertSame('named.multi', $routes['POST']['/named-multi']['name']);
        $this->assertSame('/named-multi', Route::url('named.multi'));
    }

    // ── Named routes ────────────────────────────────────────────────────────

    public function test_named_route_registered(): void
    {
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

        $url = Route::url('dashboard');
        $this->assertSame('/dashboard', $url);
    }

    public function test_named_route_with_params(): void
    {
        Route::get('/users/{id}', 'UserController@show')->name('user.show');

        $url = Route::url('user.show', ['id' => 42]);
        $this->assertSame('/users/42', $url);
    }

    public function test_named_route_with_query_params(): void
    {
        Route::get('/search', 'SearchController@index')->name('search');

        $url = Route::url('search', ['q' => 'umay', 'page' => 2]);
        $this->assertSame('/search?q=umay&page=2', $url);
    }

    public function test_unknown_named_route_throws(): void
    {
        // Sessiz '#' yerine net hata — template'teki yazım hatası görünür olsun.
        // Fail loudly instead of a silent '#' — typos in templates must surface.
        $this->expectException(\InvalidArgumentException::class);
        Route::url('nonexistent.route');
    }

    public function test_has_returns_true_for_registered_name(): void
    {
        Route::get('/profile', 'ProfileController@show')->name('profile');

        $this->assertTrue(Route::has('profile'));
        $this->assertFalse(Route::has('nonexistent.route'));
    }

    // ── Prefix/Group ────────────────────────────────────────────────────────

    public function test_prefix_group_adds_prefix(): void
    {
        Route::prefix('/admin')->group(function () {
            Route::get('/users', 'AdminController@users')->name('admin.users');
        });

        $routes = Route::getRoutes();
        $this->assertArrayHasKey('/admin/users', $routes['GET']);
    }

    public function test_nested_prefix_groups(): void
    {
        Route::prefix('/api')->group(function () {
            Route::prefix('/v1')->group(function () {
                Route::get('/posts', 'Api\PostController@index')->name('api.v1.posts');
            });
        });

        $routes = Route::getRoutes();
        $this->assertArrayHasKey('/api/v1/posts', $routes['GET']);
    }

    // ── Middleware atama ─────────────────────────────────────────────────────

    public function test_route_middleware_assigned(): void
    {
        Route::get('/secure', 'SecureController@index')->middleware('auth');

        $routes = Route::getRoutes();
        $this->assertContains('auth', $routes['GET']['/secure']['middleware']);
    }

    public function test_multiple_middlewares_assigned(): void
    {
        Route::get('/admin', 'AdminController@index')->middleware(['auth', 'admin']);

        $routes = Route::getRoutes();
        $middlewares = $routes['GET']['/admin']['middleware'];
        $this->assertContains('auth', $middlewares);
        $this->assertContains('admin', $middlewares);
    }

    public function test_group_middleware_inherited(): void
    {
        Route::prefix('/admin')->middleware('auth')->group(function () {
            Route::get('/dashboard', 'AdminController@dashboard');
        });

        $routes = Route::getRoutes();
        $this->assertContains('auth', $routes['GET']['/admin/dashboard']['middleware']);
    }

    // ── Parametreli route regex derleme ──────────────────────────────────────

    public function test_parametered_route_has_compiled_regex(): void
    {
        Route::get('/users/{id}/posts/{postId}', 'UserPostController@show');

        $routes = Route::getRoutes();
        $route = $routes['GET']['/users/{id}/posts/{postId}'];

        $this->assertNotNull($route['_compiled']);
        $this->assertArrayHasKey('regex', $route['_compiled']);
        $this->assertArrayHasKey('params', $route['_compiled']);
        $this->assertEquals(['id', 'postId'], $route['_compiled']['params']);
    }

    // ── Route grup yönetimi ─────────────────────────────────────────────────

    public function test_set_and_get_group(): void
    {
        Route::setGroup('api');
        $this->assertSame('api', Route::getGroup());

        Route::setGroup('web');
        $this->assertSame('web', Route::getGroup());
    }

    // ── removeRoute ─────────────────────────────────────────────────────────

    public function test_remove_route(): void
    {
        Route::get('/temp', 'TempController@index')->name('temp');

        Route::removeRoute('GET', '/temp');

        $routes = Route::getRoutes();
        $this->assertArrayNotHasKey('/temp', $routes['GET'] ?? []);
    }

    // ── Closure route ───────────────────────────────────────────────────────

    public function test_closure_route_registered(): void
    {
        Route::get('/hello', function () {
            return 'Hello World';
        });

        $routes = Route::getRoutes();
        $this->assertArrayHasKey('/hello', $routes['GET']);
        $this->assertInstanceOf(\Closure::class, $routes['GET']['/hello']['action']);
    }

    // ── Root URL '/' ────────────────────────────────────────────────────────

    public function test_root_route_registered_correctly(): void
    {
        Route::get('/', 'HomeController@index')->name('home');

        $routes = Route::getRoutes();
        $this->assertArrayHasKey('/', $routes['GET']);
        $this->assertSame('/', Route::url('home'));
    }

    // ── Opsiyonel parametre URL üretimi ──────────────────────────────────────
    // ── Optional parameter URL generation ────────────────────────────────────

    public function test_url_optional_param_provided(): void
    {
        Route::get('/posts/{id?}', 'PostController@show')->name('posts.show');

        $this->assertSame('/posts/9', Route::url('posts.show', ['id' => 9]));
    }

    public function test_url_optional_param_omitted_strips_placeholder_and_slash(): void
    {
        Route::get('/posts/{id?}', 'PostController@show')->name('posts.show');

        $this->assertSame('/posts', Route::url('posts.show'));
    }

    public function test_url_mixed_required_and_optional_param(): void
    {
        Route::get('/a/{id}/{slug?}', 'AController@show')->name('a.show');

        $this->assertSame('/a/1/hello', Route::url('a.show', ['id' => 1, 'slug' => 'hello']));
        $this->assertSame('/a/1', Route::url('a.show', ['id' => 1]));
    }

    public function test_url_optional_route_extra_param_becomes_query(): void
    {
        Route::get('/posts/{id?}', 'PostController@show')->name('posts.show');

        $this->assertSame('/posts/5?ref=home', Route::url('posts.show', ['id' => 5, 'ref' => 'home']));
    }

    // ── Regex derleme: statik segmentteki özel karakterler escape edilir ──────
    // ── Regex compilation: special chars in static segments are escaped ───────

    public function test_compiled_regex_escapes_static_dot(): void
    {
        Route::get('/v1.0/{id}', 'VController@show');

        $compiled = Route::getRoutes()['GET']['/v1.0/{id}']['_compiled'];

        // Nokta literal eşleşir // Dot matches literally
        $this->assertSame(1, preg_match($compiled['regex'], '/v1.0/5'));
        // Nokta wildcard DEĞİLDİR // Dot is NOT a wildcard
        $this->assertSame(0, preg_match($compiled['regex'], '/v1x0/5'));
    }

    public function test_compiled_regex_optional_matches_with_and_without_param(): void
    {
        Route::get('/posts/{id?}', 'PostController@show');

        $compiled = Route::getRoutes()['GET']['/posts/{id?}']['_compiled'];

        $this->assertSame(1, preg_match($compiled['regex'], '/posts/9'));
        $this->assertSame(1, preg_match($compiled['regex'], '/posts'));
        $this->assertSame(['id'], $compiled['params']);
    }

    // ── HEAD istekleri GET route'larıyla karşılanır (RFC 9110) ───────────────
    // ── HEAD requests are served by GET routes (RFC 9110) ────────────────────

    public function test_head_request_dispatches_get_route(): void
    {
        // Middleware'siz bir grup kullan — pipeline boş kalsın, test izole çalışsın.
        // Use a group with no middleware — empty pipeline, isolated test.
        Route::setGroup('testing-no-mw');
        Route::get('/head-probe', fn () => 'head-ok');
        Route::setGroup('web');

        $_SERVER['REQUEST_METHOD'] = 'HEAD';
        $_SERVER['REQUEST_URI'] = '/head-probe';

        ob_start();
        try {
            Route::dispatch();
        } finally {
            $output = ob_get_clean();
        }

        // Düzeltme öncesi: routes['HEAD'] boş olduğundan abort(404) fırlardı.
        // Before the fix: routes['HEAD'] was empty, so abort(404) was thrown.
        $this->assertSame('head-ok', $output);
    }

    // ── OPTIONS fallback — preflight, eşleşen verb'lerin pipeline'ına düşmeli ──
    // ── OPTIONS fallback — preflight must fall through to matching verbs ───────

    public function test_options_falls_back_to_matching_verb_and_responds_204(): void
    {
        // Middleware'siz bir grup — boş pipeline, 204 + Allow handler'ı çalışır.
        // A group with no middleware — empty pipeline, the 204 + Allow handler runs.
        Route::setGroup('testing-no-mw');
        Route::post('/preflight-probe', fn () => 'handler-must-not-run');
        Route::setGroup('web');

        $_SERVER['REQUEST_METHOD'] = 'OPTIONS';
        $_SERVER['REQUEST_URI'] = '/preflight-probe';

        ob_start();
        try {
            Route::dispatch();
        } finally {
            $output = ob_get_clean();
        }

        // Düzeltme öncesi: routes['OPTIONS'] boş olduğundan abort(404) fırlıyordu ve
        // Cors middleware'inin preflight (204) dalı hiç çalışamıyordu.
        // Before the fix: routes['OPTIONS'] was empty so abort(404) was thrown and
        // the Cors middleware's preflight (204) branch could never run.
        $this->assertSame('', $output);
        $this->assertSame(204, http_response_code());

        http_response_code(200);
    }

    public function test_options_on_unknown_path_still_404s(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'OPTIONS';
        $_SERVER['REQUEST_URI'] = '/no-such-path-anywhere';

        $this->expectException(\Core\Exceptions\HttpException::class);
        Route::dispatch();
    }

    // ── group() yalnızca kendi push'ladığı prefix'i pop'lamalı ────────────────
    // ── group() must only pop the prefix its own handle pushed ────────────────

    public function test_group_on_route_handle_does_not_pop_outer_prefix(): void
    {
        Route::prefix('/admin')->group(function () {
            // Yanlış kullanım: route handle'ında ->group() — dış prefix'i BOZMAMALI.
            // Misuse: ->group() on a route handle — must NOT corrupt the outer prefix.
            Route::get('/x', 'AController@x')->group(function () {});
            Route::get('/y', 'AController@y');
        });

        $routes = Route::getRoutes();
        $this->assertArrayHasKey('/admin/x', $routes['GET']);
        $this->assertArrayHasKey('/admin/y', $routes['GET']);
    }
}
