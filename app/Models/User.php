<?php

declare(strict_types=1);

namespace App\Models;

use Core\Auth\HasApiTokens;
use Core\Contracts\Authenticatable;
use Core\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class User extends Model implements Authenticatable
{
    // Bearer-token issuing for the api-auth guard ($user->createToken(...)).
    // api-auth guard'ı için Bearer-token üretimi ($user->createToken(...)).
    use HasApiTokens;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // Security: Hide sensitive fields
    // Güvenlik: Hassas alanları gizle
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Mutator: auto-hash the password when set.
    // Mutator: atandığında şifreyi otomatik hash'le.
    public function setPasswordAttribute(mixed $value): void
    {
        // empty() DEĞİL — empty('0') true olduğundan "0" parolası mutator'ı sessizce
        // atlıyordu: yeni kayıtta kolon hiç set edilmez, güncellemede eski parola kalırdı.
        // NOT empty() — since empty('0') is true, a password of "0" silently skipped the
        // mutator: the column was never set on insert, and the old password survived updates.
        if (is_string($value) && $value !== '') {
            $this->attributes['password'] = password_hash($value, PASSWORD_DEFAULT);
        }
    }

    // ── Core\Contracts\Authenticatable ──────────────────────────────────────────
    // Lets the core (Core\Auth) authenticate this model without coupling to it.
    // Çekirdeğin (Core\Auth) bu modele bağlanmadan kimlik doğrulamasını sağlar.

    public function getAuthIdentifier(): int|string
    {
        return $this->id;
    }

    public function getAuthPassword(): string
    {
        return (string) $this->password;
    }

    public function getRememberToken(): ?string
    {
        return $this->remember_token;
    }

    public function setRememberToken(?string $token): void
    {
        $this->remember_token = $token;
    }
}
