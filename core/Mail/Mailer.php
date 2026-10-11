<?php

declare(strict_types=1);

namespace Core\Mail;

use Core\Contracts\MailTransport;
use Core\DebugBar;
use Core\Mail\Transport\LogTransport;

/**
 * Mail sending entry point.
 * Mail gönderme giriş noktası.
 *
 * // Sending / Gönderim:
 * Mail::to('user@example.com')->send(new WelcomeMail($user));
 * Mail::to('user@example.com', 'Ad Soyad')->send(new WelcomeMail($user));
 *
 * // Multiple recipients / Çoklu alıcı:
 * Mail::to(['a@b.com', 'c@d.com'])->send(new NewsletterMail());
 *
 * // CC / BCC:
 * Mail::to('user@example.com')->cc('manager@example.com')->send(new InvoiceMail());
 *
 * The actual delivery is delegated to a transport resolved from config/mail.php.
 * The framework ships only 'log' (LogTransport). For real delivery, implement
 * Core\Contracts\MailTransport and reference it in config/mail.php — like auth.
 *
 * Asıl teslimat config/mail.php'den çözülen bir transport'a devredilir. Framework
 * yalnızca 'log' (LogTransport) ile gelir. Gerçek gönderim için
 * Core\Contracts\MailTransport implemente edip config/mail.php'de gösterin — auth gibi.
 */
class Mailer
{
    /** @var array<int, string|array{0: string, 1: string}> */
    private array $to = [];

    /** @var array<int, string> */
    private array $cc = [];

    /** @var array<int, string> */
    private array $bcc = [];

    private function __construct() {}

    /** Set recipient // Alıcı belirle */
    public static function to(string|array $address, string $name = ''): static
    {
        /** @phpstan-ignore new.static (Mail extends Mailer — late static binding intentional) */
        $instance = new static;
        if (is_array($address)) {
            $instance->to = $address;
        } else {
            $instance->to[] = $name ? [$address, $name] : $address;
        }

        return $instance;
    }

    /** Add CC // CC ekle */
    public function cc(string|array $address): static
    {
        $this->cc = array_merge($this->cc, (array) $address);

        return $this;
    }

    /** Add BCC // BCC ekle */
    public function bcc(string|array $address): static
    {
        $this->bcc = array_merge($this->bcc, (array) $address);

        return $this;
    }

    /**
     * Build the mailable and deliver it via the configured transport.
     * Mailable'ı derle ve yapılandırılmış transport ile teslim et.
     *
     * @throws \RuntimeException If the configured transport is missing/invalid.
     */
    public function send(Mailable $mailable): bool
    {
        // call build() — subclass defines content // build() çağır — subclass içeriği tanımlar
        $mailable->build();

        $default = config('mail.default', 'log');
        $name = is_string($default) ? $default : 'log';
        $transport = $this->resolveTransport($name);

        if (class_exists(DebugBar::class) && DebugBar::isEnabled()) {
            DebugBar::addMail([
                'class' => get_class($mailable),
                'subject' => $mailable->getSubject(),
                'to' => implode(', ', array_map(
                    static fn ($r) => is_array($r) ? $r[0] : $r,
                    $this->to
                )),
                'from' => $mailable->getFrom(),
                'driver' => $name,
            ]);
        }

        return $transport->send($mailable, $this->to, $this->cc, $this->bcc);
    }

    /**
     * Resolve the transport for a mailer name from config/mail.php.
     * config/mail.php'den bir mailer adı için transport'u çöz.
     *
     * To plug your own sender, point the mailer's 'transport' at a class
     * implementing Core\Contracts\MailTransport.
     * Kendi göndericinizi takmak için mailer'ın 'transport'unu
     * Core\Contracts\MailTransport implemente eden bir sınıfa yönlendirin.
     */
    private function resolveTransport(string $name): MailTransport
    {
        $config = config("mail.mailers.{$name}", []);
        $class = is_array($config) ? ($config['transport'] ?? LogTransport::class) : LogTransport::class;

        if (! is_string($class) || ! class_exists($class)) {
            throw new \RuntimeException("Mail transport not found // bulunamadı (config/mail.php → mailers.{$name}).");
        }

        $transport = new $class(is_array($config) ? $config : []);

        if (! $transport instanceof MailTransport) {
            throw new \RuntimeException($class.' must implement Core\Contracts\MailTransport.');
        }

        return $transport;
    }
}
