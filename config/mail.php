<?php

declare(strict_types=1);

use Core\Mail\Transport\LogTransport;

/*
 * Mail is driver-based: you write Mailables and delivery is handled
 * by a "transport" selected here. The framework ships only the 'log' transport
 * (writes mail to storage/logs). For real delivery, implement
 * Core\Contracts\MailTransport and add it as a mailer below — without touching core.
 *
 * Mail driver tabanlıdır: Mailable'ları sen yazarsın, teslimatı burada
 * seçilen bir "transport" yapar. Framework yalnızca 'log' transport'u ile gelir
 * (maili storage/logs'a yazar). Gerçek gönderim için Core\Contracts\MailTransport
 * implemente edip aşağıya bir mailer olarak ekleyin — çekirdeğe dokunmadan.
 */
return [

    /*
     * Active mailer — a key from the 'mailers' list below.
     * Aktif mailer — aşağıdaki 'mailers' listesinden bir anahtar.
     */
    'default' => $_ENV['MAIL_MAILER'] ?? 'log',

    /*
     * Mailer definitions. Each has a 'transport' (a Core\Contracts\MailTransport
     * class) plus any extra keys that transport needs (host, port, ...).
     *
     * Mailer tanımları. Her biri bir 'transport' (Core\Contracts\MailTransport
     * sınıfı) ve o transport'un ihtiyaç duyduğu ek anahtarları (host, port, ...) içerir.
     */
    'mailers' => [
        'log' => [
            'transport' => LogTransport::class,
        ],

        // Example — your own transport (e.g. real SMTP), no core changes needed:
        // Örnek — kendi transport'unuz (örn. gerçek SMTP), çekirdek değişmeden:
        // 'smtp' => [
        //     'transport' => \App\Mail\Transport\SmtpTransport::class,
        //     'host'      => $_ENV['MAIL_HOST'] ?? '127.0.0.1',
        //     'port'      => (int) ($_ENV['MAIL_PORT'] ?? 587),
        //     'username'  => $_ENV['MAIL_USERNAME'] ?? '',
        //     'password'  => $_ENV['MAIL_PASSWORD'] ?? '',
        //     'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'tls',
        // ],
    ],

    /*
     * Default sender — used when a Mailable does not call ->from().
     * Varsayılan gönderen — Mailable ->from() çağırmazsa kullanılır.
     */
    'from' => [
        'address' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@localhost',
        'name' => $_ENV['MAIL_FROM_NAME'] ?? 'Umay',
    ],
];
