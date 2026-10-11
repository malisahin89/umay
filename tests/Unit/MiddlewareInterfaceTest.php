<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Middleware\ThrottleMiddleware;
use Core\Contracts\MiddlewareInterface;
use Core\Middleware\Cors;
use Core\Middleware\RememberMe;
use Core\Middleware\SecurityHeaders;
use Core\Middleware\VerifyCsrfToken;
use Tests\TestCase;

class MiddlewareInterfaceTest extends TestCase
{
    public function test_all_middlewares_implement_interface()
    {
        $middlewares = [
            VerifyCsrfToken::class,
            SecurityHeaders::class,
            RememberMe::class,
            Cors::class,
            ThrottleMiddleware::class,
        ];

        foreach ($middlewares as $class) {
            // Ensure the class actually exists (If mistyped or deleted, test must FAIL)
            // Sınıfın gerçekten var olduğundan emin ol (Yanlış yazım veya silinme durumunda test HATA vermeli)
            $this->assertTrue(
                class_exists($class),
                "HATA: {$class} sınıfı bulunamadı. İsim değişmiş veya silinmiş olabilir."
            );

            $implements = class_implements($class);
            $this->assertArrayHasKey(
                MiddlewareInterface::class,
                $implements,
                "Sınıf {$class} MiddlewareInterface implement etmiyor."
            );
        }
    }
}
