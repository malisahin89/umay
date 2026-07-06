<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Container;
use Core\Support\Facade;
use Tests\TestCase;

/**
 * Facade sistemi testleri.
 *
 * __callStatic proxy, swap(), clearResolvedInstances(),
 * Container singleton resolution ve hata durumları test edilir.
 */
class FacadeTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = Container::getInstance();
        Facade::clearResolvedInstances();
    }

    protected function tearDown(): void
    {
        Facade::clearResolvedInstances();
        parent::tearDown();
    }

    // ── __callStatic proxy mekanizması ──────────────────────────────────────

    public function test_facade_forwards_static_calls_to_resolved_instance(): void
    {
        // Sahte bir servis bind et
        $fakeService = new class
        {
            public function greet(string $name): string
            {
                return "Merhaba, {$name}!";
            }
        };

        $this->container->instance('fake_service', $fakeService);

        // Sahte Facade
        $facade = new class extends Facade
        {
            protected static function getFacadeAccessor(): string
            {
                return 'fake_service';
            }
        };

        $result = $facade::greet('Umay');
        $this->assertSame('Merhaba, Umay!', $result);
    }

    // ── swap() ile mock değiştirme ──────────────────────────────────────────

    public function test_swap_replaces_resolved_instance(): void
    {
        $original = new class
        {
            public function value(): string
            {
                return 'original';
            }
        };
        $mock = new class
        {
            public function value(): string
            {
                return 'mocked';
            }
        };

        $this->container->instance('swap_test', $original);

        $facade = new class extends Facade
        {
            protected static function getFacadeAccessor(): string
            {
                return 'swap_test';
            }
        };

        $this->assertSame('original', $facade::value());

        $facade::swap($mock);
        $this->assertSame('mocked', $facade::value());
    }

    // ── clearResolvedInstances ──────────────────────────────────────────────

    public function test_clear_resolved_instances_forces_re_resolution(): void
    {
        $counter = new class
        {
            public int $count = 0;

            public function increment(): int
            {
                return ++$this->count;
            }
        };

        $this->container->instance('counter_test', $counter);

        $facade = new class extends Facade
        {
            protected static function getFacadeAccessor(): string
            {
                return 'counter_test';
            }
        };

        $facade::increment();
        $this->assertSame(1, $counter->count);

        // clearResolvedInstances sonrası aynı instance dönmeli (Container singleton)
        Facade::clearResolvedInstances();
        $facade::increment();
        $this->assertSame(2, $counter->count);
    }

    // ── Çözümlenemeyen accessor hata fırlatmalı ─────────────────────────────

    public function test_facade_throws_when_accessor_cannot_be_resolved(): void
    {
        $facade = new class extends Facade
        {
            protected static function getFacadeAccessor(): string
            {
                return 'NonExistentClass_12345';
            }
        };

        $this->expectException(\RuntimeException::class);
        $facade::someMethod();
    }

    // ── getFacadeRoot doğru instance döndürür ───────────────────────────────

    public function test_get_facade_root_returns_correct_instance(): void
    {
        $service = new \stdClass;
        $service->name = 'TestService';

        $this->container->instance('root_test', $service);

        $facade = new class extends Facade
        {
            protected static function getFacadeAccessor(): string
            {
                return 'root_test';
            }
        };

        $root = $facade::getFacadeRoot();
        $this->assertSame($service, $root);
        $this->assertSame('TestService', $root->name);
    }

    // ── clearResolvedInstance (tekil) ────────────────────────────────────────

    public function test_clear_single_resolved_instance(): void
    {
        $service = new \stdClass;
        $this->container->instance('single_clear_test', $service);

        $facade = new class extends Facade
        {
            protected static function getFacadeAccessor(): string
            {
                return 'single_clear_test';
            }
        };

        // İlk çözümleme
        $facade::getFacadeRoot();

        // Tekil temizleme
        Facade::clearResolvedInstance('single_clear_test');

        // Hala Container'dan çözümlenebilmeli
        $root = $facade::getFacadeRoot();
        $this->assertSame($service, $root);
    }
}
