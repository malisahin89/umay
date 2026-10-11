<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Middleware\ThrottleMiddleware;
use Core\Facades\RateLimiter;
use Tests\TestCase;

/**
 * ThrottleMiddleware named limiter çözümleme testleri.
 * ThrottleMiddleware named limiter resolution tests.
 *
 * Tanımsız named limiter sessizce 60/60'a düşüyordu — sıkı bir limit (5/300)
 * yazım hatasıyla fark edilmeden gevşerdi. Artık net hata veriyor.
 * An undefined named limiter silently fell back to 60/60 — a strict limit (5/300)
 * loosened unnoticed on a typo. It now fails loudly.
 */
class ThrottleMiddlewareTest extends TestCase
{
    public function test_undefined_named_limiter_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('tanımsız-limiter');

        new ThrottleMiddleware('tanımsız-limiter');
    }

    public function test_defined_named_limiter_is_accepted(): void
    {
        RateLimiter::for('tm-test-limiter', 5, 300);

        $middleware = new ThrottleMiddleware('tm-test-limiter');

        $this->assertInstanceOf(ThrottleMiddleware::class, $middleware);
    }

    public function test_numeric_param_is_accepted(): void
    {
        $middleware = new ThrottleMiddleware('10,60');

        $this->assertInstanceOf(ThrottleMiddleware::class, $middleware);
    }
}
