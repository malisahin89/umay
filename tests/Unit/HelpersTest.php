<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Exceptions\HttpException;
use Tests\TestCase;

/**
 * Helpers fonksiyon testleri.
 *
 * str_slug, str_limit, now, today, method_field,
 * ipInCidr, isCloudflareIP, asset, old, flash testleri.
 */
class HelpersTest extends TestCase
{
    // ── str_slug ─────────────────────────────────────────────────────────────

    public function test_str_slug_converts_turkish_characters(): void
    {
        $this->assertSame('merhaba-dunya', str_slug('Merhaba Dünya'));
    }

    public function test_str_slug_handles_special_characters(): void
    {
        $this->assertSame('hello-world', str_slug('Hello & World!'));
    }

    public function test_str_slug_handles_multiple_spaces(): void
    {
        $this->assertSame('bir-iki-uc', str_slug('Bir   İki   Üç'));
    }

    public function test_str_slug_uses_custom_separator(): void
    {
        $this->assertSame('merhaba_dunya', str_slug('Merhaba Dünya', '_'));
    }

    public function test_str_slug_handles_empty_string(): void
    {
        $this->assertSame('', str_slug(''));
    }

    // ── str_limit ────────────────────────────────────────────────────────────

    public function test_str_limit_truncates_long_text(): void
    {
        $this->assertSame('Merhaba...', str_limit('Merhaba Dünya', 7));
    }

    public function test_str_limit_does_not_truncate_short_text(): void
    {
        $this->assertSame('Kısa', str_limit('Kısa', 100));
    }

    public function test_str_limit_uses_custom_end(): void
    {
        $this->assertSame('Mer →', str_limit('Merhaba', 3, ' →'));
    }

    // ── now / today ──────────────────────────────────────────────────────────

    public function test_now_returns_current_datetime(): void
    {
        $result = now();
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $result);
    }

    public function test_now_accepts_custom_format(): void
    {
        $result = now('Y');
        $this->assertSame(date('Y'), $result);
    }

    public function test_today_returns_current_date(): void
    {
        $result = today();
        $this->assertSame(date('Y-m-d'), $result);
    }

    public function test_today_accepts_custom_format(): void
    {
        $result = today('d.m.Y');
        $this->assertSame(date('d.m.Y'), $result);
    }

    // ── method_field ─────────────────────────────────────────────────────────

    public function test_method_field_generates_hidden_input(): void
    {
        $html = method_field('PUT');
        $this->assertStringContainsString('type="hidden"', $html);
        $this->assertStringContainsString('name="_method"', $html);
        $this->assertStringContainsString('value="PUT"', $html);
    }

    public function test_method_field_uppercases_method(): void
    {
        $html = method_field('delete');
        $this->assertStringContainsString('value="DELETE"', $html);
    }

    public function test_method_field_escapes_xss(): void
    {
        $html = method_field('"><script>alert(1)</script>');
        $this->assertStringNotContainsString('<script>', $html);
    }

    // ── asset ────────────────────────────────────────────────────────────────

    public function test_asset_returns_path_with_leading_slash(): void
    {
        $this->assertSame('/css/app.css', asset('css/app.css'));
    }

    public function test_asset_does_not_double_slash(): void
    {
        $this->assertSame('/js/app.js', asset('/js/app.js'));
    }

    // ── flash ────────────────────────────────────────────────────────────────

    public function test_flash_sets_and_gets_message(): void
    {
        flash('success', 'Kayıt başarılı');
        $this->assertSame('Kayıt başarılı', flash('success'));
    }

    public function test_flash_returns_null_when_no_message(): void
    {
        $this->assertNull(flash('nonexistent'));
    }

    public function test_flash_is_consumed_after_read(): void
    {
        flash('error', 'Hata oluştu');
        flash('error'); // ilk okuma
        $this->assertNull(flash('error')); // ikinci okuma null
    }

    // ── old ──────────────────────────────────────────────────────────────────

    public function test_old_returns_default_when_no_old_data(): void
    {
        $this->assertSame('', old('name'));
    }

    public function test_old_returns_previous_post_data(): void
    {
        $_SESSION['_old'] = ['name' => 'Ali'];
        $this->assertSame('Ali', old('name'));
    }

    public function test_old_escapes_xss_by_default(): void
    {
        $_SESSION['_old'] = ['name' => '<script>alert(1)</script>'];
        $result = old('name');
        $this->assertStringNotContainsString('<script>', $result);
        $this->assertStringContainsString('&lt;script&gt;', $result);
    }

    public function test_old_can_skip_escaping(): void
    {
        $_SESSION['_old'] = ['bio' => '<b>Bold</b>'];
        $result = old('bio', '', false);
        $this->assertSame('<b>Bold</b>', $result);
    }

    // ── ipInCidr ─────────────────────────────────────────────────────────────

    public function test_ip_in_cidr_returns_true_for_matching_ip(): void
    {
        $this->assertTrue(ipInCidr('192.168.1.100', '192.168.1.0/24'));
    }

    public function test_ip_in_cidr_returns_false_for_non_matching_ip(): void
    {
        $this->assertFalse(ipInCidr('10.0.0.1', '192.168.1.0/24'));
    }

    public function test_ip_in_cidr_handles_single_host(): void
    {
        $this->assertTrue(ipInCidr('10.0.0.1', '10.0.0.1/32'));
    }

    public function test_ip_in_cidr_returns_false_for_invalid_ip(): void
    {
        $this->assertFalse(ipInCidr('invalid', '192.168.1.0/24'));
    }

    public function test_ip_in_cidr_handles_ipv6(): void
    {
        $this->assertTrue(ipInCidr('2001:db8::1', '2001:db8::/32'));
    }

    public function test_ip_in_cidr_without_slash_requires_exact_match(): void
    {
        // '/' içermeyen girdi CIDR değildir — birebir IP karşılaştırması yapılmalı.
        // Eski davranışta $bits 0'a düşüyor ve HER IP eşleşiyordu (spoof riski).
        // Input without '/' is not CIDR — must fall back to exact-IP comparison.
        // Previously $bits collapsed to 0 and EVERY IP matched (spoofing risk).
        $this->assertTrue(ipInCidr('1.2.3.4', '1.2.3.4'));
        $this->assertFalse(ipInCidr('5.6.7.8', '1.2.3.4'));
        $this->assertFalse(ipInCidr('192.168.1.1', '10.0.0.0'));
    }

    // ── isCloudflareIP ───────────────────────────────────────────────────────

    public function test_cloudflare_ip_returns_true_for_cf_range(): void
    {
        $this->assertTrue(isCloudflareIP('104.16.0.1'));
    }

    public function test_cloudflare_ip_returns_false_for_private_ip(): void
    {
        $this->assertFalse(isCloudflareIP('192.168.1.1'));
    }

    public function test_cloudflare_ip_returns_true_for_cf_ipv6(): void
    {
        $this->assertTrue(isCloudflareIP('2606:4700::1'));
    }

    // ── getRealIP ────────────────────────────────────────────────────────────

    public function test_get_real_ip_returns_remote_addr_by_default(): void
    {
        $_SERVER['REMOTE_ADDR'] = '203.0.113.50';
        unset($_SERVER['HTTP_X_FORWARDED_FOR'], $_SERVER['HTTP_CF_CONNECTING_IP']);

        $this->assertSame('203.0.113.50', getRealIP());
    }

    public function test_get_real_ip_trusts_x_forwarded_for_from_localhost(): void
    {
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '85.100.50.25';

        $this->assertSame('85.100.50.25', getRealIP());
    }

    public function test_get_real_ip_uses_rightmost_untrusted_forwarded_entry(): void
    {
        // Spoof resistance: a client may prepend a fake entry, but the trusted proxy only
        // APPENDS the real client IP to the right — we must return that, not the spoof.
        // Spoof direnci: client en sola sahte değer ekleyebilir, ama güvenilir proxy gerçek
        // client IP'sini yalnızca sağa EKLER — soldaki sahteyi değil onu döndürmeliyiz.
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '1.2.3.4, 85.100.50.25';

        $this->assertSame('85.100.50.25', getRealIP());

        unset($_SERVER['HTTP_X_FORWARDED_FOR']);
    }

    public function test_get_real_ip_ignores_x_forwarded_for_from_untrusted(): void
    {
        $_SERVER['REMOTE_ADDR'] = '203.0.113.50';
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '10.0.0.1';

        $this->assertSame('203.0.113.50', getRealIP());
    }

    // ── isSecure ─────────────────────────────────────────────────────────────

    public function test_is_secure_true_when_https_on(): void
    {
        $_SERVER['HTTPS'] = 'on';
        unset($_SERVER['HTTP_X_FORWARDED_PROTO']);

        $this->assertTrue(isSecure());

        unset($_SERVER['HTTPS']);
    }

    public function test_is_secure_false_on_plain_http(): void
    {
        unset($_SERVER['HTTPS'], $_SERVER['HTTP_X_FORWARDED_PROTO']);
        $_SERVER['REMOTE_ADDR'] = '203.0.113.50';

        $this->assertFalse(isSecure());
    }

    public function test_is_secure_trusts_forwarded_proto_from_trusted_proxy(): void
    {
        unset($_SERVER['HTTPS']);
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';

        $this->assertTrue(isSecure());

        unset($_SERVER['HTTP_X_FORWARDED_PROTO']);
    }

    public function test_is_secure_ignores_forwarded_proto_from_untrusted_client(): void
    {
        // Doğrudan gelen client X-Forwarded-Proto ile şemayı sahteleyememeli.
        // A direct client must not be able to spoof the scheme via X-Forwarded-Proto.
        unset($_SERVER['HTTPS']);
        $_SERVER['REMOTE_ADDR'] = '203.0.113.50';
        $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';

        $this->assertFalse(isSecure());

        unset($_SERVER['HTTP_X_FORWARDED_PROTO']);
    }

    public function test_is_secure_reads_first_value_of_proto_chain(): void
    {
        // Proxy zinciri virgüllü liste gönderebilir — ilk değer istemciye bakan şemadır.
        // Proxy chains may send a comma list — the first value is the client-facing scheme.
        unset($_SERVER['HTTPS']);
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https, http';

        $this->assertTrue(isSecure());

        unset($_SERVER['HTTP_X_FORWARDED_PROTO']);
    }

    // ── validate ─────────────────────────────────────────────────────────────

    public function test_validate_returns_null_when_valid(): void
    {
        $result = validate(['name' => 'Ali'], ['name' => 'required']);
        $this->assertNull($result);
    }

    public function test_validate_returns_errors_when_invalid(): void
    {
        $result = validate(['name' => ''], ['name' => 'required']);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('name', $result);
    }

    // ── abort ────────────────────────────────────────────────────────────────

    public function test_abort_throws_http_exception(): void
    {
        $this->expectException(HttpException::class);
        abort(404, 'Not found');
    }

    // ── csrf ─────────────────────────────────────────────────────────────────

    public function test_csrf_returns_token_string(): void
    {
        $token = csrf();
        $this->assertIsString($token);
        $this->assertNotEmpty($token);
    }

    public function test_csrf_token_equals_csrf(): void
    {
        $this->assertSame(csrf_token(), $_SESSION['csrf_token']);
    }

    // ── config() — config/database.php artık array döndürür ───────────────────
    // config/database.php now returns its array, so config('database.*') works.

    public function test_config_reads_database_array(): void
    {
        $this->assertSame('mysql', config('database.driver'));
        $this->assertSame('utf8mb4', config('database.charset'));
    }

    public function test_config_returns_default_for_missing_database_key(): void
    {
        $this->assertSame('fallback', config('database.no_such_key', 'fallback'));
    }
}
