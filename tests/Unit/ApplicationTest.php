<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Application;
use Core\Container;
use Core\Request;
use Core\ServiceProvider;
use Tests\TestCase;

/**
 * Application bootstrap ve ServiceProvider testleri.
 *
 * Singleton pattern, provider kaydı, boot mekanizması,
 * Container erişimi ve captureRequest() test edilir.
 */
class ApplicationTest extends TestCase
{
    // ── Singleton pattern ───────────────────────────────────────────────────

    public function test_get_instance_returns_same_instance(): void
    {
        $app1 = Application::getInstance();
        $app2 = Application::getInstance();

        $this->assertSame($app1, $app2);
    }

    // ── Container erişimi ───────────────────────────────────────────────────

    public function test_container_returns_container_instance(): void
    {
        $app = Application::getInstance();
        $container = $app->container();

        $this->assertInstanceOf(Container::class, $container);
    }

    // ── make() Container'a delegate eder ────────────────────────────────────

    public function test_make_delegates_to_container(): void
    {
        $app = Application::getInstance();
        $app->instance('test_service', (object) ['name' => 'TestService']);

        $service = $app->make('test_service');
        $this->assertSame('TestService', $service->name);
    }

    // ── singleton() Container'a delegate eder ───────────────────────────────

    public function test_singleton_delegates_to_container(): void
    {
        $app = Application::getInstance();
        $app->singleton('counter', fn () => new class
        {
            public int $n = 0;
        });

        $a = $app->make('counter');
        $a->n++;
        $b = $app->make('counter');

        $this->assertSame(1, $b->n); // Aynı instance
    }

    // ── register() ServiceProvider kaydı ────────────────────────────────────

    public function test_register_calls_provider_register(): void
    {
        $app = Application::getInstance();

        // Geçersiz (ServiceProvider olmayan) sınıf adıyla hata fırlatmalı
        $this->expectException(\InvalidArgumentException::class);
        $app->register(\stdClass::class);
    }

    // ── boot() sadece bir kez çalışır ───────────────────────────────────────

    public function test_boot_runs_only_once(): void
    {
        $app = Application::getInstance();

        // İlk ve ikinci boot aynı sonucu vermeli
        $result1 = $app->boot();
        $result2 = $app->boot();

        $this->assertSame($app, $result1);
        $this->assertSame($app, $result2);
    }

    // ── captureRequest() Request'i Container'a bind eder ────────────────────

    public function test_capture_request_binds_to_container(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test';
        $_SERVER['HTTP_HOST'] = 'localhost';

        $app = Application::getInstance();
        $app->captureRequest();

        $this->assertTrue($app->container()->has(Request::class));
        $request = $app->make(Request::class);
        $this->assertInstanceOf(Request::class, $request);
    }
}
