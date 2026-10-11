<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use Core\Auth;
use Core\Cache;
use Core\Container;
use Core\RateLimiter;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

/**
 * Login throttle + sabit-zamanlı hata testleri.
 * Login throttle + constant-time failure tests.
 *
 * Auth::attempt() artık (kimlik + IP) başına başarısız denemeleri sınırlar ve
 * olmayan kullanıcıda da sahte bir bcrypt doğrulaması çalıştırır. Aşağıdaki
 * testler davranışı doğrular; zamanlama eşitliği (timing) ölçülmez — yalnızca
 * sahte doğrulama yolunun bir hata fırlatmadan çalıştığı varsayılır.
 *
 * Auth::attempt() now rate-limits failed attempts per (identifier + IP) and runs
 * a dummy bcrypt verify for unknown users. These tests assert behaviour; timing
 * equality is not measured — only that the dummy-verify path runs without error.
 */
class LoginThrottleTest extends TestCase
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

        // RateLimiter resolves Cache from the Container — bind a singleton so the
        // test and the limiter observe the same store.
        // RateLimiter Cache'i Container'dan çözer — test ve limiter aynı depoyu
        // gözlemlesin diye singleton bağla.
        $container = Container::getInstance();
        if (! $container->has(Cache::class)) {
            $container->singleton(Cache::class, fn () => new Cache);
        }
        $container->make(Cache::class)->flush();

        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        unset($_SERVER['HTTP_X_FORWARDED_FOR']);

        $this->auth = new Auth;
        Container::getInstance()->instance(Auth::class, $this->auth);
    }

    protected function tearDown(): void
    {
        Container::getInstance()->make(Cache::class)->flush();
        parent::tearDown();
    }

    private function makeUser(): User
    {
        return User::create([
            'name' => 'Throttle User',
            'email' => 'throttle_'.uniqid().'@example.com',
            'password' => 'secret123',
        ]);
    }

    /** Deterministic throttle key for the given email (mirrors Auth::throttleKey). */
    private function throttleKey(string $email): string
    {
        return 'login_'.hash('sha256', strtolower($email).'|127.0.0.1');
    }

    public function test_attempt_succeeds_with_correct_credentials(): void
    {
        $user = $this->makeUser();

        $this->assertTrue($this->auth->attempt(['email' => $user->email, 'password' => 'secret123']));
        $this->assertTrue($this->auth->check());
        $this->assertSame($user->id, $this->auth->id());
    }

    public function test_attempt_fails_with_wrong_password(): void
    {
        $user = $this->makeUser();

        $this->assertFalse($this->auth->attempt(['email' => $user->email, 'password' => 'wrong']));
        $this->assertTrue($this->auth->guest());
    }

    public function test_attempt_fails_for_unknown_user(): void
    {
        // Dummy password_verify() çalışır ve fırlatmaz; sonuç yine false olmalı.
        // The dummy password_verify() runs, throws nothing, and the result is false.
        $this->assertFalse(
            $this->auth->attempt(['email' => 'nobody_'.uniqid().'@example.com', 'password' => 'whatever'])
        );
    }

    public function test_repeated_failures_lock_out_even_correct_password(): void
    {
        $user = $this->makeUser();

        for ($i = 0; $i < 5; $i++) {
            $this->assertFalse(
                $this->auth->attempt(['email' => $user->email, 'password' => 'wrong-'.$i])
            );
        }

        // 6th attempt is blocked BEFORE the password is checked.
        // 6. deneme parola kontrol edilmeden ÖNCE engellenir.
        $this->assertFalse($this->auth->attempt(['email' => $user->email, 'password' => 'secret123']));
        $this->assertTrue($this->auth->guest());
    }

    public function test_failed_attempts_are_counted_per_identifier(): void
    {
        $userA = $this->makeUser();
        $userB = $this->makeUser();

        for ($i = 0; $i < 5; $i++) {
            $this->auth->attempt(['email' => $userA->email, 'password' => 'wrong-'.$i]);
        }

        $limiter = Container::getInstance()->make(RateLimiter::class);
        $this->assertSame(5, $limiter->attempts($this->throttleKey($userA->email)));

        // A different identifier is unaffected and can still log in.
        // Farklı bir kimlik etkilenmez ve hâlâ giriş yapabilir.
        $this->assertTrue($this->auth->attempt(['email' => $userB->email, 'password' => 'secret123']));
    }

    public function test_successful_login_clears_failure_counter(): void
    {
        $user = $this->makeUser();

        $this->auth->attempt(['email' => $user->email, 'password' => 'wrong']);
        $this->auth->attempt(['email' => $user->email, 'password' => 'wrong']);

        $this->assertTrue($this->auth->attempt(['email' => $user->email, 'password' => 'secret123']));

        $limiter = Container::getInstance()->make(RateLimiter::class);
        $this->assertSame(0, $limiter->attempts($this->throttleKey($user->email)));
    }
}
