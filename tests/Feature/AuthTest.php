<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Core\Auth;
use Core\Container;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class AuthTest extends TestCase
{
    private Auth $auth;

    protected function setUp(): void
    {
        parent::setUp();

        // check()/id() artık kullanıcıyı provider'dan gerçekten çözer — testler
        // için gerçek bir users tablosu gerekir (in-memory sqlite).
        // check()/id() now actually resolve the user via the provider — tests
        // need a real users table (in-memory sqlite).
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
        Container::getInstance()->instance(Auth::class, $this->auth);
    }

    private function makeUser(): User
    {
        return User::create([
            'name' => 'Auth User',
            'email' => 'auth_'.uniqid().'@example.com',
            'password' => 'secret123',
        ]);
    }

    public function test_auth_check_returns_false_when_no_session(): void
    {
        $this->assertFalse($this->auth->check());
    }

    public function test_auth_guest_returns_true_when_not_logged_in(): void
    {
        $this->assertTrue($this->auth->guest());
    }

    public function test_auth_id_returns_null_when_not_logged_in(): void
    {
        $this->assertNull($this->auth->id());
    }

    public function test_auth_user_returns_null_when_no_session(): void
    {
        $this->assertNull($this->auth->user());
    }

    public function test_session_user_id_makes_auth_check_true(): void
    {
        $user = $this->makeUser();
        $_SESSION['user_id'] = $user->id;

        $this->assertTrue($this->auth->check());
        $this->assertFalse($this->auth->guest());
        $this->assertEquals($user->id, $this->auth->id());
    }

    // ── Bayat session: silinmiş kullanıcı kimlik doğrulamış SAYILMAZ ─────────
    // ── Stale session: a deleted user must NOT count as authenticated ────────

    public function test_check_returns_false_for_deleted_user(): void
    {
        $user = $this->makeUser();
        $_SESSION['user_id'] = $user->id;
        $user->delete();

        // Eski davranışta check() session'a bakıp true dönüyor, user() null
        // kalıyordu — guard'lar hayalet session'ı içeri alıyordu.
        // Previously check() looked at the session and returned true while user()
        // was null — guards let the ghost session through.
        $this->assertFalse($this->auth->check());
        $this->assertTrue($this->auth->guest());
        $this->assertNull($this->auth->user());
        $this->assertNull($this->auth->id());
    }

    public function test_stale_session_queries_provider_only_once(): void
    {
        $user = $this->makeUser();
        $_SESSION['user_id'] = $user->id;
        $user->delete();

        $this->auth->check();

        // İkinci çağrı önbellekten (userResolved) dönmeli — kullanıcıyı geri
        // ekleyip sorgulanmadığını gözlemleyerek doğruluyoruz.
        // The second call must come from the cache (userResolved) — verified by
        // re-inserting the user and observing that no fresh query happens.
        DB::table('users')->insert([
            'id' => $user->id,
            'name' => 'Back',
            'email' => 'back_'.uniqid().'@example.com',
            'password' => 'secret123',
        ]);

        $this->assertFalse($this->auth->check());
    }

    // ── setUser: stateless (api-auth) kullanıcı ataması ──────────────────────
    // ── setUser: stateless (api-auth) user binding ────────────────────────────

    public function test_set_user_makes_check_true_without_session(): void
    {
        $user = $this->makeUser();
        $this->auth->setUser($user);

        $this->assertTrue($this->auth->check());
        $this->assertEquals($user->id, $this->auth->id());
        $this->assertSame($user, $this->auth->user());
    }

    public function test_clear_cache_resets_resolution_state(): void
    {
        $user = $this->makeUser();
        $_SESSION['user_id'] = $user->id;

        $this->assertTrue($this->auth->check());

        $user->delete();
        $this->auth->clearCache();

        // clearCache sonrası yeniden çözümlenir — silinen kullanıcı artık yok.
        // Re-resolved after clearCache — the deleted user is gone now.
        $this->assertFalse($this->auth->check());
    }

    public function test_login_validation_requires_email_and_password(): void
    {
        $errors = validate([], ['email' => 'required|email', 'password' => 'required|min:8']);
        $this->assertNotNull($errors);
        $this->assertArrayHasKey('email', $errors);
        $this->assertArrayHasKey('password', $errors);
    }

    public function test_login_validation_passes_with_valid_data(): void
    {
        $errors = validate(
            ['email' => 'user@example.com', 'password' => 'password123'],
            ['email' => 'required|email', 'password' => 'required|min:8']
        );
        $this->assertNull($errors);
    }
}
