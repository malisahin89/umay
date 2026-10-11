<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Csrf;
use Tests\TestCase;

/**
 * CSRF koruma sistemi testleri.
 *
 * Token üretimi, session saklama, doğrulama,
 * timing-safe karşılaştırma ve edge case'ler test edilir.
 */
class CsrfTest extends TestCase
{
    // ── Token üretimi ───────────────────────────────────────────────────────

    public function test_generate_creates_token(): void
    {
        $token = Csrf::generate();

        $this->assertNotEmpty($token);
        $this->assertIsString($token);
    }

    public function test_generate_returns_same_token_in_same_session(): void
    {
        $token1 = Csrf::generate();
        $token2 = Csrf::generate();

        $this->assertSame($token1, $token2);
    }

    public function test_token_is_64_characters_hex(): void
    {
        $token = Csrf::generate();

        $this->assertSame(64, strlen($token));
        $this->assertMatchesRegularExpression('/^[0-9a-f]{64}$/', $token);
    }

    // ── Token doğrulama ─────────────────────────────────────────────────────

    public function test_check_validates_correct_token(): void
    {
        $token = Csrf::generate();
        $this->assertTrue(Csrf::check($token));
    }

    public function test_check_rejects_wrong_token(): void
    {
        Csrf::generate();
        $this->assertFalse(Csrf::check('wrong_token'));
    }

    public function test_check_rejects_empty_string(): void
    {
        Csrf::generate();
        $this->assertFalse(Csrf::check(''));
    }

    public function test_check_rejects_null(): void
    {
        Csrf::generate();
        $this->assertFalse(Csrf::check(null));
    }

    public function test_check_rejects_integer(): void
    {
        Csrf::generate();
        $this->assertFalse(Csrf::check(12345));
    }

    public function test_check_rejects_array(): void
    {
        Csrf::generate();
        $this->assertFalse(Csrf::check(['token']));
    }

    // ── Session'da saklanma ─────────────────────────────────────────────────

    public function test_token_stored_in_session(): void
    {
        $token = Csrf::generate();
        $this->assertSame($token, $_SESSION['csrf_token']);
    }

    // ── Session olmadan check ───────────────────────────────────────────────

    public function test_check_fails_when_no_session_token(): void
    {
        // Session'daki token'ı temizle
        unset($_SESSION['csrf_token']);

        $this->assertFalse(Csrf::check('some_token'));
    }
}
