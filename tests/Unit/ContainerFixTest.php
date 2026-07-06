<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Container;
use RuntimeException;
use Tests\TestCase;

class ClassA
{
    public function __construct(ClassB $b) {}
}
class ClassB
{
    public function __construct(ClassA $a) {}
}

interface CacheDriver {}
class FileCache implements CacheDriver {}
class RedisCache implements CacheDriver {}

class ServiceWithUnion
{
    public function __construct(FileCache|RedisCache $cache) {}
}

class ContainerFixTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_circular_dependency_throws_exception()
    {
        $container = Container::getInstance();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches('/Circular dependency/');

        $container->make(ClassA::class);
    }

    public function test_union_type_support()
    {
        $container = Container::getInstance();
        $container->singleton(FileCache::class, fn () => new FileCache);

        $service = $container->make(ServiceWithUnion::class);

        $this->assertNotNull($service);
        $this->assertInstanceOf(ServiceWithUnion::class, $service);
    }
}
