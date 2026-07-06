<?php

declare(strict_types=1);

namespace Core\Auth;

use Core\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * PersonalAccessToken — a hashed API token issued to an Authenticatable.
 * PersonalAccessToken — bir Authenticatable'a verilmiş hash'li API token'ı.
 *
 * Stored token = hash('sha256', plaintext). The plaintext handed to the client is
 * "{id}|{plaintext}" so lookup is an indexed primary-key fetch followed by a single
 * timing-safe hash comparison (no table scan).
 *
 * Saklanan token = hash('sha256', düz_metin). İstemciye verilen düz metin
 * "{id}|{düz_metin}" biçimindedir; böylece arama, indeksli primary-key getirme +
 * tek bir timing-safe hash karşılaştırmasıdır (tablo taraması yok).
 *
 * @property int $id
 * @property string $tokenable_type
 * @property int $tokenable_id
 * @property string $name
 * @property string $token
 * @property array<int, string>|null $abilities
 * @property Carbon|null $last_used_at
 * @property Carbon|null $expires_at
 * @property-read \Illuminate\Database\Eloquent\Model|null $tokenable
 *
 * @method static static|null find(mixed $id)
 */
class PersonalAccessToken extends Model
{
    protected $table = 'personal_access_tokens';

    protected $fillable = ['name', 'token', 'abilities', 'last_used_at', 'expires_at'];

    /** @var array<string, string> */
    protected $casts = [
        'abilities' => 'array',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected $hidden = ['token'];

    /**
     * The owning model (polymorphic — usually App\Models\User).
     * Sahibi model (polimorfik — genellikle App\Models\User).
     */
    public function tokenable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Resolve and verify a "{id}|{plaintext}" token string (timing-safe).
     * Returns null when the format, id or hash do not match.
     *
     * "{id}|{düz_metin}" token string'ini çöz ve doğrula (timing-safe).
     * Format, id veya hash eşleşmezse null döner.
     */
    public static function findToken(string $tokenString): ?self
    {
        if (! str_contains($tokenString, '|')) {
            return null;
        }

        [$id, $plain] = explode('|', $tokenString, 2);
        if (! ctype_digit($id) || $plain === '') {
            return null;
        }

        $token = static::find((int) $id);
        if ($token === null) {
            return null;
        }

        if (! hash_equals($token->token, hash('sha256', $plain))) {
            return null;
        }

        // Expiry — a null expires_at means the token never expires.
        // Süre — null expires_at token'ın süresiz olduğu anlamına gelir.
        if ($token->expires_at !== null && $token->expires_at->isPast()) {
            return null;
        }

        return $token;
    }

    /**
     * Does this token grant the given ability? '*' grants everything.
     * Bu token verilen yetkiyi tanır mı? '*' her şeyi tanır.
     */
    public function can(string $ability): bool
    {
        $abilities = $this->abilities ?? [];

        return in_array('*', $abilities, true) || in_array($ability, $abilities, true);
    }
}
