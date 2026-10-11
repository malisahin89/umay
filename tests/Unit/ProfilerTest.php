<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Profiler\Profiler;
use Tests\TestCase;

/**
 * Profiler yardımcıları testleri.
 * Profiler helper tests.
 *
 * maskUriQuery: profiler deposuna yazılan ham URI'lerdeki kimlik-bilgisi benzeri
 * query parametreleri maskelenmeli (?token=… diske düz metin yazılmamalı).
 * maskUriQuery: credential-like query params in raw URIs persisted to profiler
 * storage must be masked (?token=… must not hit disk in plaintext).
 */
class ProfilerTest extends TestCase
{
    public function test_mask_uri_query_masks_credential_like_params(): void
    {
        $masked = Profiler::maskUriQuery('/reset?token=abc123&page=2');

        $this->assertStringNotContainsString('abc123', $masked);
        $this->assertStringContainsString('token=%2A%2A%2A%2A%2A%2A%2A%2A', $masked);
        $this->assertStringContainsString('page=2', $masked);
    }

    public function test_mask_uri_query_masks_api_key(): void
    {
        $masked = Profiler::maskUriQuery('/api/posts?api_key=SECRET&q=umay');

        $this->assertStringNotContainsString('SECRET', $masked);
        $this->assertStringContainsString('q=umay', $masked);
    }

    public function test_mask_uri_query_leaves_plain_uris_untouched(): void
    {
        $this->assertSame('/posts/5', Profiler::maskUriQuery('/posts/5'));
        $this->assertSame('/posts?page=3&sort=asc', Profiler::maskUriQuery('/posts?page=3&sort=asc'));
    }
}
