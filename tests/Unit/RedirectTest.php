<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Redirect;
use Tests\TestCase;

/**
 * Redirect güvenli hedef çözümleme (open-redirect koruması) testleri.
 * Safe redirect target resolution (open-redirect protection) tests.
 */
class RedirectTest extends TestCase
{
    public function test_safe_relative_path_is_kept(): void
    {
        $this->assertSame('/dashboard', Redirect::resolve('/dashboard'));
    }

    public function test_schemeless_path_is_prefixes_with_slash(): void
    {
        $this->assertSame('/dashboard', Redirect::resolve('dashboard'));
    }

    public function test_protocol_relative_url_is_denied(): void
    {
        $this->assertSame('/', Redirect::resolve('//evil.com'));
    }

    public function test_protocol_relative_slash_backslash_is_denied(): void
    {
        $this->assertSame('/', Redirect::resolve('/\\evil.com'));
    }

    public function test_leading_backslash_is_denied(): void
    {
        $this->assertSame('/', Redirect::resolve('\\evil.com'));
    }

    public function test_external_absolute_url_is_denied(): void
    {
        $this->assertSame('/', Redirect::resolve('https://evil.com/path'));
    }

    public function test_crlf_is_stripped(): void
    {
        // A leading backslash that survives stripping still triggers the deny.
        $result = Redirect::resolve('\\evil'.chr(13).chr(10).'Location: https://evil.com');
        $this->assertSame('/', $result);

        // Resolved values must never contain CR/LF (header injection).
        $relative = Redirect::resolve('/dashboard'.chr(13).chr(10).'X-Evil: 1');
        $this->assertStringNotContainsString("\r", $relative);
        $this->assertStringNotContainsString("\n", $relative);
    }
}