<?php

declare(strict_types=1);

namespace Core;

/**
 * Form Request — Request'i genişletir, controller'a inject edilir.
 *
 * Kullanım:
 *   class StoreUserRequest extends FormRequest {
 *       public function rules(): array { return ['email' => 'required|email']; }
 *       public function messages(): array { return ['email.required' => 'E-posta zorunlu.']; }
 *       public function authorize(): bool { return true; }
 *   }
 *
 *   public function store(StoreUserRequest $request) {
 *       $data = $request->validated();
 *   }
 *
 * @phpstan-consistent-constructor
 */
abstract class FormRequest extends Request
{
    private array $validatedData = [];

    // ── Abstract API ─────────────────────────────────────────────────────────

    abstract public function rules(): array;

    public function messages(): array
    {
        return [];
    }

    // Yetki kontrolü — false dönerse 403. Varsayılan GÜVENLİ taraftır: bir isteğin
    // yetkilendirilmesi açıkça onaylanmalıdır (Laravel'in make:request çıktısıyla aynı).
    // Authorization — false returns 403. The default is the SAFE side: authorizing a
    // request must be explicit (same as Laravel's make:request output).
    public function authorize(): bool
    {
        return false;
    }

    // ── Factory: mevcut Request'ten oluştur ──────────────────────────────────

    public static function createFrom(Request $parent): static
    {
        // Parent Request'in dahili state'ini kullan (superglobal'lara değil)
        // Bu sayede middleware'lerin Request üzerindeki değişiklikleri korunur.
        $instance = new static(
            $parent->getQuery(),
            $parent->getPost(),
            $parent->getFiles(),
            $parent->getServer(),
            $parent->getCookies()
        );
        $instance->setRouteParams($parent->getRouteParams());
        $instance->resolve();

        return $instance;
    }

    // ── Doğrulama akışı ──────────────────────────────────────────────────────

    private function resolve(): void
    {
        // 1) Yetki kontrolü
        // expectsJson() — AJAX + Accept: application/json + Bearer token'ı kapsar,
        // böylece API client'ları redirect yerine JSON yanıt alır (ExceptionHandler ile tutarlı).
        if (! $this->authorize()) {
            if ($this->expectsJson()) {
                Response::json(['error' => 'Bu işlem için yetkiniz yok.'], 403);
            }
            Response::forbidden('Bu işlem için yetkiniz yok.');

            return; // Response::forbidden zaten exception fırlatır
        }

        // 2) Validation
        $validator = Validator::make($this->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            if ($this->expectsJson()) {
                Response::json(['errors' => $validator->errors()], 422);
            }

            // Flash errors + eski input → geri yönlendir
            // Hassas alanları (parola/token vb.) _old'a yazma — session'da düz metin kalmasın.
            // Don't flash sensitive fields (password/token etc.) into _old — no plaintext in session.
            $_SESSION['_flash_errors'] = $validator->errors();
            $_SESSION['_old'] = self::exceptSensitive($this->all());
            back();

            return; // back() RedirectException fırlatır, buraya gelmez
        }

        // 3) Yalnızca rules() içindeki alanları sakla
        // Dot/wildcard anahtarları (user.name, items.*.qty) çöz — düz only()
        // yalnızca üst düzey anahtarları eşleştirip iç içe alanları düşürürdü.
        // Resolve dot/wildcard keys (user.name, items.*.qty) — a flat only() only
        // matches top-level keys and would silently drop nested fields.
        $this->validatedData = self::extractValidated($this->all(), array_keys($this->rules()));
    }

    /**
     * rules() anahtarlarına göre doğrulanmış veriyi (nokta/joker destekli) çıkar.
     * Extract validated data for the given rule keys, honouring dot / wildcard paths.
     *
     * @param  array<string|int, mixed>  $data
     * @param  array<int, mixed>  $keys
     * @return array<string|int, mixed>
     */
    private static function extractValidated(array $data, array $keys): array
    {
        $result = [];

        foreach ($keys as $key) {
            if (! is_string($key) || $key === '') {
                continue;
            }

            foreach (self::resolveKey($data, explode('.', $key)) as [$path, $value]) {
                self::setNested($result, $path, $value);
            }
        }

        return $result;
    }

    /**
     * Bir nokta-yolunu veri üzerinde çözümler; her eşleşme için [yol, değer] döner.
     * Resolve a dot path against the data, returning [path, value] for each match.
     *
     * @param  array<string|int, mixed>  $data
     * @param  array<int, string>  $segments
     * @return array<int, array{0: array<int, string|int>, 1: mixed}>
     */
    private static function resolveKey(array $data, array $segments): array
    {
        if ($segments === []) {
            return [];
        }

        $head = array_shift($segments);

        if ($head === '*') {
            $matches = [];
            foreach ($data as $index => $value) {
                if (! is_array($value)) {
                    continue;
                }
                foreach (self::resolveKey($value, $segments) as [$path, $resolved]) {
                    $matches[] = [array_merge([$index], $path), $resolved];
                }
            }

            return $matches;
        }

        if (! array_key_exists($head, $data)) {
            return [];
        }

        if ($segments === []) {
            return [[[$head], $data[$head]]];
        }

        return is_array($data[$head]) ? self::resolveKey($data[$head], $segments) : [];
    }

    /**
     * Bir değeri sonuç dizisine nokta-yolu boyunca yerleştirir.
     * Place a value into the result at the given dot path.
     *
     * @param  array<string|int, mixed>  $target
     * @param  array<int, string|int>  $path
     */
    private static function setNested(array &$target, array $path, mixed $value): void
    {
        $ref = &$target;
        foreach ($path as $segment) {
            if (! isset($ref[$segment]) || ! is_array($ref[$segment])) {
                $ref[$segment] = [];
            }
            $ref = &$ref[$segment];
        }
        $ref = $value;
    }

    // ── Public API ───────────────────────────────────────────────────────────

    /**
     * Yalnızca rules() içinde tanımlı, doğrulanmış alanları döndürür.
     */
    public function validated(): array
    {
        return $this->validatedData;
    }
}
