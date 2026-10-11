<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Request;
use Tests\TestCase;

class RequestTest extends TestCase
{
    public function test_bearer_token_extracted_from_authorization_header(): void
    {
        $request = $this->makeRequest('GET', '/', [], ['Authorization' => 'Bearer mytoken123']);
        $this->assertEquals('mytoken123', $request->bearerToken());
    }

    public function test_bearer_token_returns_null_when_no_header(): void
    {
        unset($_SERVER['HTTP_AUTHORIZATION']);
        $request = $this->makeRequest('GET', '/');
        $this->assertNull($request->bearerToken());
    }

    public function test_bearer_token_returns_null_when_not_bearer_scheme(): void
    {
        $request = $this->makeRequest('GET', '/', [], ['Authorization' => 'Basic dXNlcjpwYXNz']);
        $this->assertNull($request->bearerToken());
    }

    public function test_expects_json_true_when_bearer_token_present(): void
    {
        $request = $this->makeRequest('GET', '/', [], ['Authorization' => 'Bearer token123']);
        $this->assertTrue($request->expectsJson());
    }

    public function test_expects_json_true_when_accept_json(): void
    {
        $request = $this->makeRequest('GET', '/', [], ['Accept' => 'application/json']);
        $this->assertTrue($request->expectsJson());
    }

    public function test_post_data_accessible(): void
    {
        $request = $this->makeRequest('POST', '/login', ['email' => 'a@b.com', 'pass' => 'secret']);
        $this->assertEquals('a@b.com', $request->post('email'));
        $this->assertNull($request->post('nonexistent'));
    }

    public function test_get_data_accessible(): void
    {
        $request = $this->makeRequest('GET', '/', ['page' => '2', 'sort' => 'name']);
        $this->assertEquals('2', $request->get('page'));
        $this->assertEquals('name', $request->get('sort'));
    }

    public function test_only_filters_fields(): void
    {
        $request = $this->makeRequest('POST', '/', ['name' => 'Ali', 'email' => 'a@b.com', 'password' => 'secret']);
        $only = $request->only(['name', 'email']);
        $this->assertArrayHasKey('name', $only);
        $this->assertArrayHasKey('email', $only);
        $this->assertArrayNotHasKey('password', $only);
    }

    public function test_filled_returns_false_for_empty_string(): void
    {
        $request = $this->makeRequest('POST', '/', ['name' => '']);
        $this->assertFalse($request->filled('name'));
    }

    public function test_filled_returns_true_for_non_empty(): void
    {
        $request = $this->makeRequest('POST', '/', ['name' => 'Ali']);
        $this->assertTrue($request->filled('name'));
    }

    public function test_method_detection(): void
    {
        $request = $this->makeRequest('POST', '/');
        $this->assertTrue($request->isPost());
        $this->assertFalse($request->isGet());
        $this->assertEquals('POST', $request->method());
    }

    // ── header(): CGI/FPM'de Content-Type/Length HTTP_ öneksizdir ────────────
    // ── header(): Content-Type/Length live without HTTP_ prefix on CGI/FPM ───

    public function test_header_reads_content_type_without_http_prefix(): void
    {
        $request = new Request([], ['x' => '1'], [], ['CONTENT_TYPE' => 'text/plain'], []);

        $this->assertSame('text/plain', $request->header('Content-Type'));
    }

    public function test_header_reads_content_length_without_http_prefix(): void
    {
        $request = new Request([], [], [], ['CONTENT_LENGTH' => '42'], []);

        $this->assertSame('42', $request->header('Content-Length'));
    }

    public function test_header_prefers_http_prefixed_value(): void
    {
        $request = new Request([], ['x' => '1'], [], [
            'HTTP_CONTENT_TYPE' => 'application/xml',
            'CONTENT_TYPE' => 'text/plain',
        ], []);

        $this->assertSame('application/xml', $request->header('Content-Type'));
    }

    // ── hasFile(): tekli ve çoklu (name="x[]") yüklemeler ────────────────────
    // ── hasFile(): single and multi (name="x[]") uploads ─────────────────────

    public function test_has_file_true_for_successful_single_upload(): void
    {
        $files = ['avatar' => ['name' => 'a.png', 'tmp_name' => '/tmp/a', 'error' => UPLOAD_ERR_OK, 'size' => 10]];
        $request = new Request([], [], $files, [], []);

        $this->assertTrue($request->hasFile('avatar'));
    }

    public function test_has_file_false_when_no_file(): void
    {
        $files = ['avatar' => ['name' => '', 'tmp_name' => '', 'error' => UPLOAD_ERR_NO_FILE, 'size' => 0]];
        $request = new Request([], [], $files, [], []);

        $this->assertFalse($request->hasFile('avatar'));
        $this->assertFalse($request->hasFile('missing'));
    }

    public function test_has_file_multi_upload_true_when_any_succeeded(): void
    {
        $files = ['photos' => [
            'name' => ['a.png', ''],
            'tmp_name' => ['/tmp/a', ''],
            'error' => [UPLOAD_ERR_OK, UPLOAD_ERR_NO_FILE],
            'size' => [10, 0],
        ]];
        $request = new Request([], [], $files, [], []);

        $this->assertTrue($request->hasFile('photos'));
    }

    public function test_has_file_multi_upload_false_when_none_succeeded(): void
    {
        $files = ['photos' => [
            'name' => ['', ''],
            'tmp_name' => ['', ''],
            'error' => [UPLOAD_ERR_NO_FILE, UPLOAD_ERR_NO_FILE],
            'size' => [0, 0],
        ]];
        $request = new Request([], [], $files, [], []);

        $this->assertFalse($request->hasFile('photos'));
    }
}
