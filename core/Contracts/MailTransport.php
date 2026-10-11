<?php

declare(strict_types=1);

namespace Core\Contracts;

use Core\Mail\Mailable;

/**
 * MailTransport — the contract a mail "sender" must implement.
 * MailTransport — bir mail "gönderici"sinin uyması gereken sözleşme.
 *
 * The framework ships only a LogTransport (writes mail to the log). To send real
 * email, implement this interface and point a mailer's 'transport' at your class
 * in config/mail.php — without touching the core (same pattern as auth providers).
 *
 * Framework yalnızca LogTransport ile gelir (maili log'a yazar). Gerçek e-posta
 * göndermek için bu arayüzü implemente edin ve config/mail.php'de bir mailer'ın
 * 'transport'unu kendi sınıfınıza yönlendirin — çekirdeğe dokunmadan
 * (auth provider'larıyla aynı desen).
 */
interface MailTransport
{
    /**
     * Deliver the built Mailable to the given recipients.
     * Derlenmiş Mailable'ı verilen alıcılara teslim et.
     *
     * @param  array<int, string|array{0: string, 1: string}>  $to  "addr" or [addr, name]
     * @param  array<int, string>  $cc
     * @param  array<int, string>  $bcc
     */
    public function send(Mailable $mailable, array $to, array $cc = [], array $bcc = []): bool;
}
