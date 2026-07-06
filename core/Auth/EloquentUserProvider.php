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

        // Timing-safe comparison
        // Zamanlama-güvenli karşılaştırma
        return ($stored !== null && $stored !== '' && hash_equals($stored, $token)) ? $user : null;
    }

    public function updateRememberToken(Authenticatable $user, ?string $token): void
    {
        $user->setRememberToken($token);
        // Eloquent persist — the provider is Eloquent-specific by design.
        // Eloquent kalıcı kayıt — provider tasarım gereği Eloquent'e özgüdür.
        $user->save();
    }

    /**
     * Configured model class name (an Eloquent model implementing Authenticatable).
     * Config'de tanımlı model sınıf adı (Authenticatable implemente eden Eloquent modeli).
     *
     * @return class-string
     */
    private function model(): string
    {
        $model = $this->config['model'] ?? null;

        if (! is_string($model) || ! class_exists($model)) {
            throw new \RuntimeException(
                'auth provider model is not configured // auth provider modeli yapılandırılmamış (config/auth.php).'
            );
        }

        return $model;
    }
}
