<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use Core\Auth;
use Core\Auth\PersonalAccessToken;
use Core\Container;
use Core\Exceptions\HttpException;
use Core\Middleware\ApiAuth;
use Core\Request;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class ApiAuthTest extends TestCase
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
        if (! $schema->hasTable('personal_access_tokens')) {
            $schema->create('personal_access_tokens', function (Blueprint $t) {
                $t->increments('id');
                $t->morphs('tokenable');
                $t->string('name');
                $t->string('token', 64)->unique();
                $t->text('abilities')->nullable();
                $t->timestamp('last_used_at')->nullable();
                $t->timestamp('expires_at')->nullable();
                $t->timestamps();
            });
        }

        DB::table('personal_access_tokens')->delete();
        DB::table('users')->delete();

        // Share ONE Auth instance between the middleware and the test so setUser()
        // is observable (in unit context no ServiceProvider binds Auth as singleton).
        $this->auth = new Auth;
        Container::getInstance()->instance(Auth::class, $this->auth);
    }

    private function makeUser(): User
    {
        return User::create([
            'name' => 'Token User',
            'email' => 'tok_'.uniqid().'@example.com',
            'password' => 'secret123',
        ]);
    }

    private function bearer(?string $token): Request
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/api/me';
        if ($token !== null) {
            $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer '.$token;
        } else {
            unset($_SERVER['HTTP_AUTHORIZATION']);
        }

        return Request::capture();
    }

    public function test_valid_token_authenticates_the_owning_user(): void
    {
        $user = $this->makeUser();
        ['plainTextToken' => $plain] = $user->createToken('test');

        $result = (new ApiAuth)->handle($this->bearer($plain), fn ($r) => 'passed');

        $this->assertSame('passed', $result);
        $this->assertTrue($this->auth->check());
        $this->assertSame($user->id, $this->auth->id());
        $this->assertSame($user->id, $this->auth->user()?->getAuthIdentifier());
    }

    public function test_token_is_stored_hashed_not_in_plaintext(): void
    {
        $user = $this->makeUser();
        ['plainTextToken' => $plain, 'token' => $model] = $user->createToken('test');

        [, $rawSecret] = explode('|', $plain, 2);
        $this->assertNotSame($rawSecret, $model->token);
        $this->assertSame(hash('sha256', $rawSecret), $model->token);
    }

    public function test_missing_token_is_rejected_with_401(): void
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionCode(401);
        (new ApiAuth)->handle($this->bearer(null), fn ($r) => 'passed');
    }

    public function test_invalid_token_is_rejected_with_401(): void
    {
        $this->makeUser();
        $this->expectException(HttpException::class);
        $this->expectExceptionCode(401);
        (new ApiAuth)->handle($this->bearer('999|deadbeefdeadbeef'), fn ($r) => 'passed');
    }

    public function test_ability_protected_route_allows_matching_token(): void
    {
        $user = $this->makeUser();
        ['plainTextToken' => $plain] = $user->createToken('scoped', ['posts.read']);

        $result = (new ApiAuth('posts.read'))->handle($this->bearer($plain), fn ($r) => 'passed');

        $this->assertSame('passed', $result);
        $this->assertTrue($this->auth->user()?->tokenCan('posts.read'));
        $this->assertFalse($this->auth->user()?->tokenCan('posts.write'));
    }

    public function test_ability_protected_route_rejects_token_without_ability(): void
    {
        $user = $this->makeUser();
        ['plainTextToken' => $plain] = $user->createToken('scoped', ['posts.read']);

        $this->expectException(HttpException::class);
        $this->expectExceptionCode(403);
        (new ApiAuth('posts.write'))->handle($this->bearer($plain), fn ($r) => 'passed');
    }

    public function test_wildcard_token_grants_every_ability(): void
    {
        $user = $this->makeUser();
        ['plainTextToken' => $plain] = $user->createToken('admin', ['*']);

        $result = (new ApiAuth('anything.at.all'))->handle($this->bearer($plain), fn ($r) => 'ok');

        $this->assertSame('ok', $result);
    }

    public function test_last_used_at_is_recorded(): void
    {
        $user = $this->makeUser();
        ['plainTextToken' => $plain, 'token' => $model] = $user->createToken('test');
        $this->assertNull($model->last_used_at);

        (new ApiAuth)->handle($this->bearer($plain), fn ($r) => 'passed');

        $this->assertNotNull(PersonalAccessToken::find($model->id)?->last_used_at);
    }

    public function test_unexpired_token_is_accepted(): void
    {
        $user = $this->makeUser();
        ['plainTextToken' => $plain] = $user->createToken('temp', ['*'], new \DateTimeImmutable('+1 hour'));

        $result = (new ApiAuth)->handle($this->bearer($plain), fn ($r) => 'passed');

        $this->assertSame('passed', $result);
    }

    public function test_expired_token_is_rejected_with_401(): void
    {
        $user = $this->makeUser();
        ['plainTextToken' => $plain] = $user->createToken('temp', ['*'], new \DateTimeImmutable('-1 hour'));

        $this->expectException(HttpException::class);
        $this->expectExceptionCode(401);
        (new ApiAuth)->handle($this->bearer($plain), fn ($r) => 'passed');
    }
}
