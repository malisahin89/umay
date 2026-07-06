<?php

declare(strict_types=1);

namespace Core\Mail\Transport;

use Core\Contracts\MailTransport;
use Core\Facades\Log;
use Core\Mail\Mailable;

/**
 * LogTransport — the default mail transport.
 * LogTransport — varsayılan mail transport'u.
 *
 * Does NOT send real email; writes a summary to storage/logs (ideal for
 * development and testing — no SMTP needed).
 *
 * Gerçek e-posta GÖNDERMEZ; özeti storage/logs'a yazar (geliştirme ve test için
 * idealdir — SMTP gerekmez).
 */
class LogTransport implements MailTransport
{
    public function send(Mailable $mailable, array $to, array $cc = [], array $bcc = []): bool
    {
        $text = $mailable->getTextBody();
        $html = $mailable->getHtmlBody();

        Log::info('[Mail:log] '.get_class($mailable), [
            'from' => $mailable->getFrom(),
            'to' => $this->stringify($to),
            'cc' => $this->stringify($cc),
            'bcc' => $this->stringify($bcc),
            'subject' => $mailable->getSubject(),
            'preview' => mb_substr($text !== '' ? $text : strip_tags($html), 0, 100),
        ]);

        return true;
    }

    /**
     * Render a recipient list to a single safe string (CRLF stripped).
     * Alıcı listesini tek güvenli string'e çevir (CRLF temizlenmiş).
     *
     * @param  array<int, string|array{0: string, 1: string}>  $recipients
     */
    private function stringify(array $recipients): string
    {
        $list = [];
        foreach ($recipients as $r) {
            if (is_array($r)) {
                $name = str_replace(["\r", "\n"], '', $r[1]);
                $addr = str_replace(["\r", "\n"], '', $r[0]);
                $list[] = $name !== '' ? '"'.$name.'" <'.$addr.'>' : $addr;
            } else {
                $list[] = str_replace(["\r", "\n"], '', $r);
            }
        }

        return implode(', ', $list);
    }
}
