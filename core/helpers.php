<?php

declare(strict_types=1);

use Core\Auth;
use Core\Cache;
use Core\Container;
use Core\Contracts\Authenticatable;
use Core\Csrf;
use Core\Events\Dispatcher;
use Core\Events\Event;
use Core\Exceptions\HttpException;
use Core\Factory;
use Core\Mail\Mailer;
use Core\Paginator;
use Core\Redirect;
use Core\Request;
use Core\ResponseBuilder;
use Core\Route;
use Core\Validator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

// ── Routing ──────────────────────────────────────────────────────────────────

if (! function_exists('route')) {
    function route(string $name, array $params = []): string
    {
        return Route::url($name, $params);
    }
}

if (! function_exists('redirect')) {
    function redirect(string $urlOrName): void
    {
        // Store POST data if present; don't overwrite if controller already set it.
        // Strip credential-like fields so passwords/tokens never land in the session store.
        // POST verisi varsa sakla; controller daha önce set ettiyse ezme.
        // Parola/token gibi alanları çıkar ki session deposuna düz metin yazılmasın.
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && ! isset($_SESSION['_old'])) {
            $_SESSION['_old'] = Request::exceptSensitive($_POST);
        }

        // Named route OR raw URL — Route::url() now throws on unknown names, so
        // membership is checked with has() before treating the argument as a name.
        // İsimli route VEYA ham URL — Route::url() artık bilinmeyen isimde exception
        // fırlatıyor; argüman isim sayılmadan önce has() ile varlığı kontrol edilir.
        $url = Route::has($urlOrName) ? Route::url($urlOrName) : $urlOrName;

        Redirect::to($url);
    }
}

if (! function_exists('back')) {
    /**
     * Redirect to the previous page (HTTP_REFERER).
     * Bir önceki sayfaya (HTTP_REFERER) yönlendir.
     *
     * Falls back to homepage if referer is missing.
     * Referer yoksa ana sayfaya düşer.
     */
    function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        Redirect::to($referer);
    }
}

if (! function_exists('asset')) {
    function asset(string $path): string
    {
        return '/'.ltrim($path, '/');
    }
}

// ── Environment & Config ─────────────────────────────────────────────────────

if (! function_exists('env')) {
    /**
     * Get .env variable with type casting.
     * .env değişkenini tip dönüşümüyle al.
     */
    function env(string $key, mixed $default = null): mixed
    {
        // Bulunamayan anahtarlar ayrı izlenir; böylece açıkça "null" tanımlanmış bir
        // değişken null döner (Laravel semantiği), $default'a düşmez — `?? $default`
        // ikisini ayırt edemiyordu.
        // Missing keys are tracked separately so a variable explicitly set to "null"
        // resolves to null (Laravel semantics) instead of falling back to $default —
        // `?? $default` could not tell the two apart.
        static $cache = [];
        static $missing = [];

        if (isset($missing[$key])) {
            return $default;
        }

        if (array_key_exists($key, $cache)) {
            return $cache[$key];
        }

        $value = $_ENV[$key] ?? getenv($key);

        if ($value === false) {
            $missing[$key] = true;

            return $default;
        }

        $cache[$key] = match (strtolower((string) $value)) {
            'true', '(true)' => true,
            'false', '(false)' => false,
            'null', '(null)' => null,
            'empty', '(empty)' => '',
            default => $value,
        };

        return $cache[$key];
    }
}

if (! function_exists('config')) {
    /**
     * Read value from PHP files in config/ directory.
     * config/ dizinindeki PHP dosyalarından değer oku.
     *
     * Nested keys can be accessed using dot notation.
     * Nokta notasyonuyla iç içe anahtarlara ulaşılabilir.
     *
     * config('database.host')  → config/database.php → ['host' => ...]
     * config('app.debug')      → config/app.php      → ['debug' => ...]
     */
    function config(string $key, mixed $default = null): mixed
    {
        static $cache = [];

        $parts = explode('.', $key);
        $fileName = array_shift($parts);

        if (! array_key_exists($fileName, $cache)) {
            $path = (defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__))
                ."/config/{$fileName}.php";

            $cache[$fileName] = file_exists($path) ? (require $path) : [];
        }

        $value = $cache[$fileName];

        foreach ($parts as $part) {
            if (! is_array($value) || ! array_key_exists($part, $value)) {
                return $default;
            }
            $value = $value[$part];
        }

        return $value ?? $default;
    }
}

// ── Flash & Session ───────────────────────────────────────────────────────────

if (! function_exists('flash')) {
    function flash(string $key, ?string $message = null): mixed
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($message !== null) {
            $_SESSION['_flash'][$key] = $message;

            return null;
        }

        if (isset($_SESSION['_flash'][$key])) {
            $value = $_SESSION['_flash'][$key];
            unset($_SESSION['_flash'][$key]);

            return $value;
        }

        return null;
    }
}

if (! function_exists('old')) {
    function old(string $key, string $default = '', bool $escape = true): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $value = $_SESSION['_old'][$key] ?? $default;

        return $escape ? htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') : (string) $value;
    }
}

// ── Auth ──────────────────────────────────────────────────────────────────────

if (! function_exists('auth')) {
    /**
     * Returns the currently logged-in user.
     * Mevcut oturum açmış kullanıcıyı döndürür.
     *
     * Cached per request via Core\Auth (resolved from Container).
     * Core\Auth üzerinden request bazlı önbelleklenir (Container'dan çözümlenir).
     *
     * Returns the configured Authenticatable (e.g. App\Models\User), not a
     * hard-coded class — the concrete type is decided by config/auth.php.
     * Yapılandırılmış Authenticatable'ı (örn. App\Models\User) döndürür, sabit
     * kodlanmış bir sınıfı değil — somut tip config/auth.php ile belirlenir.
     */
    function auth(): ?Authenticatable
    {
        return Container::getInstance()->make(Auth::class)->user();
    }
}

// ── CSRF ──────────────────────────────────────────────────────────────────────

if (! function_exists('csrf')) {
    function csrf(): string
    {
        return Csrf::generate();
    }
}

if (! function_exists('csrf_token')) {
    function csrf_token(): string
    {
        return Csrf::generate();
    }
}

// ── Validation ────────────────────────────────────────────────────────────────

if (! function_exists('validate')) {
    /**
     * Validate data against rules.
     * Veriyi kurallara göre doğrula.
     *
     * Returns ['field' => ['message']] if there are errors, null otherwise.
     * Hata varsa ['field' => ['mesaj']] döner, yoksa null.
     *
     * Now delegated to Core\Validator class.
     * Artık Core\Validator sınıfına delegate edilmektedir.
     */
    function validate(array $data, array $rules, array $messages = []): ?array
    {
        $validator = Validator::make($data, $rules, $messages);

        return $validator->fails() ? $validator->errors() : null;
    }
}

// ── HTTP ──────────────────────────────────────────────────────────────────────

if (! function_exists('abort')) {
    /**
     * Throw an exception with a specific HTTP status code.
     * Belirli HTTP durum koduyla exception fırlat.
     *
     * ExceptionHandler will show the corresponding error page.
     * ExceptionHandler ilgili hata sayfasını gösterir.
     *
     * abort(403);
     * abort(404, 'Record not found. // Kayıt bulunamadı.');
     */
    function abort(int $code, string $message = ''): never
    {
        throw new HttpException($code, $message);
    }
}

if (! function_exists('response')) {
    /**
     * Returns a fluent ResponseBuilder instance.
     * Fluent ResponseBuilder örneği döndürür.
     *
     * return response()->json(['ok' => true]);
     * return response('Hello // Merhaba', 200)->header('X-Foo', 'bar');
     */
    function response(string $body = '', int $status = 200): ResponseBuilder
    {
        return new ResponseBuilder($body, $status);
    }
}

if (! function_exists('factory')) {
    /**
     * Create a model factory.
     * Model factory oluştur.
     *
     * factory(User::class)->make();             // Does not write to DB // DB'ye yazmaz
     * factory(User::class)->create();           // Saves to DB // DB'ye kaydeder
     * factory(User::class, 5)->create();        // 5 records // 5 kayıt
     * factory(User::class)->admin()->create();  // With state // State ile
     * factory(User::class)->state(['role' => 'admin'])->make();
     */
    function factory(string $modelClass, int $count = 1): Factory
    {
        return Factory::forModel($modelClass, $count);
    }
}

if (! function_exists('event')) {
    /**
     * Dispatch an event.
     * Event dispatch et.
     *
     * event(new UserRegistered($user));
     * event(new PostPublished($post));
     */
    function event(Event $event): Event
    {
        return Dispatcher::dispatch($event);
    }
}

if (! class_exists('Mail')) {
    /**
     * Mail facade alias.
     * Mail::to('user@example.com')->send(new WelcomeMail($user));
     */
    class Mail extends Mailer {}
}

if (! function_exists('paginator')) {
    /**
     * Create a Paginator from an Eloquent LengthAwarePaginator or raw data.
     * Eloquent LengthAwarePaginator veya raw veriden Paginator oluştur.
     *
     * // From Eloquent paginate() result / Eloquent paginate() sonucundan:
     * $users = User::paginate(15);
     * echo paginator($users)->links();
     *
     * // Manual / Manuel:
     * echo paginator($items, $total, $perPage)->links();
     */
    function paginator(mixed $items, int $total = 0, int $perPage = 15): Paginator
    {
        if ($items instanceof LengthAwarePaginator) {
            return Paginator::fromEloquent($items);
        }
        $page = (int) ($_GET['page'] ?? 1);

        return Paginator::make($items, $total, $perPage, $page);
    }
}

if (! function_exists('method_field')) {
    /**
     * Generates a hidden input for PUT/PATCH/DELETE method spoofing in HTML forms.
     * HTML formlarda PUT/PATCH/DELETE method spoofing için hidden input üretir.
     *
     * <?= method_field('PUT') ?>
     * → <input type="hidden" name="_method" value="PUT">
     */
    function method_field(string $method): string
    {
        return '<input type="hidden" name="_method" value="'.htmlspecialchars(strtoupper($method), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8').'">';
    }
}

// ── Cache ─────────────────────────────────────────────────────────────────────

if (! function_exists('cache')) {
    /**
     * Cache helper — resolved from Container.
     * Cache helper — Container'dan çözümlenir.
     *
     * cache()                    → Cache instance
     * cache('key')               → Cache::get('key')
     * cache('key', 'val', 3600)  → Cache::set('key', 'val', 3600)
     */
    function cache(?string $key = null, mixed $value = null, ?int $ttl = null): mixed
    {
        $cache = Container::getInstance()->make(Cache::class);

        if ($key === null) {
            return $cache;
        }
        if ($value === null) {
            return $cache->get($key);
        }

        // $ttl null ise Cache::set() config('cache.default_ttl')'i kullanır
        // When $ttl is null, Cache::set() falls back to config('cache.default_ttl')
        return $cache->set($key, $value, $ttl);
    }
}

// ── Collections ───────────────────────────────────────────────────────────────

if (! function_exists('collect')) {
    /**
     * Create an Illuminate Collection.
     * Illuminate Collection oluştur.
     *
     * Comes pre-installed with illuminate/database.
     * illuminate/database ile birlikte zaten yüklü gelir.
     */
    function collect(mixed $items = []): Collection
    {
        return new Collection($items);
    }
}

// ── String Helpers ────────────────────────────────────────────────────────────

if (! function_exists('str_slug')) {
    /**
     * Generates a URL-safe slug by converting Turkish characters.
     * Türkçe karakterleri dönüştürerek URL-safe slug üretir.
     *
     * str_slug('Merhaba Dünya')  → 'merhaba-dunya'
     */
    function str_slug(string $text, string $separator = '-'): string
    {
        $tr = ['ç', 'ğ', 'ı', 'ö', 'ş', 'ü', 'Ç', 'Ğ', 'İ', 'Ö', 'Ş', 'Ü'];
        $en = ['c', 'g', 'i', 'o', 's', 'u', 'c', 'g', 'i', 'o', 's', 'u'];

        $text = str_replace($tr, $en, $text);
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/[^a-z0-9\s\-]/u', '', $text);
        $text = preg_replace('/[\s\-]+/', $separator, $text);

        return trim($text, $separator);
    }
}

if (! function_exists('str_limit')) {
    /**
     * Cut text at a specific character count.
     * Metni belirli karakter sayısında kes.
     *
     * str_limit('Merhaba Dünya', 7)  → 'Merhaba...'
     */
    function str_limit(string $text, int $limit = 100, string $end = '...'): string
    {
        if (mb_strlen($text, 'UTF-8') <= $limit) {
            return $text;
        }

        return rtrim(mb_substr($text, 0, $limit, 'UTF-8')).$end;
    }
}

// ── Date / Time ───────────────────────────────────────────────────────────────

if (! function_exists('now')) {
    /**
     * Returns the current date and time.
     * Şu anki tarih-saati döndürür.
     *
     * now()              → '2026-03-01 14:30:00'
     * now('d.m.Y')       → '01.03.2026'
     */
    function now(string $format = 'Y-m-d H:i:s'): string
    {
        return date($format);
    }
}

if (! function_exists('today')) {
    /**
     * Returns today's date.
     * Bugünün tarihini döndürür.
     *
     * today()            → '2026-03-01'
     * today('d.m.Y')     → '01.03.2026'
     */
    function today(string $format = 'Y-m-d'): string
    {
        return date($format);
    }
}

// ── Misc ──────────────────────────────────────────────────────────────────────

if (! function_exists('isCloudflareIP')) {
    /**
     * Checks if the given IP address belongs to Cloudflare IP blocks.
     * Verilen IP adresinin Cloudflare IP bloklarına ait olup olmadığını kontrol eder.
     *
     * https://www.cloudflare.com/ips-v4 & https://www.cloudflare.com/ips-v6
     */
    function isCloudflareIP(string $ip): bool
    {
        // Ranges are config-overridable (config/app.php → 'cloudflare_ipv4' /
        // 'cloudflare_ipv6') so operators can track Cloudflare's published list without
        // editing core when CF adds/removes a block. They fall back to this snapshot
        // (April 2026). https://www.cloudflare.com/ips
        // Aralıklar config'den ezilebilir (config/app.php → 'cloudflare_ipv4' /
        // 'cloudflare_ipv6'); böylece CF bir blok ekler/çıkarırsa operatör core'u
        // düzenlemeden yayınlanan listeyi güncel tutar. Bu anlık görüntüye (Nisan 2026)
        // geri düşer.
        $cfRangesV4 = config('app.cloudflare_ipv4');
        if (! is_array($cfRangesV4) || $cfRangesV4 === []) {
            $cfRangesV4 = [
                '173.245.48.0/20',
                '103.21.244.0/22',
                '103.22.200.0/22',
                '103.31.4.0/22',
                '141.101.64.0/18',
                '108.162.192.0/18',
                '190.93.240.0/20',
                '188.114.96.0/20',
                '197.234.240.0/22',
                '198.41.128.0/17',
                '162.158.0.0/15',
                '104.16.0.0/13',
                '104.24.0.0/14',
                '172.64.0.0/13',
                '131.0.72.0/22',
            ];
        }

        $cfRangesV6 = config('app.cloudflare_ipv6');
        if (! is_array($cfRangesV6) || $cfRangesV6 === []) {
            $cfRangesV6 = [
                '2400:cb00::/32',
                '2606:4700::/32',
                '2803:f800::/32',
                '2405:b500::/32',
                '2405:8100::/32',
                '2a06:98c0::/29',
                '2c0f:f248::/32',
            ];
        }

        // Check if IPv6
        // IPv6 mı kontrol et
        $isIPv6 = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
        $ranges = $isIPv6 ? $cfRangesV6 : $cfRangesV4;

        foreach ($ranges as $range) {
            if (is_string($range) && ipInCidr($ip, $range)) {
                return true;
            }
        }

        return false;
    }
}

if (! function_exists('ipInCidr')) {
    /**
     * Checks if the IP address is within the specified CIDR range.
     * IP adresinin belirtilen CIDR aralığında olup olmadığını kontrol eder.
     *
     * Supports both IPv4 and IPv6.
     * Hem IPv4 hem IPv6 destekler.
     */
    function ipInCidr(string $ip, string $cidr): bool
    {
        // No '/' → not CIDR notation. Fall back to exact-IP comparison instead of
        // destructuring into $bits = 0, which would build an all-zero mask and make
        // EVERY address match (a config entry like '1.2.3.4' would turn
        // isCloudflareIP() into "always true" → CF-Connecting-IP spoofable).
        // '/' yoksa CIDR gösterimi değildir. $bits = 0'a düşmek yerine birebir IP
        // karşılaştırmasına dön; aksi halde maske tamamen sıfır olur ve HER adres
        // eşleşirdi ('1.2.3.4' gibi bir config girdisi isCloudflareIP()'ı "hep true"
        // yapar → CF-Connecting-IP spoof edilebilirdi).
        if (! str_contains($cidr, '/')) {
            return $ip === $cidr;
        }

        [$subnet, $bits] = explode('/', $cidr, 2);
        $bits = (int) $bits;

        $ipBin = inet_pton($ip);
        $subBin = inet_pton($subnet);

        if ($ipBin === false || $subBin === false) {
            return false;
        }

        // Different address families (IPv4 vs IPv6)
        // Farklı adres aileleri (IPv4 vs IPv6)
        if (strlen($ipBin) !== strlen($subBin)) {
            return false;
        }

        // Create bit mask
        // Bit maskesi oluştur
        $byteLen = strlen($ipBin); // IPv4=4, IPv6=16
        $mask = str_repeat("\xff", (int) ($bits / 8));

        $remainBits = $bits % 8;
        if ($remainBits > 0) {
            $mask .= chr(0xFF << (8 - $remainBits) & 0xFF);
        }

        $mask = str_pad($mask, $byteLen, "\x00");

        return ($ipBin & $mask) === ($subBin & $mask);
    }
}

if (! function_exists('isSecure')) {
    /**
     * Is the current request served over HTTPS?
     * Mevcut istek HTTPS üzerinden mi sunuluyor?
     *
     * Behind a TLS-terminating reverse proxy $_SERVER['HTTPS'] is empty, which made
     * session/remember-me cookies lose their Secure flag, suppressed HSTS and looped
     * the production HTTPS redirect. X-Forwarded-Proto is honoured ONLY when the
     * request arrives from a trusted proxy (config app.trusted_proxies) or a genuine
     * Cloudflare IP block — the same trust model as getRealIP() — so a direct client
     * cannot spoof the scheme.
     *
     * TLS sonlandıran reverse proxy arkasında $_SERVER['HTTPS'] boş kalır; bu yüzden
     * session/remember-me cookie'leri Secure bayrağını kaybediyor, HSTS gönderilmiyor
     * ve production HTTPS yönlendirmesi döngüye giriyordu. X-Forwarded-Proto YALNIZCA
     * istek güvenilir bir proxy'den (config app.trusted_proxies) veya gerçek bir
     * Cloudflare IP bloğundan geldiğinde dikkate alınır — getRealIP() ile aynı güven
     * modeli — doğrudan gelen bir client şemayı sahteleyemez.
     */
    function isSecure(): bool
    {
        if (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            return true;
        }

        $proto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? null;
        if (! is_string($proto) || $proto === '') {
            return false;
        }

        $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? '';
        $remoteAddr = is_string($remoteAddr) ? $remoteAddr : '';

        $trustedProxies = config('app.trusted_proxies', ['127.0.0.1', '::1']);
        if (! is_array($trustedProxies) || $trustedProxies === []) {
            $trustedProxies = ['127.0.0.1', '::1'];
        }

        $trusted = in_array($remoteAddr, $trustedProxies, true)
            || (filter_var($remoteAddr, FILTER_VALIDATE_IP) !== false && isCloudflareIP($remoteAddr));

        if (! $trusted) {
            return false;
        }

        // Proxy chains may send a comma-separated list — the first value is the
        // client-facing scheme.
        // Proxy zincirleri virgüllü liste gönderebilir — ilk değer istemciye bakan şemadır.
        return strtolower(trim(explode(',', $proto, 2)[0])) === 'https';
    }
}

if (! function_exists('getRealIP')) {
    /**
     * Securely retrieves the real IP address.
     * Gerçek IP adresini güvenli bir şekilde alır.
     *
     * Returns the correct IP even behind a proxy/load balancer.
     * Proxy/load balancer arkasında bile doğru IP'yi döndürür.
     *
     * Security: The CF-Connecting-IP header is only accepted if the request
     * comes from a genuine Cloudflare IP block.
     * Güvenlik: CF-Connecting-IP başlığı yalnızca istek gerçek
     * Cloudflare IP bloklarından geliyorsa kabul edilir.
     */
    function getRealIP(): string
    {
        $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        // Trusted proxies are config-driven (config/app.php → 'trusted_proxies',
        // env TRUSTED_PROXIES). X-Forwarded-For is honoured ONLY when the request
        // arrives from one of these addresses — otherwise the header is ignored,
        // so a direct client cannot spoof its IP.
        // Güvenilir proxy'ler config'den gelir (config/app.php → 'trusted_proxies',
        // env TRUSTED_PROXIES). X-Forwarded-For YALNIZCA istek bu adreslerden birinden
        // geldiğinde dikkate alınır — aksi halde header yok sayılır; doğrudan gelen
        // bir client IP'sini sahteleyemez.
        $trustedProxies = config('app.trusted_proxies', ['127.0.0.1', '::1']);
        if (! is_array($trustedProxies) || $trustedProxies === []) {
            $trustedProxies = ['127.0.0.1', '::1'];
        }

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) &&
            in_array($remoteAddr, $trustedProxies, true)) {
            // Walk the forwarded chain right-to-left and return the first address that is
            // NOT itself a trusted proxy — i.e. the closest untrusted hop, the one the last
            // trusted proxy appended. Taking the LEFTMOST entry instead would let a client
            // spoof its IP by sending its own X-Forwarded-For (a proxy only *appends* to the
            // right). Uses right-to-left forwarded chain resolution.
            //
            // Forwarded zincirini sağdan sola yürü ve kendisi güvenilir proxy OLMAYAN ilk
            // adresi döndür — yani son güvenilir proxy'nin eklediği, sunucuya en yakın
            // güvenilmeyen adım. En SOLDAKİni almak, client'ın kendi X-Forwarded-For'unu
            // göndererek IP'sini sahtelemesine izin verirdi (proxy yalnızca sağa *ekler*).
            // Forwarded zinciri sağdan sola yürüterek çözümlenir.
            $forwarded = array_map('trim', explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
            for ($i = count($forwarded) - 1; $i >= 0; $i--) {
                $candidate = $forwarded[$i];
                if (! filter_var($candidate, FILTER_VALIDATE_IP)) {
                    continue;
                }
                if (in_array($candidate, $trustedProxies, true)) {
                    continue; // chained trusted proxy — keep walking left // zincirli güvenilir proxy — sola devam
                }

                return $candidate;
            }
        }

        // Cloudflare header: ONLY trust if the request comes from a real CF IP block
        // Cloudflare başlığı: SADECE istek gerçek CF IP bloğundan geliyorsa güven
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP']) &&
            filter_var($remoteAddr, FILTER_VALIDATE_IP) &&
            isCloudflareIP($remoteAddr)) {
            $cfIP = $_SERVER['HTTP_CF_CONNECTING_IP'];
            if (filter_var($cfIP, FILTER_VALIDATE_IP)) {
                return $cfIP;
            }
        }

        return $remoteAddr;
    }
}
