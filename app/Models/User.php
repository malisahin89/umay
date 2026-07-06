<?php

declare(strict_types=1);

namespace App\Models;

use Core\Auth\HasApiTokens;
use Core\Contracts\Authenticatable;
use Core\Model;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $username
 * @property string|null $profile_image
 * @property string|null $bio
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string $status
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $full_name
 */
class User extends Model implements Authenticatable
{
    // Bearer-token issuing for the api-auth guard ($user->createToken(...)).
    // api-auth guard'ı için Bearer-token üretimi ($user->createToken(...)).
    use HasApiTokens;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'surname',
        'username',
        'profile_image',
        'bio',
        'email',
        'password',
    ];

    // Security: privilege fields are never mass-assignable.
    // Güvenlik: yetki alanları asla toplu atanamaz.
    // Set explicitly: $user->role = 'admin'; $user->status = 'active';
    // Açıkça atayın: $user->role = 'admin'; $user->status = 'active';
    protected $guarded = [
        'role',
        'status',
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

    // Accessor: Full name / Accessor: Tam ad
    public function getFullNameAttribute(): string
    {
        return trim($this->name.' '.$this->surname);
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

    // Scope: Active users / Scope: Aktif kullanıcılar
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    // Role checks / Rol kontrolleri
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    // Permission check — admin holds every permission.
    // İzin kontrolü — admin her izne sahiptir.
    public function hasPermission(string $permissionName): bool
    {
        if ($this->role === 'admin') {
            return true;
        }

        return Manager::table('role_permissions')
            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->where('role_permissions.role', $this->role)
            ->where('permissions.name', $permissionName)
            ->exists();
    }
}
