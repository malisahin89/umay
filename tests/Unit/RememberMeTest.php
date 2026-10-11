<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use Core\Auth;
use Core\Auth\EloquentUserProvider;
use Core\Container;
use Core\Contracts\Authenticatable;
use Core\Contracts\UserProvider;
use Core\Middleware\RememberMe;
use Core\Request;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

/**
 * Remember-me sunucu tarafı süre (expiry) testleri.
 * Remember-me server-side expiry tests.
 *
 * Token depoda "hash|expiryUnix" olarak tutulur; süresi geçmiş token çalınmış
 * cookie'nin kendi süresi atlatılsa bile reddedilmelidir. Süresiz eski "hash"
 * formatı reddedilir (fail-closed).
 *
 * The token is stored as "hash|expiryUnix"; an expired token must be rejected
 * even if the cookie's own expiry is bypassed. Legacy "hash" (no expiry) values
 * are rejected (fail-closed).
 */
class RememberMeTest extends TestCase
{
    private Auth $auth;

    protected function setUp(): void
    {
        parent::setUp();

        $schema = DB::schema();
        if (! $schema->hasTable('users')) {
            $schema->create('users', function (Blueprint $t) {
                $t->increments('id');
                $t->string('name');
                $t->string('email')->unique();
                $t->string('password');
                $t->string('remember_token', 100)->nullable();
                $t->timestamps();
            });
        }
        DB::table('users')->delete();

        $this->auth = new Auth;
        $this->auth->setProvider(new EloquentUserProvider(['model' => User::class]));
        Container::getInstance()->instance(Auth::class, $this->auth);
    }

    private function makeUser(): User
    {
        return User::create([
            'name' => 'Remember User',
            'email' => 'rem_'.uniqid().'@example.com',
            'password' => 'secret123',
        ]);
    }

    private function storeToken(User $user, string $plain, ?int $expiresAt): void
    {
        $stored = hash('sha256', $plain).($expiresAt === null ? '' : '|'.$expiresAt);
        $user->setRememberToken($stored);
        $user->save();
    }

    public function test_token_with_future_expiry_is_accepted(): void
    {
        $user = $this->makeUser();
        $plain = bin2hex(random_bytes(16));
        $this->storeToken($user, $plain, time() + 3600);

        $found = $this->auth->provider()->retrieveByToken($user->id, hash('sha256', $plain));

        $this->assertNotNull($found);
        $this->assertSame($user->id, $found->getAuthIdentifier());
    }

    public function test_token_with_past_expiry_is_rejected(): void
    {
        $user = $this->makeUser();
        $plain = bin2hex(random_bytes(16));
        $this->storeToken($user, $plain, time() - 10);

        $this->assertNull($this->auth->provider()->retrieveByToken($user->id, hash('sha256', $plain)));
    }

    public function test_legacy_token_without_expiry_is_rejected(): void
    {
        $user = $this->makeUser();
        $plain = bin2hex(random_bytes(16));
        $this->storeToken($user, $plain, null);

        $this->assertNull($this->auth->provider()->retrieveByToken($user->id, hash('sha256', $plain)));
    }

    public function test_non_numeric_expiry_is_rejected(): void
    {
        $user = $this->makeUser();
        $plain = bin2hex(random_bytes(16));
        $user->setRememberToken(hash('sha256', $plain).'|not-a-number');
        $user->save();

        $this->assertNull($this->auth->provider()->retrieveByToken($user->id, hash('sha256', $plain)));
    }

    public function test_wrong_token_is_rejected(): void
    {
        $user = $this->makeUser();
        $plain = bin2hex(random_bytes(16));
        $this->storeToken($user, $plain, time() + 3600);

        $this->assertNull($this->auth->provider()->retrieveByToken($user->id, hash('sha256', 'other')));
    }

    public function test_empty_stored_token_is_rejected(): void
    {
        $user = $this->makeUser();

        $this->assertNull($this->auth->provider()->retrieveByToken($user->id, hash('sha256', 'anything')));
    }

    public function test_middleware_logs_in_with_valid_cookie(): void
    {
        $user = $this->makeUser();
        $plain = bin2hex(random_bytes(16));
        $this->storeToken($user, $plain, time() + 3600);

        $_COOKIE['remember_me'] = $user->id.':'.$plain;
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';
        $request = Request::capture();

        $result = (new RememberMe)->handle($request, fn ($r) => 'passed');

        $this->assertSame('passed', $result);
        $this->assertTrue($this->auth->check());
    }

    public function test_middleware_rejects_expired_cookie(): void
    {
        $user = $this->makeUser();
        $plain = bin2hex(random_bytes(16));
        $this->storeToken($user, $plain, time() - 10);

        $_COOKIE['remember_me'] = $user->id.':'.$plain;
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';
        $request = Request::capture();

        (new RememberMe)->handle($request, fn ($r) => 'passed');

        $this->assertTrue($this->auth->guest());
    }

    public function test_logout_with_forged_cookie_does_not_revoke_victim_token(): void
    {
        $victim = $this->makeUser();
        $plain = bin2hex(random_bytes(16));
        $this->storeToken($victim, $plain, time() + 3600);

        // Attacker: empty session + forged cookie targeting the victim by id only.
        // Saldırgan: boş session + kurbanı yalnızca id ile hedefleyen sahte cookie.
        $_SESSION = [];
        $_COOKIE['remember_me'] = $victim->id.':'.bin2hex(random_bytes(16));

        $this->auth->logout();

        $this->assertNotNull(User::find($victim->id)->getRememberToken());
    }

    public function test_logout_with_valid_cookie_revokes_stored_token_when_session_is_stale(): void
    {
        $user = $this->makeUser();
        $plain = bin2hex(random_bytes(16));
        $this->storeToken($user, $plain, time() + 3600);

        // Stale/deleted session — the cookie must still revoke the OWN token.
        // Bayat/silinmiş session — cookie yine de KENDİ token'ını iptal etmeli.
        $_SESSION = [];
        $_COOKIE['remember_me'] = $user->id.':'.$plain;

        $this->auth->logout();

        $this->assertNull(User::find($user->id)->getRememberToken());
    }

    public function test_zero_user_id_in_session_resolves_user(): void
    {
        $zeroUser = new class implements Authenticatable {
            public function getAuthIdentifier(): int|string
            {
                return 0;
            }

            public function getAuthPassword(): string
            {
                return '';
            }

            public function getRememberToken(): ?string
            {
                return null;
            }

            public function setRememberToken(?string $token): void {}
        };

        $stubProvider = new class($zeroUser) implements UserProvider {
            public function __construct(private Authenticatable $user) {}

            public function retrieveById(int|string $id): ?Authenticatable
            {
                return $id === 0 ? $this->user : null;
            }

            public function retrieveByCredentials(array $credentials): ?Authenticatable
            {
                return null;
            }

            public function validateCredentials(Authenticatable $user, array $credentials): bool
            {
                return false;
            }

            public function retrieveByToken(int|string $id, string $token): ?Authenticatable
            {
                return null;
            }

            public function updateRememberToken(Authenticatable $user, ?string $token): void {}
        };

        $auth = new Auth;
        $auth->setProvider($stubProvider);

        $_SESSION['user_id'] = 0;

        $this->assertTrue($auth->check());
        $this->assertSame(0, $auth->id());
    }
}
