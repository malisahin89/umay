<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use Core\Auth;
use Core\Exceptions\HttpException;
use Core\Middleware\Auth as AuthMiddleware;
use Core\RedirectException;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

/**
 * 'auth' middleware (Core\Middleware\Auth) testleri.
 * 'auth' middleware (Core\Middleware\Auth) tests.
 *
 * Misafir JSON isteği 401; misafir web isteği login'e redirect; giriş yapmış
 * istek olduğu gibi geçer.
 *
 * Guest JSON requests get 401; guest web requests are redirected to login;
 * authenticated requests pass through.
 */
class AuthMiddlewareTest extends TestCase
{
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

        // TestCase does not reset $_SERVER headers between tests, so stale
        // Accept / XHR / Authorization values leak from earlier tests.
        // TestCase testler arası $_SERVER header'larını sıfırlamaz; önceki
        // testlerden gelen Accept / XHR / Authorization değerleri sızabilir.
        unset(
            $_SERVER['HTTP_ACCEPT'],
            $_SERVER['HTTP_X_REQUESTED_WITH'],
            $_SERVER['HTTP_AUTHORIZATION'],
        );
    }

    private function makeUser(): User
    {
        return User::create([
            'name' => 'Guard User',
            'email' => 'guard_'.uniqid().'@example.com',
            'password' => 'secret123',
        ]);
    }

    public function test_guest_json_request_is_rejected_with_401(): void
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionCode(401);

        $request = $this->makeRequest('GET', '/dashboard', [], ['Accept' => 'application/json']);

        (new AuthMiddleware)->handle($request, fn ($r) => 'passed');
    }

    public function test_guest_ajax_request_is_rejected_with_401(): void
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionCode(401);

        $request = $this->makeRequest('GET', '/dashboard', [], ['X-Requested-With' => 'XMLHttpRequest']);

        (new AuthMiddleware)->handle($request, fn ($r) => 'passed');
    }

    public function test_guest_web_request_redirects_to_login(): void
    {
        $this->expectException(RedirectException::class);

        $request = $this->makeRequest('GET', '/dashboard');

        (new AuthMiddleware)->handle($request, fn ($r) => 'passed');
    }

    public function test_authenticated_request_passes_through(): void
    {
        $user = $this->makeUser();
        $_SESSION['user_id'] = $user->id;

        $request = $this->makeRequest('GET', '/dashboard');

        $result = (new AuthMiddleware)->handle($request, fn ($r) => 'passed');

        $this->assertSame('passed', $result);
    }

    public function test_bearer_guest_request_is_treated_as_json(): void
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionCode(401);

        $request = $this->makeRequest('GET', '/api/resource', [], ['Authorization' => 'Bearer in.valid.token']);

        (new AuthMiddleware)->handle($request, fn ($r) => 'passed');
    }
}
