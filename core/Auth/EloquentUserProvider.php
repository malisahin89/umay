<?php

declare(strict_types=1);

namespace Core\Auth;

use Core\Contracts\Authenticatable;
use Core\Contracts\UserProvider;

/**
 * EloquentUserProvider — the default UserProvider (Eloquent-based).
 * EloquentUserProvider — varsayılan UserProvider (Eloquent tabanlı).
 *
 * Reads which model to use from the 'model' key of its provider config
 * (config/auth.php). Depends ONLY on contracts + config — it does NOT
 * know the App\ namespace.
 *
 * Hangi modelin kullanılacağını kendi provider config'inin 'model' anahtarından
 * (config/auth.php) okur. YALNIZCA contract + config'e bağlıdır — App\
 * namespace'ini TANIMAZ.
 */
class EloquentUserProvider implements UserProvider
{
    /**
     * @param  array<string, mixed>  $config  Provider config — must contain a 'model' class-string.
     */
    public function __construct(private array $config) {}

    public function retrieveById(int|string $id): ?Authenticatable
    {
        $model = $this->model();

        return $model::find($id);
    }

    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        $email = $credentials['email'] ?? null;

        if (! is_string($email) || $email === '') {
            return null;
        }

        $model = $this->model();

        return $model::where('email', $email)->first();
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        $password = $credentials['password'] ?? '';

        return is_string($password)
            && $password !== ''
            && password_verify($password, $user->getAuthPassword());
    }

    public function retrieveByToken(int|string $id, string $token): ?Authenticatable
    {
        $user = $this->retrieveById($id);

        if (! $user) {
            return null;
        }

        $stored = $user->getRememberToken();
        if ($stored === null || $stored === '') {
            return null;
        }

        // Only the "hash|expiryUnix" format is accepted. Legacy "hash"-only
        // values (no server-side expiry) are rejected: a stolen legacy token
        // would otherwise remain valid indefinitely, defeating the expiry the
        // "hash|expiry" format was introduced to enforce.
        // Yalnızca "hash|expiryUnix" formatı kabul edilir. Süresiz eski "hash"
        // değerleri reddedilir: çalınan legacy token aksi halde sonsuza dek geçerli
        // kalır ve "hash|expiry" formatının getirdiği kısıtı baypas ederdi.
        $parts = explode('|', $stored, 2);
        if (count($parts) !== 2) {
            return null;
        }
        [$hash, $expires] = $parts;
        if (! ctype_digit($expires) || (int) $expires < time()) {
            return null;
        }

        // Timing-safe comparison
        // Zamanlama-güvenli karşılaştırma
        return hash_equals($hash, $token) ? $user : null;
    }

    public function updateRememberToken(Authenticatable $user, ?string $token): void
    {
        $user->setRememberToken($token);
        // Eloquent persist — the provider is Eloquent-specific by design.
        // Eloquent kalıcı kayıt — provider tasarım gereği Eloquent'e özgüdür.
        $user->save();
    }

    /** 
     * Configured model class name (an Authenticatable Eloquent model).
     * Config'de tanımlı model sınıf adı (Authenticatable implemente eden Eloquent modeli).
     *
     * Fails loudly on a misconfiguration instead of letting a non-Authenticatable
     * model produce a runtime TypeError downstream.
     *
     * Yanlış yapılandırmada hata üst seviyede verilir; Authenticatable olmayan bir
     * model alt katmanlarda runtime TypeError üretmek yerine net bir hata alır.
     *
     * @return class-string
     */
    private function model(): string
    {
        $model = $this->config['model'] ?? null;

        if (! is_string($model) || ! class_exists($model)
            || ! is_subclass_of($model, Authenticatable::class)) {
            throw new \RuntimeException(
                'auth provider model is not configured or does not implement Authenticatable // auth provider modeli yapılandırılmamış veya Authenticatable implemente etmiyor (config/auth.php).'
            );
        }

        return $model;
    }
}
