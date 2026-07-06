<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Contracts\MailTransport;
use Core\Mail\Mailable;
use Core\Mail\Mailer;
use Core\Mail\Transport\LogTransport;
use Tests\TestCase;

/**
 * Mailer testleri.
 *
 * Recipient zincirleme (to/cc/bcc), varsayılan log transport'u, transport
 * çözümleme (config tabanlı, takılabilir) ve header injection koruması.
 */
class MailerTest extends TestCase
{
    private function sampleMailable(): Mailable
    {
        return new class extends Mailable
        {
            public function build(): static
            {
                return $this->subject('Test Konusu')->text('Merhaba dünya');
            }
        };
    }

    private function prop(object $obj, string $name): mixed
    {
        $ref = new \ReflectionProperty($obj, $name);
        $ref->setAccessible(true);

        return $ref->getValue($obj);
    }

    private function todayLog(): string
    {
        return BASE_PATH.'/storage/logs/'.date('Y-m-d').'.log';
    }

    // ── to() / cc() / bcc() ──────────────────────────────────────────────────

    public function test_to_returns_mailer_instance(): void
    {
        $this->assertInstanceOf(Mailer::class, Mailer::to('user@example.com'));
    }

    public function test_to_accepts_array_of_recipients(): void
    {
        $mailer = Mailer::to(['a@b.com', 'c@d.com']);
        $this->assertCount(2, $this->prop($mailer, 'to'));
    }

    public function test_to_with_name_stores_array_pair(): void
    {
        $mailer = Mailer::to('user@example.com', 'Ali');
        $this->assertSame([['user@example.com', 'Ali']], $this->prop($mailer, 'to'));
    }

    public function test_cc_is_chainable(): void
    {
        $mailer = Mailer::to('user@example.com')->cc('manager@example.com');
        $this->assertContains('manager@example.com', $this->prop($mailer, 'cc'));
    }

    public function test_bcc_accepts_array(): void
    {
        $mailer = Mailer::to('user@example.com')->bcc(['a@example.com', 'b@example.com']);
        $this->assertCount(2, $this->prop($mailer, 'bcc'));
    }

    // ── Log transport (varsayılan) ───────────────────────────────────────────

    public function test_log_transport_implements_contract(): void
    {
        $this->assertInstanceOf(MailTransport::class, new LogTransport);
    }

    public function test_send_uses_log_transport_and_writes_log(): void
    {
        @unlink($this->todayLog());

        $result = Mailer::to('user@example.com', 'Ali')->send($this->sampleMailable());

        $this->assertTrue($result);
        $this->assertFileExists($this->todayLog());

        $content = file_get_contents($this->todayLog());
        $this->assertStringContainsString('[Mail:log]', $content);
        $this->assertStringContainsString('Test Konusu', $content);

        @unlink($this->todayLog());
    }

    // ── Header injection koruması ────────────────────────────────────────────

    public function test_recipient_crlf_is_stripped(): void
    {
        @unlink($this->todayLog());

        Mailer::to("attacker@evil.com\r\nBcc: victim@example.com")->send($this->sampleMailable());

        $content = file_get_contents($this->todayLog());
        // CR baytı kalmamalı; enjekte edilen "Bcc:" ayrı satıra dönüşemez (tek satırda birleşir)
        $this->assertStringNotContainsString("\r", $content);
        $this->assertStringContainsString('attacker@evil.comBcc: victim@example.com', $content);

        @unlink($this->todayLog());
    }
}
