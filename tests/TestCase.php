<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;
use Core\Auth;
use Core\Container;
use Core\Events\Dispatcher;
use Core\Request;
use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * Project base TestCase class.
 * Proje base TestCase sınıfı.
 *
 * Before each test:
 * Her testten önce:
 *   - Session is reset // Session sıfırlanır
 *   - Event dispatcher is cleared // Event dispatcher temizlenir
 *   - Container is reset // Container sıfırlanır
 *   - Auth cache is cleared // Auth cache temizlenir
 *
 * Helper methods:
 * Yardımcı metodlar:
 *   $this->makeRequest('POST', '/login', ['email' => ...])
 *   $this->actingAs($user)
 *   $this->withSession(['key' => 'value'])
 */
abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Start session (array handler in test environment)
        // Session başlat (test ortamında array handler)
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.use_cookies', 0);
            ini_set('session.use_only_cookies', 0);
            session_id('test_session_'.uniqid());
            @session_start();
        }

        $_SESSION = [];
        $_POST = [];
        $_GET = [];
        $_FILES = [];
        $_COOKIE = [];
        $_SERVER['REQUEST_METHOD'] = 'GET';

        // Clear auth cache
        // Auth cache temizle
        if (Container::getInstance()->has(Auth::class)) {
            Container::getInstance()->make(Auth::class)->clearCache();
        }

        // Clear event listeners
        // Event listener'ları temizle
        Dispatcher::flush();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Dispatcher::flush();
        if (Container::getInstance()->has(Auth::class)) {
            Container::getInstance()->make(Auth::class)->clearCache();
        }
    }

    // ── Request Helpers ───────────────────────────────────────────────────────
    // ── Request Yardımcıları ──────────────────────────────────────────────────

    /**
     * Create test Request object.
     * Test Request nesnesi oluştur.
     *
     * $request = $this->makeRequest('POST', '/login', ['email' => 'a@b.com']);
     */
    protected function makeRequest(
        string $method = 'GET',
        string $uri = '/',
        array $data = [],
        array $headers = []
    ): Request {
        $_SERVER['REQUEST_METHOD'] = strtoupper($method);
        $_SERVER['REQUEST_URI'] = $uri;
        $_SERVER['HTTP_HOST'] = 'localhost';

        foreach ($headers as $key => $value) {
            $_SERVER['HTTP_'.strtoupper(str_replace('-', '_', $key))] = $value;
        }

        if (strtoupper($method) === 'GET') {
            $_GET = $data;
            $_POST = [];
        } else {
            $_POST = $data;
            $_GET = [];
        }

        return Request::capture();
    }

    /**
     * Mark the user as logged in.
     * Kullanıcıyı giriş yapmış gibi işaretle.
     *
     * $this->actingAs($user);
     * $this->assertTrue(Auth::check());
     */
    protected function actingAs(User $user): static
    {
        $_SESSION['user_id'] = $user->id;
        if (Container::getInstance()->has(Auth::class)) {
            Container::getInstance()->make(Auth::class)->clearCache();
        }

        return $this;
    }

    /**
     * Add session data.
     * Session verisi ekle.
     *
     * $this->withSession(['_flash' => ['error' => 'Hata!']]);
     */
    protected function withSession(array $data): static
    {
        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }

        return $this;
    }

    // ── Assertion Helpers ─────────────────────────────────────────────────────
    // ── Assertion Yardımcıları ────────────────────────────────────────────────

    /** Verify that a specific key exists in the session // Session'da belirli key'in var olduğunu doğrula */
    protected function assertSessionHas(string $key, mixed $value = null): void
    {
        $this->assertArrayHasKey($key, $_SESSION, "Session '{$key}' anahtarını içermiyor.");
        if ($value !== null) {
            $this->assertEquals($value, $_SESSION[$key], "Session '{$key}' beklenen değeri içermiyor.");
        }
    }

    /** Verify that a specific key does not exist in the session // Session'da belirli key'in olmadığını doğrula */
    protected function assertSessionMissing(string $key): void
    {
        $this->assertArrayNotHasKey($key, $_SESSION, "Session beklenmedik '{$key}' anahtarını içeriyor.");
    }

    /** Verify that a flash message exists // Flash mesajının var olduğunu doğrula */
    protected function assertFlash(string $type, ?string $message = null): void
    {
        $this->assertArrayHasKey('_flash', $_SESSION, 'Session _flash içermiyor.');
        $this->assertArrayHasKey($type, $_SESSION['_flash'], "Flash '{$type}' mesajı yok.");
        if ($message !== null) {
            $this->assertEquals($message, $_SESSION['_flash'][$type]);
        }
    }
}
