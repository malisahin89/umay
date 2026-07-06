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

    // Yetki kontrolü — false dönerse 403
    public function authorize(): bool
    {
        return true;
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
        $this->validatedData = $this->only(array_keys($this->rules()));
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
