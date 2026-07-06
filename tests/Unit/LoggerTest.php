<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Logger;
use Tests\TestCase;

/**
 * Logger sistemi testleri.
 *
 * Dosya tabanlı log yazımı, seviye ayrımı (error/warning/info),
 * günlük rotasyon dosya adı ve log formatı doğrulanır.
 */
class LoggerTest extends TestCase
{
    private Logger $logger;

    private string $logPath;

    protected function setUp(): void
    {
        parent::setUp();
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['HTTP_USER_AGENT'] = 'UmayTest/1.0';

        $this->logger = new Logger;
        $this->logPath = BASE_PATH.'/storage/logs';
    }

    protected function tearDown(): void
    {
        // Bugünün log dosyasını sil
        $todayLog = $this->logPath.'/'.date('Y-m-d').'.log';
        if (file_exists($todayLog)) {
            unlink($todayLog);
        }
        parent::tearDown();
    }

    // ── Log dosyası oluşturma ───────────────────────────────────────────────

    public function test_info_creates_log_file(): void
    {
        $this->logger->info('Test info mesajı');

        $filename = $this->logPath.'/'.date('Y-m-d').'.log';
        $this->assertFileExists($filename);
    }

    // ── Log seviye formatı ──────────────────────────────────────────────────

    public function test_error_log_contains_error_level(): void
    {
        $this->logger->error('Kritik hata oluştu', ['code' => 500]);

        $content = file_get_contents($this->logPath.'/'.date('Y-m-d').'.log');
        $this->assertStringContainsString('ERROR', $content);
        $this->assertStringContainsString('Kritik hata oluştu', $content);
        $this->assertStringContainsString('"code":500', $content);
    }

    public function test_warning_log_contains_warning_level(): void
    {
        $this->logger->warning('Stok azaldı', ['product' => 'Widget']);

        $content = file_get_contents($this->logPath.'/'.date('Y-m-d').'.log');
        $this->assertStringContainsString('WARNING', $content);
        $this->assertStringContainsString('Stok azaldı', $content);
    }

    public function test_info_log_contains_info_level(): void
    {
        $this->logger->info('Kullanıcı giriş yaptı', ['user_id' => 5]);

        $content = file_get_contents($this->logPath.'/'.date('Y-m-d').'.log');
        $this->assertStringContainsString('INFO', $content);
        $this->assertStringContainsString('Kullanıcı giriş yaptı', $content);
    }

    // ── IP ve User-Agent bilgisi ────────────────────────────────────────────

    public function test_log_includes_ip_and_user_agent(): void
    {
        $this->logger->info('Test log');

        $content = file_get_contents($this->logPath.'/'.date('Y-m-d').'.log');
        $this->assertStringContainsString('IP: 127.0.0.1', $content);
        $this->assertStringContainsString('UmayTest/1.0', $content);
    }

    // ── Günlük rotasyon dosya adı ───────────────────────────────────────────

    public function test_log_file_uses_daily_rotation(): void
    {
        $this->logger->info('Rotasyon testi');

        $expectedFile = $this->logPath.'/'.date('Y-m-d').'.log';
        $this->assertFileExists($expectedFile);

        // Dosya adı tarih formatında mı?
        $this->assertMatchesRegularExpression('/\d{4}-\d{2}-\d{2}\.log$/', basename($expectedFile));
    }

    // ── Çoklu log yazımı ────────────────────────────────────────────────────

    public function test_multiple_logs_appended_to_same_file(): void
    {
        $this->logger->info('Birinci log');
        $this->logger->error('İkinci log');
        $this->logger->warning('Üçüncü log');

        $content = file_get_contents($this->logPath.'/'.date('Y-m-d').'.log');

        $this->assertStringContainsString('Birinci log', $content);
        $this->assertStringContainsString('İkinci log', $content);
        $this->assertStringContainsString('Üçüncü log', $content);

        // 3 satır olmalı
        $lines = array_filter(explode("\n", trim($content)));
        $this->assertCount(3, $lines);
    }
}
