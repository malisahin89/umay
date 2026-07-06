<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Exceptions\ContainerException;
use Core\Exceptions\EntryNotFoundException;
use Core\Exceptions\HttpException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tests\TestCase;

/**
 * Exception sınıfları testleri.
 *
 * ContainerException, EntryNotFoundException ve HttpException'ın
 * PSR-11 uyumluluğu, hata mesajları ve status kodları test edilir.
 */
class ExceptionClassesTest extends TestCase
{
    // ── ContainerException ──────────────────────────────────────────────────

    public function test_container_exception_implements_psr11(): void
    {
        $e = new ContainerException('Test');
        $this->assertInstanceOf(ContainerExceptionInterface::class, $e);
    }

    public function test_container_exception_has_message(): void
    {
        $e = new ContainerException('Circular dependency detected');
        $this->assertSame('Circular dependency detected', $e->getMessage());
    }

    // ── EntryNotFoundException ───────────────────────────────────────────────

    public function test_entry_not_found_implements_psr11(): void
    {
        $e = new EntryNotFoundException('Not found');
        $this->assertInstanceOf(NotFoundExceptionInterface::class, $e);
    }

    public function test_entry_not_found_has_message(): void
    {
        $e = new EntryNotFoundException('Service not found');
        $this->assertSame('Service not found', $e->getMessage());
    }

    // ── HttpException ───────────────────────────────────────────────────────

    public function test_http_exception_has_status_code(): void
    {
        $e = new HttpException(404, 'Sayfa bulunamadı');

        $this->assertSame(404, $e->getStatusCode());
        $this->assertSame('Sayfa bulunamadı', $e->getMessage());
    }

    public function test_http_exception_default_403(): void
    {
        $e = new HttpException(403);

        $this->assertSame(403, $e->getStatusCode());
    }

    public function test_http_exception_default_500(): void
    {
        $e = new HttpException(500, 'Sunucu hatası');

        $this->assertSame(500, $e->getStatusCode());
        $this->assertSame('Sunucu hatası', $e->getMessage());
    }
}
