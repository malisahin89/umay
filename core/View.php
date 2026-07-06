<?php

declare(strict_types=1);

namespace Core;

use Core\Profiler\Profiler;
use League\Plates\Engine;

/**
 * View — Plates template engine wrapper.
 * View — Plates template motoru sarmalayıcısı.
 *
 * Instance-based architecture — resolved from Container via Facade.
 * Instance tabanlı mimari — Facade aracılığıyla Container'dan çözümlenir.
 *
 * Usage via Facade / Facade ile kullanım:
 *   View::render('users/index', ['users' => $users]);
 */
class View
{
    /** @var Engine|null Singleton Plates engine instance — created once, reused across renders */
    private ?Engine $engine = null;

    /**
     * Flash values (success/error) consumed once by render() and remembered so the
     * flash() view helper returns the SAME value the $success/$error globals were
     * built from — otherwise render() would consume them first and $this->flash()
     * would always be empty.
     *
     * render() tarafından bir kez tüketilen flash değerleri (success/error); flash()
     * view helper'ı, $success/$error global'lerinin türetildiği AYNI değeri döndürsün
     * diye saklanır — aksi halde render() onları önce tüketir ve $this->flash() hep boş kalırdı.
     *
     * @var array<string, mixed>
     */
    private array $consumedFlash = [];

    /**
     * Data shared with EVERY view and layout via share(). Filled early in the
     * request (middleware / provider boot) and merged into the layout bridge in
     * render(). View is a container singleton, so values put here before render()
     * are still present when it runs; PHP's per-request lifecycle keeps requests
     * isolated — no static needed.
     *
     * share() ile HER view'e ve layout'a paylaşılan veri. İsteğin başında
     * (middleware / provider boot) doldurulur, render()'daki layout köprüsüne
     * merge edilir. View container'da singleton olduğundan render()'dan önce
     * konan değerler çalıştığında hâlâ yerindedir; PHP'nin istek-başına yaşam
     * döngüsü istekleri izole tutar — static gerekmez.
     *
     * @var array<string, mixed>
     */
    private array $shared = [];

    /**
     * Keys pushed into the Plates engine by the PREVIOUS render() call. Plates'
     * addData() only merges (there is no removeData), so a second render in the
     * same request (error page, response()->view() twice) would inherit the first
     * page's variables. Stale keys are nulled out on the next render.
     *
     * ÖNCEKİ render() çağrısının Plates engine'e bastığı anahtarlar. Plates'in
     * addData()'sı yalnızca merge eder (removeData yok); aynı istekteki ikinci
     * render (hata sayfası, iki kez response()->view()) ilk sayfanın değişkenlerini
     * miras alırdı. Bayat anahtarlar sonraki render'da null'a çekilir.
     *
     * @var array<array-key, true>
     */
    private array $lastRenderKeys = [];

    /**
     * Share data with every view and layout.
     * Veriyi her view ve layout ile paylaş.
     *
     * Usage / Kullanım:
     *   View::share('langUrls', $urls);
     *   View::share(['siteName' => 'Umay', 'locale' => $locale]);
     *
     * Precedence / Öncelik: shared < page $data < framework globals (errors, user_id…).
     *
     * @param  string|array<string, mixed>  $key
     */
    public function share(string|array $key, mixed $value = null): void
    {
        if (is_array($key)) {
            $this->shared = array_merge($this->shared, $key);

            return;
        }

        $this->shared[$key] = $value;
    }

    /**
     * Returns the shared Plates Engine instance.
     * Template fonksiyonları yalnızca bir kez register edilir.
     */
    private function engine(): Engine
    {
        if ($this->engine === null) {
            $this->engine = new Engine(__DIR__.'/../views');

            // Register partials folder for component-like includes
            // Component benzeri include'lar için partials klasörünü kaydet
            // Usage / Kullanım: $this->insert('partials::alert', ['type' => 'success', 'message' => '...'])
            $partialsDir = __DIR__.'/../views/partials';
            if (is_dir($partialsDir)) {
                $this->engine->addFolder('partials', $partialsDir);
            }

            // ── Security Helpers ─────────────────────────────────────────

            // CSRF hidden input field
            // CSRF gizli input alanı
            // Usage: $this->csrf()
            $this->engine->registerFunction('csrf', function () {
                return '<input type="hidden" name="csrf_token" value="'.Csrf::generate().'">';
            });

            // CSRF token value only
            // Sadece CSRF token değeri
            // Usage: $this->csrf_token()
            $this->engine->registerFunction('csrf_token', function () {
                return Csrf::generate();
            });

            // Escape function for XSS protection
            // XSS koruması için escape fonksiyonu
            // Usage: $this->e($user->name)
            $this->engine->registerFunction('e', function ($string) {
                return htmlspecialchars($string ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            });

            // HTTP method spoofing for PUT/PATCH/DELETE forms
            // PUT/PATCH/DELETE formları için HTTP method spoofing
            // Usage: $this->method('PUT')
            $this->engine->registerFunction('method', function (string $method) {
                return '<input type="hidden" name="_method" value="'.htmlspecialchars(strtoupper($method), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8').'">';
            });

            // CSP nonce attribute
            // CSP nonce niteliği
            // Usage: $this->nonce()
            $this->engine->registerFunction('nonce', function () {
                return Csp::nonce();
            });

            // ── Routing & Asset Helpers ──────────────────────────────────

            // Named route URL (XSS safe)
            // İsimli route URL'i (XSS güvenli)
            // Usage: $this->route('user.profile', ['id' => 1])
            $this->engine->registerFunction('route', function (string $name, array $params = []) {
                return htmlspecialchars(route($name, $params), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            });

            // Legacy alias for route()
            // route() için eski alias
            $this->engine->registerFunction('url', function ($name) {
                return htmlspecialchars(route($name), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            });

            // Asset URL helper
            // Asset URL yardımcısı
            // Usage: $this->asset('css/app.css')
            $this->engine->registerFunction('asset', function (string $path) {
                return htmlspecialchars(asset($path), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            });

            // ── Form & Input Helpers ─────────────────────────────────────

            // Old input value (XSS protected) — old() itself escapes by default, so a
            // second htmlspecialchars() here would double-encode (O'Reilly → O&#039;Reilly
            // rendered literally in the form field).
            // Eski input değeri (XSS korumalı) — old() varsayılan olarak zaten escape
            // eder; burada ikinci bir htmlspecialchars() çift kodlardı (O'Reilly formda
            // literal O&#039;Reilly görünürdü).
            // Usage: $this->old('email', 'default@mail.com')
            $this->engine->registerFunction('old', function ($key, $default = '') {
                return old($key, $default);
            });

            // ── Auth Helpers ─────────────────────────────────────────────

            // Get current authenticated user (null if guest)
            // Oturum açmış kullanıcıyı al (misafirse null)
            // Usage: $this->auth() — returns User|null
            $this->engine->registerFunction('auth', function () {
                return auth();
            });

            // Check if user is a guest (not logged in)
            // Kullanıcının misafir olup olmadığını kontrol et
            // Usage: $this->guest() — returns bool
            $this->engine->registerFunction('guest', function () {
                return auth() === null;
            });

            // ── Config & Environment Helpers ─────────────────────────────

            // Read config value
            // Config değeri oku
            // Usage: $this->config('app.name')
            $this->engine->registerFunction('config', function (string $key, mixed $default = null) {
                return config($key, $default);
            });

            // Application name shortcut
            // Uygulama adı kısayolu
            // Usage: $this->app_name()
            $this->engine->registerFunction('app_name', function () {
                return htmlspecialchars(config('app.name', 'Umay'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            });

            // ── Date & Time Helpers ──────────────────────────────────────

            // Current date/time
            // Şu anki tarih/saat
            // Usage: $this->now('d.m.Y')
            $this->engine->registerFunction('now', function (string $format = 'Y-m-d H:i:s') {
                return date($format);
            });

            // ── Conditional CSS Class Helper ─────────────────────────────

            // Conditional CSS class builder (like Alpine.js / Blade @class)
            // Koşullu CSS class oluşturucu
            // Usage: $this->class_if(['active' => $isActive, 'text-bold' => true])
            $this->engine->registerFunction('class_if', function (array $classes) {
                $result = [];
                foreach ($classes as $class => $condition) {
                    if (is_int($class)) {
                        $result[] = $condition; // Always include if no condition
                    } elseif ($condition) {
                        $result[] = $class;
                    }
                }

                return implode(' ', $result);
            });

            // ── Validation Error Helpers ─────────────────────────────────

            // Check if a field has validation errors
            // Bir alanın validasyon hatası olup olmadığını kontrol et
            // Usage: $this->has_error('email') — returns bool
            $this->engine->registerFunction('has_error', function (string $field) {
                $errors = $_SESSION['_flash_errors'] ?? [];

                return isset($errors[$field]);
            });

            // Get validation error message for a field
            // Bir alan için validasyon hata mesajını al
            // Usage: $this->error('email')
            $this->engine->registerFunction('error', function (string $field) {
                $errors = $_SESSION['_flash_errors'] ?? [];
                if (isset($errors[$field])) {
                    $msg = is_array($errors[$field]) ? $errors[$field][0] : $errors[$field];

                    return htmlspecialchars($msg, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                }

                return '';
            });

            // ── Flash Message Helper ─────────────────────────────────────

            // Get flash message
            // Flash mesajı al
            // Usage: $this->flash('success')
            $this->engine->registerFunction('flash', function (string $key) {
                // Return the value render() already consumed (success/error), so this
                // helper and the $success/$error globals stay consistent. Other keys
                // fall through to the global flash() (read-and-clear).
                // render()'in zaten tükettiği değeri (success/error) döndür; böylece bu
                // helper ile $success/$error global'leri tutarlı kalır. Diğer anahtarlar
                // global flash()'e düşer (oku-ve-temizle).
                return array_key_exists($key, $this->consumedFlash)
                    ? $this->consumedFlash[$key]
                    : flash($key);
            });

            // ── Debug Helper ─────────────────────────────────────────────

            // Dump and die (development only)
            // Dump ve die (sadece geliştirme)
            // Usage: $this->dd($variable)
            $this->engine->registerFunction('dd', function (mixed $value) {
                echo '<pre style="background:#1e1e2e;color:#cdd6f4;padding:16px;border-radius:8px;font-size:13px;overflow:auto">';
                var_dump($value);
                echo '</pre>';
                exit;
            });
        }

        return $this->engine;
    }

    public function render(string $template, array $data = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $templates = $this->engine();

        // PRG: read validation errors stored in session (after redirect).
        // NOTE: do NOT unset here — the input partial and has_error()/error() helpers
        // read $_SESSION['_flash_errors'] DURING rendering. It is cleared AFTER render
        // (alongside _old) so it survives exactly one render, like flashed old input.
        // PRG: session'da saklanmış validasyon hatalarını oku (redirect sonrası).
        // NOT: burada unset ETME — input partial'ı ve has_error()/error() helper'ları
        // render SIRASINDA $_SESSION['_flash_errors']'ı okur. Render'dan SONRA (_old ile
        // birlikte) temizlenir; böylece flash'lanmış eski input gibi tam bir render yaşar.
        $sessionErrors = ! empty($_SESSION['_flash_errors']) ? $_SESSION['_flash_errors'] : [];

        // Consume flash success/error ONCE and remember them so the flash() view helper
        // returns the same value (see $consumedFlash). The array_key_exists guard keeps
        // the value alive across a SECOND render in the same request (e.g. an error
        // page) — re-reading flash() there would overwrite it with null.
        // Flash success/error'u BİR kez tüket ve sakla; flash() view helper'ı aynı değeri
        // döndürsün diye ($consumedFlash'a bakın). array_key_exists koruması, aynı
        // istekteki İKİNCİ render'da da (örn. hata sayfası) değeri yaşatır — orada
        // flash()'ı yeniden okumak değeri null ile ezerdi.
        if (! array_key_exists('success', $this->consumedFlash)) {
            $this->consumedFlash['success'] = flash('success');
            $this->consumedFlash['error'] = flash('error');
        }

        // Bridge shared globals and the caller's page data into the layout scope,
        // then overlay the framework-computed globals. The core stays app-agnostic:
        // whatever a controller passes to view() (description, langUrls, …) reaches
        // the layout without the core naming those keys. Precedence: shared < page
        // data < framework globals — a page may override a shared value, nothing
        // overrides errors/user_id.
        // Paylaşılan global'leri ve çağıranın sayfa verisini layout kapsamına
        // köprüle, üzerine framework'ün hesapladığı global'leri bindir. Çekirdek
        // uygulamadan bağımsız kalır: controller view()'a ne gönderirse
        // (description, langUrls, …) çekirdek o isimleri bilmeden layout'a ulaşır.
        // Öncelik: shared < sayfa verisi < framework global'leri — sayfa paylaşılanı
        // ezebilir, errors/user_id'yi hiçbir şey ezemez.
        $merged = array_merge($this->shared, $data, [
            'title' => $data['title'] ?? '',
            'errors' => ! empty($data['errors']) ? $data['errors'] : $sessionErrors,
            'success' => $this->consumedFlash['success'],
            'error' => $this->consumedFlash['error'],
            'user_id' => $_SESSION['user_id'] ?? null,
        ]);

        // Null out keys left over from a previous render in this request so a second
        // template doesn't inherit the first page's variables (see $lastRenderKeys).
        // Bu istekteki önceki render'dan kalan anahtarları null'a çek; ikinci template
        // ilk sayfanın değişkenlerini miras almasın (bkz. $lastRenderKeys).
        $currentKeys = array_fill_keys(array_keys($merged), true);
        foreach (array_keys(array_diff_key($this->lastRenderKeys, $currentKeys)) as $staleKey) {
            $merged[$staleKey] = null;
        }
        $this->lastRenderKeys = $currentKeys;

        $templates->addData($merged);

        if (Profiler::isEnabled()) {
            Profiler::startMeasure('view:'.$template);
        }

        // $merged geç, ham $data değil — Plates'te render-data engine-data'yı (addData)
        // ezer; ham $data geçmek, framework global'lerinin (errors/success/user_id)
        // sayfa verisince ezilmesine izin verir ve yukarıda belgelenen önceliği bozardı.
        // Pass $merged, not raw $data — in Plates render-data overrides engine-data
        // (addData); passing raw $data would let page data clobber the framework
        // globals (errors/success/user_id), breaking the precedence documented above.
        $output = $templates->render($template, $merged);

        if (Profiler::isEnabled()) {
            Profiler::stopMeasure('view:'.$template);
            Profiler::addView($template, $data);

            // Save profiler data (must be ready before AJAX endpoint is called)
            // Profiler verisini kaydet (AJAX endpoint çağrılmadan önce hazır olmalı)
            Profiler::finish();

            // Inject Toolbar HTML
            // Toolbar HTML'ini enjekte et
            $bar = Profiler::renderToolbar();
            $output = stripos($output, '</body>') !== false
                ? str_ireplace('</body>', $bar.'</body>', $output)
                : $output.$bar;
        }

        echo $output;

        // Flash data lives for exactly one render — cleared AFTER rendering so the
        // partials/helpers above could still read it (mirrors _old handling).
        // Flash verisi tam bir render yaşar — render'dan SONRA temizlenir ki yukarıdaki
        // partial/helper'lar okuyabilsin (_old yönetimini taklit eder).
        unset($_SESSION['_old'], $_SESSION['_flash_errors']);
    }
}
