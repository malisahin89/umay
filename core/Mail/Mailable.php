<?php

declare(strict_types=1);

namespace Core\Mail;

/**
 * Mailable base class — all mail classes extend this.
 * Mailable base sınıfı — tüm mail sınıfları bunu extend eder.
 *
 * // app/Mail/WelcomeMail.php:
 * class WelcomeMail extends Mailable
 * {
 *     public function __construct(private User $user) {}
 *
 *     public function build(): static
 *     {
 *         return $this
 *             ->subject('Hoş geldiniz!')
 *             ->from('noreply@site.com', 'Site Adı')
 *             ->view('emails/welcome', ['user' => $this->user]);
 *             // or ->text('Hello ' . $this->user->name) // veya ->text('Merhaba ' . $this->user->name)
 *     }
 * }
 *
 * // Sending / Gönderim:
 * Mail::to($user->email)->send(new WelcomeMail($user));
 * Mail::to($user->email)->queue(new WelcomeMail($user)); // writes to log driver // log driver'a yazar
 */
abstract class Mailable
{
    protected string $fromAddress = '';

    protected string $fromName = '';

    protected string $subjectLine = '';

    protected string $viewTemplate = '';

    protected array $viewData = [];

    protected string $textBody = '';

    protected array $attachments = [];

    protected array $headers = [];

    /**
     * The subclass implements this method.
     * Defines the mail content via $this->subject(...)->view(...) chain.
     *
     * Alt sınıf bu metodu implement eder.
     * $this->subject(...)->view(...) zinciriyle mail içeriğini tanımlar.
     */
    abstract public function build(): static;

    // ── Fluent builder methods ────────────────────────────────────────────────
    // ── Fluent builder metodları ──────────────────────────────────────────────

    public function from(string $address, string $name = ''): static
    {
        $this->fromAddress = $address;
        $this->fromName = $name;

        return $this;
    }

    public function subject(string $subject): static
    {
        $this->subjectLine = $subject;

        return $this;
    }

    /**
     * HTML content via view template.
     * View template ile HTML içerik.
     *
     * ->view('emails/welcome', ['user' => $user])
     * View file must be views/emails/welcome.php.
     * View dosyası views/emails/welcome.php olmalı.
     */
    public function view(string $template, array $data = []): static
    {
        $this->viewTemplate = $template;
        $this->viewData = $data;

        return $this;
    }

    /**
     * Plain text content (if there is no view, or as an alternative).
     * Düz metin içerik (view yoksa veya ek olarak).
     */
    public function text(string $body): static
    {
        $this->textBody = $body;

        return $this;
    }

    /**
     * Add file attachment.
     * Dosya eki ekle.
     *
     * ->attach('/path/to/file.pdf', 'rapor.pdf')
     */
    public function attach(string $path, string $name = ''): static
    {
        $this->attachments[] = ['path' => $path, 'name' => $name ?: basename($path)];

        return $this;
    }

    /**
     * Add extra header.
     * Ekstra header ekle.
     */
    public function header(string $key, string $value): static
    {
        $this->headers[$key] = $value;

        return $this;
    }

    // ── Getters (Used by Mailer) ──────────────────────────────────────────────
    // ── Getter'lar (Mailer tarafından kullanılır) ─────────────────────────────

    public function getFrom(): string
    {
        return $this->fromAddress
            ?: config('mail.from.address', $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@localhost');
    }

    public function getFromName(): string
    {
        return $this->fromName
            ?: config('mail.from.name', $_ENV['MAIL_FROM_NAME'] ?? 'Umay');
    }

    public function getSubject(): string
    {
        return $this->subjectLine ?: '(Konu Belirtilmedi)';
    }

    public function getHtmlBody(): string
    {
        if (! $this->viewTemplate) {
            return '';
        }

        return $this->renderView($this->viewTemplate, $this->viewData);
    }

    public function getTextBody(): string
    {
        return $this->textBody;
    }

    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function getExtraHeaders(): array
    {
        return $this->headers;
    }

    private function renderView(string $template, array $data): string
    {
        // Render template from views/ directory — with output buffering
        // views/ dizininden template render et — output buffering ile
        $viewPath = (defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__, 2))
            .'/views/'.ltrim($template, '/').'.php';

        if (! file_exists($viewPath)) {
            return '';
        }

        extract($data, EXTR_SKIP);
        ob_start();
        include $viewPath;

        return ob_get_clean();
    }
}
