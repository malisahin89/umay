<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Container;
use Core\View;
use League\Plates\Engine;
use Tests\TestCase;

/**
 * View testleri.
 *
 * Plates engine başlatma ve singleton davranışı,
 * template fonksiyon kayıtları test edilir.
 */
class ViewTest extends TestCase
{
    private View $view;

    protected function setUp(): void
    {
        parent::setUp();

        $container = Container::getInstance();
        if (! $container->has(View::class)) {
            $container->singleton(View::class, fn () => new View);
        }
        $this->view = $container->make(View::class);
    }

    // ── Engine başlatma ──────────────────────────────────────────────────

    public function test_engine_returns_plates_instance(): void
    {
        $reflection = new \ReflectionMethod(View::class, 'engine');
        $reflection->setAccessible(true);
        $engine = $reflection->invoke($this->view);

        $this->assertInstanceOf(Engine::class, $engine);
    }

    public function test_engine_is_singleton_per_instance(): void
    {
        $reflection = new \ReflectionMethod(View::class, 'engine');
        $reflection->setAccessible(true);

        $engine1 = $reflection->invoke($this->view);
        $engine2 = $reflection->invoke($this->view);

        $this->assertSame($engine1, $engine2);
    }

    // ── Registered functions ─────────────────────────────────────────────

    public function test_engine_has_csrf_function(): void
    {
        $reflection = new \ReflectionMethod(View::class, 'engine');
        $reflection->setAccessible(true);
        $engine = $reflection->invoke($this->view);

        $this->assertTrue($engine->doesFunctionExist('csrf'));
    }

    public function test_engine_has_csrf_token_function(): void
    {
        $reflection = new \ReflectionMethod(View::class, 'engine');
        $reflection->setAccessible(true);
        $engine = $reflection->invoke($this->view);

        $this->assertTrue($engine->doesFunctionExist('csrf_token'));
    }

    public function test_engine_has_escape_function(): void
    {
        $reflection = new \ReflectionMethod(View::class, 'engine');
        $reflection->setAccessible(true);
        $engine = $reflection->invoke($this->view);

        // 'e' fonksiyonu XSS koruması
        $this->assertTrue($engine->doesFunctionExist('e'));
    }

    public function test_engine_has_old_function(): void
    {
        $reflection = new \ReflectionMethod(View::class, 'engine');
        $reflection->setAccessible(true);
        $engine = $reflection->invoke($this->view);

        $this->assertTrue($engine->doesFunctionExist('old'));
    }

    public function test_engine_has_route_function(): void
    {
        $reflection = new \ReflectionMethod(View::class, 'engine');
        $reflection->setAccessible(true);
        $engine = $reflection->invoke($this->view);

        $this->assertTrue($engine->doesFunctionExist('route'));
    }

    public function test_engine_has_flash_function(): void
    {
        $reflection = new \ReflectionMethod(View::class, 'engine');
        $reflection->setAccessible(true);
        $engine = $reflection->invoke($this->view);

        $this->assertTrue($engine->doesFunctionExist('flash'));
    }

    public function test_engine_has_dd_function(): void
    {
        $reflection = new \ReflectionMethod(View::class, 'engine');
        $reflection->setAccessible(true);
        $engine = $reflection->invoke($this->view);

        $this->assertTrue($engine->doesFunctionExist('dd'));
    }

    // ── share() ──────────────────────────────────────────────────────────

    /** share() ile konan veri singleton üzerinde birikir (tekil + toplu imza). */
    public function test_share_accumulates_single_and_bulk(): void
    {
        $view = new View;
        $view->share('siteName', 'Umay');
        $view->share(['langUrls' => ['tr' => '/tr'], 'locale' => 'tr']);

        $property = new \ReflectionProperty(View::class, 'shared');
        $property->setAccessible(true);

        $this->assertSame(
            ['siteName' => 'Umay', 'langUrls' => ['tr' => '/tr'], 'locale' => 'tr'],
            $property->getValue($view)
        );
    }

    /** Aynı anahtar tekrar paylaşılırsa son değer kazanır. */
    public function test_share_overwrites_existing_key(): void
    {
        $view = new View;
        $view->share('siteName', 'Eski');
        $view->share('siteName', 'Yeni');

        $property = new \ReflectionProperty(View::class, 'shared');
        $property->setAccessible(true);

        $this->assertSame(['siteName' => 'Yeni'], $property->getValue($view));
    }
}
