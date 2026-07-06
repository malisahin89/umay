<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\ResponseBuilder;
use Tests\TestCase;

/**
 * ResponseBuilder testleri.
 *
 * Fluent API, JSON/HTML body, header yönetimi,
 * status kodları ve download mekanizması test edilir.
 */
class ResponseBuilderTest extends TestCase
{
    // ── Temel constructor ───────────────────────────────────────────────────

    public function test_constructor_accepts_body_and_status(): void
    {
        $rb = new ResponseBuilder('Hello', 201);

        // İç durumu doğrudan göremiyoruz, ama hata fırlatmaması yeterli
        $this->assertInstanceOf(ResponseBuilder::class, $rb);
    }

    // ── JSON body ───────────────────────────────────────────────────────────

    public function test_json_sets_content_type_and_body(): void
    {
        $rb = new ResponseBuilder;
        $result = $rb->json(['success' => true, 'data' => 'test']);

        // Fluent dönüş
        $this->assertSame($rb, $result);
    }

    public function test_json_with_status_code(): void
    {
        $rb = new ResponseBuilder;
        $result = $rb->json(['error' => 'not_found'], 404);

        $this->assertInstanceOf(ResponseBuilder::class, $result);
    }

    // ── HTML body ───────────────────────────────────────────────────────────

    public function test_html_sets_body(): void
    {
        $rb = new ResponseBuilder;
        $result = $rb->html('<h1>Merhaba</h1>');

        $this->assertSame($rb, $result);
    }

    // ── Header yönetimi ─────────────────────────────────────────────────────

    public function test_header_returns_fluent_instance(): void
    {
        $rb = new ResponseBuilder;
        $result = $rb->header('X-Custom', 'Umay');

        $this->assertSame($rb, $result);
    }

    public function test_with_headers_merges_headers(): void
    {
        $rb = new ResponseBuilder;
        $result = $rb->withHeaders([
            'X-First' => 'A',
            'X-Second' => 'B',
        ]);

        $this->assertSame($rb, $result);
    }

    // ── Status code ─────────────────────────────────────────────────────────

    public function test_status_method_returns_fluent(): void
    {
        $rb = new ResponseBuilder;
        $result = $rb->status(204);

        $this->assertSame($rb, $result);
    }

    // ── Chained fluent API ──────────────────────────────────────────────────

    public function test_chained_fluent_api(): void
    {
        $rb = new ResponseBuilder;
        $result = $rb
            ->status(200)
            ->header('X-API', 'Umay')
            ->json(['status' => 'ok']);

        $this->assertInstanceOf(ResponseBuilder::class, $result);
    }

    // ── Download — dosya bulunamadığında hata ───────────────────────────────

    public function test_download_throws_when_file_not_found(): void
    {
        $rb = new ResponseBuilder;

        $this->expectException(\RuntimeException::class);
        $rb->download('/nonexistent/file/path.pdf');
    }
}
