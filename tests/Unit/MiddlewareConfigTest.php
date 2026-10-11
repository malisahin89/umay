<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;

/**
 * API prefix normalizasyonu testleri.
 * API prefix normalization tests.
 *
 * config/middleware.php normalizes API_PREFIX once (single source of truth):
 * missing/root values fall back to '/api' and a leading slash is enforced, so
 * route registration and request dispatch agree on the same prefix.
 */
class MiddlewareConfigTest extends TestCase
{
    private function loadMiddlewareConfig(): array
    {
        return require dirname(__DIR__, 2).'/config/middleware.php';
    }

    public function test_prefix_without_leading_slash_gets_normalized(): void
    {
        $_ENV['API_PREFIX'] = 'api';

        $this->assertSame('/api', $this->loadMiddlewareConfig()['api_prefix']);

        unset($_ENV['API_PREFIX']);
    }

    public function test_trailing_slash_is_trimmed(): void
    {
        $_ENV['API_PREFIX'] = 'v1/';

        $this->assertSame('/v1', $this->loadMiddlewareConfig()['api_prefix']);

        unset($_ENV['API_PREFIX']);
    }

    public function test_root_prefix_falls_back_to_default(): void
    {
        $_ENV['API_PREFIX'] = '/';
        $this->assertSame('/api', $this->loadMiddlewareConfig()['api_prefix']);

        $_ENV['API_PREFIX'] = '';
        $this->assertSame('/api', $this->loadMiddlewareConfig()['api_prefix']);

        unset($_ENV['API_PREFIX']);
    }

    public function test_missing_prefix_defaults_to_api(): void
    {
        $previous = $_ENV['API_PREFIX'] ?? null;
        unset($_ENV['API_PREFIX']);

        $this->assertSame('/api', $this->loadMiddlewareConfig()['api_prefix']);

        if ($previous !== null) {
            $_ENV['API_PREFIX'] = $previous;
        }
    }
}