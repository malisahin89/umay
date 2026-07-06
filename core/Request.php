<?php

declare(strict_types=1);

namespace Core;

/**
 * @phpstan-consistent-constructor
 */
class Request
{
    private array $get;

    private array $post;

    private array $files;

    private array $server;

    private array $cookies;

    private array $routeParams = [];

    public function __construct(array $get, array $post, array $files, array $server, array $cookies)
    {
        $this->get = $get;
        $this->post = $post;
        $this->files = $files;
        $this->server = $server;
        $this->cookies = $cookies;

        // JSON body auto-parsing (for API requests)
        // JSON body auto-parsing (API istekleri için)
        $contentType = $server['CONTENT_TYPE'] ?? $server['HTTP_CONTENT_TYPE'] ?? '';
        if (str_contains($contentType, 'application/json') && empty($post)) {
            // Bounded read so an oversized JSON body can't exhaust memory when PHP's
            // post_max_size is disabled/large. 8 MB ceiling; read one extra byte so an
            // over-limit body is detected and dropped rather than truncated then parsed.
            // Sınırlı okuma: post_max_size kapalı/büyükse aşırı büyük JSON gövdesi belleği
            // tüketemesin. 8 MB tavan; limiti aşan gövdeyi kesip ayrıştırmak yerine tespit
            // edip atmak için bir fazla bayt okunur.
            $maxBytes = 8 * 1024 * 1024;
            $rawBody = file_get_contents('php://input', false, null, 0, $maxBytes + 1);
            if ($rawBody !== false && $rawBody !== '' && strlen($rawBody) <= $maxBytes) {
                $json = json_decode($rawBody, true);
                if (is_array($json)) {
                    $this->post = array_merge($this->post, $json);
                }
            }
        }
    }

    // Create Request from superglobals
    // Superglobal'lardan Request oluştur
    public static function capture(): static
    {
        return new static($_GET, $_POST, $_FILES, $_SERVER, $_COOKIE);
    }

    // --- Internal state getters (for FormRequest, testing, etc.) ---
    // --- Dahili state getter'ları (FormRequest, test vb. için) ---

    public function getQuery(): array
    {
        return $this->get;
    }

    public function getPost(): array
    {
        return $this->post;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function getServer(): array
    {
        return $this->server;
    }

    public function getCookies(): array
    {
        return $this->cookies;
    }

    // --- Input access ---
    // --- Input erişimi ---

    // POST has priority, then GET
    // POST öncelikli, yoksa GET
    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }

    public function post(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $default;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->get[$key] ?? $default;
    }

    // Merge GET + POST
    // GET + POST birleşimi
    public function all(): array
    {
        return array_merge($this->get, $this->post);
    }

    public function only(array $keys): array
    {
        return array_intersect_key($this->all(), array_flip($keys));
    }

    public function except(array $keys): array
    {
        return array_diff_key($this->all(), array_flip($keys));
    }

    public function has(string $key): bool
    {
        return isset($this->post[$key]) || isset($this->get[$key]);
    }

    // has + not empty string
    // has + boş string değil
    public function filled(string $key): bool
    {
        $value = $this->input($key);

        return $value !== null && $value !== '';
    }

    // --- Files ---
    // --- Dosyalar ---

    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    public function hasFile(string $key): bool
    {
        $error = $this->files[$key]['error'] ?? null;

        // Multi-file input (name="photos[]") — PHP makes 'error' an array; true when
        // at least one file arrived successfully.
        // Çoklu dosya input'u (name="photos[]") — PHP 'error'ı dizi yapar; en az bir
        // dosya başarıyla geldiyse true.
        if (is_array($error)) {
            return in_array(UPLOAD_ERR_OK, $error, true);
        }

        return $error === UPLOAD_ERR_OK;
    }

    // --- HTTP info ---
    // --- HTTP bilgisi ---

    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    public function isMethod(string $method): bool
    {
        return $this->method() === strtoupper($method);
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }

    public function isAjax(): bool
    {
        return ($this->server['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }

    public function header(string $key, ?string $default = null): ?string
    {
        $serverKey = 'HTTP_'.strtoupper(str_replace('-', '_', $key));

        // Primary: the captured $_SERVER snapshot. Fallback: some SAPIs (Apache/CGI/
        // FastCGI) expose certain headers — notably Authorization — only under a REDIRECT_
        // prefix, or not in $_SERVER at all. Read them so Bearer-token
        // (api-auth) authentication keeps working there.
        // Birincil: yakalanan $_SERVER anlık görüntüsü. Geri dönüş: bazı SAPI'ler bazı
        // header'ları — özellikle Authorization — yalnızca REDIRECT_ önekiyle ya da
        // $_SERVER'da hiç sunmaz. Oku ki Bearer-token (api-auth) doğrulaması
        // orada da çalışsın.
        $value = $this->server[$serverKey] ?? $this->server['REDIRECT_'.$serverKey] ?? null;

        // Content-Type and Content-Length live in $_SERVER WITHOUT the HTTP_ prefix
        // (CGI/RFC 3875) — header('Content-Type') would otherwise always miss on
        // FPM/CGI even though the header was sent.
        // Content-Type ve Content-Length, $_SERVER'da HTTP_ öneksiz yaşar (CGI/RFC
        // 3875) — aksi halde header('Content-Type') FPM/CGI'da header gönderilmiş
        // olsa bile hep boş dönerdi.
        if ($value === null && in_array($serverKey, ['HTTP_CONTENT_TYPE', 'HTTP_CONTENT_LENGTH'], true)) {
            $value = $this->server[substr($serverKey, 5)] ?? null;
        }

        if ($value === null && function_exists('apache_request_headers')) {
            foreach (apache_request_headers() as $name => $headerValue) {
                if (strcasecmp($name, $key) === 0) {
                    $value = $headerValue;
                    break;
                }
            }
        }

        // $_SERVER / apache_request_headers values are strings; the is_string guard
        // keeps the return provably ?string (a non-string default is impossible now).
        // $_SERVER / apache_request_headers değerleri string'tir; is_string koruması
        // dönüşü kanıtlanabilir biçimde ?string tutar (artık string-dışı default olamaz).
        return is_string($value) ? $value : $default;
    }

    public function ip(): string
    {
        return getRealIP();
    }

    public function path(): string
    {
        return parse_url($this->server['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    }

    public function fullUrl(): string
    {
        $https = isset($this->server['HTTPS']) && $this->server['HTTPS'] !== 'off';
        $protocol = $https ? 'https' : 'http';

        return $protocol.'://'.($this->server['HTTP_HOST'] ?? 'localhost').($this->server['REQUEST_URI'] ?? '/');
    }

    // --- Route parameters ---
    // --- Route parametreleri ---

    public function route(string $key, mixed $default = null): mixed
    {
        return $this->routeParams[$key] ?? $default;
    }

    public function setRouteParams(array $params): void
    {
        $this->routeParams = $params;
    }

    public function getRouteParams(): array
    {
        return $this->routeParams;
    }

    // --- API Token ---

    /**
     * Extract token from Authorization: Bearer <token> header.
     * Returns null if not found.
     *
     * Authorization: Bearer <token> header'ından token'ı çıkart.
     * Yoksa null döner.
     */
    public function bearerToken(): ?string
    {
        $header = $this->header('Authorization');
        if (! $header) {
            return null;
        }

        if (str_starts_with($header, 'Bearer ')) {
            return trim(substr($header, 7));
        }

        return null;
    }

    /**
     * Is this an API request? — check for JSON Accept, AJAX, or Bearer token presence.
     * API isteği mi? — JSON Accept veya AJAX veya Bearer token var mı?
     */
    public function expectsJson(): bool
    {
        $accept = $this->header('Accept', '');

        return $this->isAjax()
        || str_contains($accept, 'application/json')
        || $this->bearerToken() !== null;
    }

    // --- Validation (delegate to existing validate() helper) ---
    // --- Doğrulama (mevcut validate() helper'ına delegate) ---

    public function validate(array $rules): ?array
    {
        return validate($this->all(), $rules);
    }

    // --- Sensitive input filtering ---
    // --- Hassas input filtreleme ---

    /**
     * Strip credential-like keys before flashing input back to the session (_old).
     * Passwords/tokens must never be persisted to the session store as plaintext
     * just so old() can repopulate a form — those fields are never re-filled anyway.
     *
     * Input'u session'a (_old) geri flash'lamadan önce kimlik-bilgisi benzeri
     * anahtarları çıkar. Parola/token'lar, sadece old() formu yeniden doldurabilsin
     * diye session deposuna düz metin yazılmamalıdır — bu alanlar zaten geri doldurulmaz.
     *
     * @param  array<array-key, mixed>  $input
     * @return array<array-key, mixed>
     */
    public static function exceptSensitive(array $input): array
    {
        foreach (array_keys($input) as $key) {
            if (preg_match('/pass|secret|token|otp|cvv|card|pin|api[_-]?key|authorization|credential/i', (string) $key)) {
                unset($input[$key]);
            }
        }

        return $input;
    }
}
