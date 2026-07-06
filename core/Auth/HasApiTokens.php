<?php

declare(strict_types=1);

namespace Core\Auth;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * HasApiTokens — add Bearer-token issuing to an Eloquent user model.
 * HasApiTokens — bir Eloquent kullanıcı modeline Bearer-token verme yeteneği ekler.
 *
 * Usage / Kullanım (app/Models/User.php):
 *   class User extends Model implements Authenticatable
 *   {
 *       use \Core\Auth\HasApiTokens;
 *   }
 *
 *   // Issue a token (run the personal_access_tokens migration first):
 *   // Token üret (önce personal_access_tokens migration'ını çalıştırın):
 *   ['plainTextToken' => $plain] = $user->createToken('mobile', ['posts.read']);
 *   // → send $plain to the client; it is never retrievable again.
 *   // → $plain'i istemciye verin; bir daha geri alınamaz.
 *
 * Protect routes with the api-auth middleware:
 * Route'ları api-auth middleware'i ile koruyun:
 *   Route::get('/me', 'Api\\UserController@me')->middleware('api-auth');
 *   Route::post('/posts', 'Api\\PostController@store')->middleware('api-auth:posts.write');
 */
trait HasApiTokens
{
    /** The access token used to authenticate the current request (if any). */
    protected ?PersonalAccessToken $accessToken = null;

    /**
     * All personal access tokens belonging to this model.
     * Bu modele ait tüm kişisel erişim token'ları.
     */
    public function tokens(): MorphMany
    {
        return $this->morphMany(PersonalAccessToken::class, 'tokenable');
    }

    /**
     * Create a new token. Returns the persisted model plus the ONE-TIME plaintext
     * token ("{id}|{plaintext}") — store/show it now, it cannot be retrieved later.
     *
     * Yeni token oluştur. Saklanan modeli ve TEK SEFERLİK düz metin token'ı
     * ("{id}|{düz_metin}") döndürür — şimdi saklayın/gösterin, sonradan alınamaz.
     *
     * @param  array<int, string>  $abilities  ['*'] = all abilities // tüm yetkiler
     * @param  \DateTimeInterface|null  $expiresAt  Optional expiry; null = never expires // Opsiyonel süre; null = süresiz
     * @return array{token: PersonalAccessToken, plainTextToken: string}
     */
    public function createToken(string $name, array $abilities = ['*'], ?\DateTimeInterface $expiresAt = null): array
    {
        $plain = bin2hex(random_bytes(32));

        $attributes = [
            'name' => $name,
            'token' => hash('sha256', $plain),
            'abilities' => $abilities,
        ];

        // Only set expires_at when an expiry is requested — keeps inserts working on
        // schemas that predate the expires_at column (null = never expires).
        // expires_at yalnızca süre istendiğinde set edilir — expires_at sütunundan önceki
        // şemalarda insert'lerin çalışmasını korur (null = süresiz).
        if ($expiresAt !== null) {
            $attributes['expires_at'] = $expiresAt;
        }

        $token = new PersonalAccessToken($attributes);

        $this->tokens()->save($token);

        return [
            'token' => $token,
            'plainTextToken' => $token->id.'|'.$plain,
        ];
    }

    /**
     * Bind the token that authenticated the current request to this instance.
     * Mevcut isteğin kimliğini doğrulayan token'ı bu instance'a bağla.
     */
    public function withAccessToken(PersonalAccessToken $token): static
    {
        $this->accessToken = $token;

        return $this;
    }

    /**
     * The token authenticating the current request, or null.
     * Mevcut isteğin kimliğini doğrulayan token, ya da null.
     */
    public function currentAccessToken(): ?PersonalAccessToken
    {
        return $this->accessToken;
    }

    /**
     * Does the current request's token grant the given ability?
     * Mevcut isteğin token'ı verilen yetkiyi tanıyor mu?
     */
    public function tokenCan(string $ability): bool
    {
        return $this->accessToken !== null && $this->accessToken->can($ability);
    }
}
