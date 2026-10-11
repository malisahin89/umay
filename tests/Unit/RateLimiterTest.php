<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Container;
use Core\RateLimiter;
use Tests\TestCase;

class RateLimiterTest extends TestCase
{
    private RateLimiter $limiter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->limiter = Container::getInstance()->make(RateLimiter::class);

        // Clear test keys
        // Test key'lerini temizle
        $this->limiter->clear('test_key');
    }

    public function test_not_too_many_attempts_initially(): void
    {
        $this->assertFalse($this->limiter->tooManyAttempts('test_key', 5, 300));
    }

    public function test_hit_increments_counter(): void
    {
        $this->limiter->hit('test_key', 300);
        $this->limiter->hit('test_key', 300);
        $this->assertEquals(2, $this->limiter->attempts('test_key'));
    }

    public function test_too_many_attempts_after_limit(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->limiter->hit('test_key', 300);
        }
        $this->assertTrue($this->limiter->tooManyAttempts('test_key', 5, 300));
    }

    public function test_clear_resets_counter(): void
    {
        $this->limiter->hit('test_key', 300);
        $this->limiter->hit('test_key', 300);
        $this->limiter->clear('test_key');
        $this->assertEquals(0, $this->limiter->attempts('test_key'));
        $this->assertFalse($this->limiter->tooManyAttempts('test_key', 5, 300));
    }

    public function test_remaining_decreases_with_hits(): void
    {
        $this->limiter->hit('test_key', 300);
        $this->limiter->hit('test_key', 300);
        $this->assertEquals(3, $this->limiter->remaining('test_key', 5));
    }

    public function test_named_limiter_registration(): void
    {
        $this->limiter->for('login', 3, 60);
        $config = $this->limiter->limiter('login');
        $this->assertEquals(3, $config['max']);
        $this->assertEquals(60, $config['decay']);
    }
}
