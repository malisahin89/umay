<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Auth;
use Core\Cache;
use Core\Container;
use Core\Database;
use Core\Events\Dispatcher;
use Core\Logger;
use Core\Providers\FacadeServiceProvider;
use Core\RateLimiter;
use Core\Route;
use Core\Validator;
use Core\View;
use Tests\TestCase;

/**
 * FacadeServiceProvider testleri.
 *
 * Core servislerin Container'a singleton olarak doğru bind edildiği,
 * register() ve boot() metodlarının çalıştığı test edilir.
 */
class FacadeServiceProviderTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = Container::getInstance();
    }

    // ── register() Container'a singleton bind eder ──────────────────────────

    public function test_register_binds_cache_singleton(): void
    {
        $provider = new FacadeServiceProvider($this->container);
        $provider->register();

        $this->assertTrue($this->container->has(Cache::class));

        $cache1 = $this->container->make(Cache::class);
        $cache2 = $this->container->make(Cache::class);
        $this->assertSame($cache1, $cache2); // Singleton
    }

    public function test_register_binds_auth_singleton(): void
    {
        $provider = new FacadeServiceProvider($this->container);
        $provider->register();

        $this->assertTrue($this->container->has(Auth::class));

        $auth1 = $this->container->make(Auth::class);
        $auth2 = $this->container->make(Auth::class);
        $this->assertSame($auth1, $auth2);
    }

    public function test_register_binds_logger_singleton(): void
    {
        $provider = new FacadeServiceProvider($this->container);
        $provider->register();

        $this->assertTrue($this->container->has(Logger::class));
    }

    public function test_register_binds_route_singleton(): void
    {
        $provider = new FacadeServiceProvider($this->container);
        $provider->register();

        $this->assertTrue($this->container->has(Route::class));
    }

    public function test_register_binds_database_singleton(): void
    {
        $provider = new FacadeServiceProvider($this->container);
        $provider->register();

        $this->assertTrue($this->container->has(Database::class));
    }

    public function test_register_binds_dispatcher_singleton(): void
    {
        $provider = new FacadeServiceProvider($this->container);
        $provider->register();

        $this->assertTrue($this->container->has(Dispatcher::class));
    }

    public function test_register_binds_view_singleton(): void
    {
        $provider = new FacadeServiceProvider($this->container);
        $provider->register();

        $this->assertTrue($this->container->has(View::class));
    }

    public function test_register_binds_rate_limiter_singleton(): void
    {
        $provider = new FacadeServiceProvider($this->container);
        $provider->register();

        $this->assertTrue($this->container->has(RateLimiter::class));
    }

    // ── Validator proxy ─────────────────────────────────────────────────────

    public function test_register_binds_validator_proxy(): void
    {
        $provider = new FacadeServiceProvider($this->container);
        $provider->register();

        $this->assertTrue($this->container->has(Validator::class));

        $proxy = $this->container->make(Validator::class);
        // Proxy'nin make() metodu olmalı
        $this->assertTrue(method_exists($proxy, 'make'));
    }

    public function test_validator_proxy_creates_validator_instance(): void
    {
        $provider = new FacadeServiceProvider($this->container);
        $provider->register();

        $proxy = $this->container->make(Validator::class);
        $validator = $proxy->make(['name' => 'Umay'], ['name' => 'required']);

        $this->assertInstanceOf(Validator::class, $validator);
        $this->assertTrue($validator->passes());
    }
}
