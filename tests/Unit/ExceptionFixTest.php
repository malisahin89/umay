<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Application;
use Core\Container;
use Core\ExceptionHandler;
use Core\RedirectException;
use Core\TerminateException;
use Tests\TestCase;

class ExceptionFixTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Önceki testlerden kalan ExceptionHandler binding'ini temizle
        // Clear any leftover ExceptionHandler binding from previous tests
        $container = Container::getInstance();
        $container->instance(ExceptionHandler::class, new ExceptionHandler);
    }

    public function test_redirect_exception_extends_terminate_exception()
    {
        $e = new RedirectException('Redirecting');

        $this->assertInstanceOf(TerminateException::class, $e);
        $this->assertInstanceOf(\RuntimeException::class, $e);
    }

    public function test_exception_handler_resolves_from_container()
    {
        $container = Container::getInstance();
        $app = Application::getInstance();

        // Bind custom handler to container — çıktı üretmeyen, exit yapmayan handler
        // Bind custom handler to container — no output, no exit
        $customHandler = new class extends ExceptionHandler
        {
            public bool $wasCalled = false;

            public function handle(\Throwable $e): void
            {
                $this->wasCalled = true;
                // Üst sınıfı çağırma (exit ve echo yapar)
                // Don't call parent (it calls exit and echo)
            }
        };

        $container->instance(ExceptionHandler::class, $customHandler);

        // Output buffer başlat (ekstra güvenlik)
        ob_start();
        $app->handleException(new \Exception('Test error'));
        ob_end_clean();

        $this->assertTrue($customHandler->wasCalled);
    }
}
