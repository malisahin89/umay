<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Cache;
use Core\Container;
use Tests\TestCase;

/**
 * Cache sistemi testleri.
 *
 * HMAC bütünlük kontrolü, TTL mekanizması, remember(), pull(),
 * flush(), has() ve tampered dosya koruması test edilir.
 */
class CacheTest extends TestCase
{
    private Cache $cache;

    private string $testCachePath;

    private string $prefix;

    protected function setUp(): void
    {
        parent::setUp();

        // Cache'i temiz bir Container'dan al
        $container = Container::getInstance();
        if (! $container->has(Cache::class)) {
            $container->singleton(Cache::class, fn () => new Cache);
        }
        $this->cache = $container->make(Cache::class);
        // Cache artık config'den path + prefix okuyor; testler de aynısını kullanmalı
        $this->testCachePath = (string) config('cache.path', BASE_PATH.'/storage/cache');
        $this->prefix = (string) config('cache.prefix', '');
    }

    protected function tearDown(): void
    {
        // Testler sonrası tüm test cache dosyalarını temizle
        $this->cache->flush();
        parent::tearDown();
    }

    // ── set + get ───────────────────────────────────────────────────────────

    public function test_set_and_get_returns_stored_value(): void
    {
        $this->cache->set('test_key', 'test_value', 300);
        $result = $this->cache->get('test_key');

        $this->assertSame('test_value', $result);
    }

    public function test_get_returns_default_when_key_not_exists(): void
    {
        $result = $this->cache->get('nonexistent_key', 'varsayilan');
        $this->assertSame('varsayilan', $result);
    }

    public function test_get_returns_null_default_when_key_not_exists(): void
    {
        $result = $this->cache->get('nonexistent_key_2');
        $this->assertNull($result);
    }

    // ── Farklı veri tipleri ─────────────────────────────────────────────────

    public function test_cache_stores_array_values(): void
    {
        $data = ['users' => [1, 2, 3], 'meta' => ['page' => 1]];
        $this->cache->set('array_key', $data, 300);

        $this->assertEquals($data, $this->cache->get('array_key'));
    }

    public function test_cache_stores_integer_values(): void
    {
        $this->cache->set('int_key', 42, 300);
        $this->assertSame(42, $this->cache->get('int_key'));
    }

    public function test_cache_stores_boolean_values(): void
    {
        $this->cache->set('bool_key', true, 300);
        $this->assertTrue($this->cache->get('bool_key'));
    }

    // ── has() ───────────────────────────────────────────────────────────────

    public function test_has_returns_true_for_existing_key(): void
    {
        $this->cache->set('has_key', 'exists', 300);
        $this->assertTrue($this->cache->has('has_key'));
    }

    public function test_has_returns_false_for_missing_key(): void
    {
        $this->assertFalse($this->cache->has('missing_key'));
    }

    // ── forget() ────────────────────────────────────────────────────────────

    public function test_forget_removes_key(): void
    {
        $this->cache->set('forget_key', 'value', 300);
        $this->assertTrue($this->cache->has('forget_key'));

        $this->cache->forget('forget_key');
        $this->assertFalse($this->cache->has('forget_key'));
    }

    // ── flush() ─────────────────────────────────────────────────────────────

    public function test_flush_removes_all_keys(): void
    {
        $this->cache->set('flush_1', 'a', 300);
        $this->cache->set('flush_2', 'b', 300);
        $this->cache->set('flush_3', 'c', 300);

        $this->cache->flush();

        $this->assertFalse($this->cache->has('flush_1'));
        $this->assertFalse($this->cache->has('flush_2'));
        $this->assertFalse($this->cache->has('flush_3'));
    }

    // ── pull() ──────────────────────────────────────────────────────────────

    public function test_pull_returns_value_and_removes_key(): void
    {
        $this->cache->set('pull_key', 'pull_value', 300);

        $result = $this->cache->pull('pull_key');
        $this->assertSame('pull_value', $result);
        $this->assertFalse($this->cache->has('pull_key'));
    }

    public function test_pull_returns_default_when_key_not_exists(): void
    {
        $result = $this->cache->pull('nonexistent_pull', 'default_pull');
        $this->assertSame('default_pull', $result);
    }

    // ── remember() ──────────────────────────────────────────────────────────

    public function test_remember_stores_and_returns_callback_value(): void
    {
        $counter = 0;
        $result = $this->cache->remember('remember_key', 300, function () use (&$counter) {
            $counter++;

            return 'computed_value';
        });

        $this->assertSame('computed_value', $result);
        $this->assertSame(1, $counter);

        // İkinci çağrıda callback çalışmamalı (cache'den dönmeli)
        $result2 = $this->cache->remember('remember_key', 300, function () use (&$counter) {
            $counter++;

            return 'new_value';
        });

        $this->assertSame('computed_value', $result2);
        $this->assertSame(1, $counter); // Counter artmamış olmalı
    }

    // ── HMAC bütünlük koruması ──────────────────────────────────────────────

    public function test_tampered_cache_file_returns_default(): void
    {
        $this->cache->set('hmac_test', 'secure_data', 300);

        // Cache dosyasını doğrudan manipüle et
        $filename = $this->testCachePath.'/'.hash('sha256', $this->prefix.'hmac_test').'.cache';
        $this->assertFileExists($filename);

        // İmzayı boz
        file_put_contents($filename, 'TAMPERED_SIGNATURE:{"value":"hacked","expires":'.(time() + 9999).'}');

        $result = $this->cache->get('hmac_test', 'fallback');
        $this->assertSame('fallback', $result);

        // Bozuk dosya silinmiş olmalı
        $this->assertFileDoesNotExist($filename);
    }

    public function test_invalid_cache_format_returns_default(): void
    {
        $this->cache->set('format_test', 'good_data', 300);

        $filename = $this->testCachePath.'/'.hash('sha256', $this->prefix.'format_test').'.cache';
        // Colon olmadan veri yaz — format bozuk
        file_put_contents($filename, 'no_colon_here_invalid_data');

        $result = $this->cache->get('format_test', 'safe_default');
        $this->assertSame('safe_default', $result);
    }

    // ── TTL süresi ──────────────────────────────────────────────────────────

    public function test_expired_cache_returns_default(): void
    {
        $this->cache->set('ttl_test', 'short_lived', 1);

        // 1 saniye geçmişe ayarla
        $filename = $this->testCachePath.'/'.hash('sha256', $this->prefix.'ttl_test').'.cache';
        $content = file_get_contents($filename);

        // İmzalı yapıyı yeniden oluştur ama TTL'i geçmiş tarih yap
        $appKey = $_ENV['APP_KEY'] ?? hash('sha256', BASE_PATH.'umay-cache-key');
        $data = json_encode(['value' => 'short_lived', 'expires' => time() - 100]);
        $signature = hash_hmac('sha256', $data, $appKey);
        file_put_contents($filename, $signature.':'.$data, LOCK_EX);

        $result = $this->cache->get('ttl_test', 'expired_default');
        $this->assertSame('expired_default', $result);
    }

    // ── Config tabanlı davranış (path / prefix / default_ttl) ────────────────

    public function test_set_uses_config_default_ttl_when_omitted(): void
    {
        $before = time();
        $this->cache->set('ttl_default_key', 'value'); // ttl verilmedi

        $filename = $this->testCachePath.'/'.hash('sha256', $this->prefix.'ttl_default_key').'.cache';
        [$sig, $json] = explode(':', file_get_contents($filename), 2);
        $data = json_decode($json, true);

        $expected = $before + (int) config('cache.default_ttl', 3600);
        $this->assertEqualsWithDelta($expected, $data['expires'], 5);
    }

    public function test_prefix_applied_to_cache_filename(): void
    {
        $this->cache->set('prefixed_key', 'value', 300);

        $prefixed = $this->testCachePath.'/'.hash('sha256', $this->prefix.'prefixed_key').'.cache';
        $this->assertFileExists($prefixed);

        if ($this->prefix !== '') {
            $bare = $this->testCachePath.'/'.hash('sha256', 'prefixed_key').'.cache';
            $this->assertFileDoesNotExist($bare);
        }
    }
}
