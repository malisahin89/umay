<?php

declare(strict_types=1);

namespace Core;

/**
 * Fluent HTTP response builder.
 *
 * Kullanım:
 *   return response()->json(['users' => $users]);
 *   return response()->json($data, 201);
 *   return response('Tamam', 200)->header('X-Foo', 'bar');
 *   return response()->view('users/index', ['users' => $users]);
 *   return response()->download('/path/file.pdf', 'rapor.pdf');
 *
 * Route dispatch, ResponseBuilder döndürülürse send() otomatik çağırır.
 *
 * MİMARİ KISIT: send() yanıtı yazdıktan sonra RedirectException fırlatarak akışı
 * sonlandırır (index.php yakalar). Bu, middleware pipeline'ını da atlar — yani
 * "$next($request) SONRASI yanıtı değiştiren" (after-tarzı) middleware yazılamaz.
 * Tüm çekirdek middleware'ler bilinçli olarak before-tarzıdır (header'larını
 * $next'ten önce gönderir).
 *
 * ARCHITECTURAL CONSTRAINT: send() terminates the flow by throwing RedirectException
 * after writing the response (caught in index.php). This also unwinds the middleware
 * pipeline — an "after-style" middleware that modifies the response AFTER
 * $next($request) cannot exist. All core middleware are deliberately before-style
 * (they emit their headers before calling $next).
 */
class ResponseBuilder
{
    private string $body = '';

    private int $status = 200;

    private array $headers = [];

    /** When set, send() streams this file from disk instead of echoing $body. */
    private ?string $downloadPath = null;

    public function __construct(string $body = '', int $status = 200)
    {
        $this->body = $body;
        $this->status = $status;
    }

    // ── Fluent setters ───────────────────────────────────────────────────────

    public function status(int $code): static
    {
        $this->status = $code;

        return $this;
    }

    public function header(string $key, string $value): static
    {
        // Strip CR/LF from both name and value — header injection / response splitting
        // protection in case a header is built from user-derived data.
        // Ad ve değerden CR/LF temizle — header bir kullanıcı verisinden türetilirse
        // header injection / response splitting koruması.
        $this->headers[self::sanitizeHeader($key)] = self::sanitizeHeader($value);

        return $this;
    }

    /**
     * @param  array<string, string>  $headers
     */
    public function withHeaders(array $headers): static
    {
        foreach ($headers as $key => $value) {
            $this->headers[self::sanitizeHeader($key)] = self::sanitizeHeader($value);
        }

        return $this;
    }

    private static function sanitizeHeader(string $value): string
    {
        return str_replace(["\r", "\n"], '', $value);
    }

    // ── Body builders ────────────────────────────────────────────────────────

    public function json(mixed $data, int $status = 0): static
    {
        $this->body = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        $this->headers['Content-Type'] = 'application/json; charset=utf-8';
        if ($status > 0) {
            $this->status = $status;
        }

        return $this;
    }

    public function html(string $content, int $status = 0): static
    {
        $this->body = $content;
        if ($status > 0) {
            $this->status = $status;
        }

        return $this;
    }

    public function view(string $view, array $data = [], int $status = 0): static
    {
        ob_start();
        Container::getInstance()->make(View::class)->render($view, $data);
        $this->body = (string) ob_get_clean();
        if ($status > 0) {
            $this->status = $status;
        }

        return $this;
    }

    public function download(string $filePath, ?string $filename = null, int $status = 0): static
    {
        if (! is_file($filePath)) {
            throw new \RuntimeException("Dosya bulunamadı: $filePath");
        }

        $filename ??= basename($filePath);

        // Strip CR/LF/quote/backslash to prevent Content-Disposition header injection,
        // with an ASCII fallback plus an RFC 5987 filename* for non-ASCII names.
        // Content-Disposition header injection'a karşı CR/LF/tırnak/ters bölü temizle;
        // ASCII fallback + ASCII-dışı adlar için RFC 5987 filename* ekle.
        $clean = str_replace(["\r", "\n", '"', '\\'], '', $filename);
        $ascii = preg_replace('/[^\x20-\x7e]/', '_', $clean) ?: 'download';

        $this->headers['Content-Type'] = mime_content_type($filePath) ?: 'application/octet-stream';
        $this->headers['Content-Disposition'] = sprintf(
            "attachment; filename=\"%s\"; filename*=UTF-8''%s",
            $ascii,
            rawurlencode($clean)
        );
        $this->headers['Content-Length'] = (string) filesize($filePath);

        // Stream from disk in send() (readfile) instead of buffering the whole file.
        // Tüm dosyayı belleğe almak yerine send() içinde diskten akıt (readfile).
        $this->downloadPath = $filePath;
        if ($status > 0) {
            $this->status = $status;
        }

        return $this;
    }

    // ── Send ─────────────────────────────────────────────────────────────────

    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }

        // download() streams from disk; everything else echoes the buffered body.
        // download() diskten akıtır; diğer her şey tamponlanmış body'yi yazar.
        if ($this->downloadPath !== null) {
            readfile($this->downloadPath);
        } else {
            echo $this->body;
        }

        throw new RedirectException; // Çalışmayı sonlandır (index.php yakalar)
    }
}
