# Authentication

Umay Framework provides a **pluggable** authentication infrastructure. The core (`Core\Auth`) is not bound to a concrete user class; instead, it talks with two contracts (interfaces) and `config/auth.php`:

- **`Core\Contracts\Authenticatable`** — The contract for your user model (identity, password, remember-token).
- **`Core\Contracts\UserProvider`** — The "Login brain": finding the user from storage and verifying the password.

Which model and which provider to use is entirely determined in `config/auth.php`. Thus, you can write your own login logic without touching the core.

> [!NOTE]
> The starting skeleton **does not include a ready-made login screen/controller** (just like Laravel's basic skeleton). You create the login interface yourself; the core only provides you with a powerful auth engine.

## Configuration (`config/auth.php`)

```php
return [
    'default' => 'eloquent',

    'providers' => [
        'eloquent' => [
            'driver' => Core\Auth\EloquentUserProvider::class,
            'model'  => App\Models\User::class,
        ],
    ],
];
```

The generic `App\Models\User` that comes at the start implements `Core\Contracts\Authenticatable`. Passwords are automatically hashed with `bcrypt` thanks to the `setPasswordAttribute` mutator in the `User` model.

## Logging In (Login)

To verify a user's credentials and open a session, `Auth::attempt()` is used. This method takes an **array**; it delegates the task of finding the user and verifying the password to the configured `UserProvider`.

```php
use Core\Facades\Auth;

public function authenticate(Request $request)
{
    $credentials = [
        'email'    => $request->post('email'),
        'password' => $request->post('password'),
        'remember' => $request->post('remember') === 'on', // optional "remember me"
    ];

    if (Auth::attempt($credentials)) {
        // Session successfully opened
        redirect('home');
    } else {
        // Email or password wrong
        flash('error', 'Invalid login attempt.');
        back();
    }
}
```

> [!WARNING]
> Never store passwords as plain text. The `setPasswordAttribute` mutator in the `User` model automatically passes the password you assign to the model through a Hash.

## Logging Out (Logout)

```php
use Core\Facades\Auth;

public function logout()
{
    Auth::logout(); // Safely deletes session + session_regenerate_id for hijacking protection
    redirect('home');
}
```

## Identity Check and `auth()` Helper

```php
// Returns the authenticated user (Authenticatable). Null if not logged in.
$user = auth();

if ($user) {
    echo 'Welcome, ' . $user->name;
}

// Via Facade:
Auth::check();   // bool — logged in?
Auth::guest();   // bool — guest?
Auth::id();      // ?int — user ID
Auth::user();    // ?Authenticatable
```

## Protecting Routes

A ready-made `auth` middleware **does not come** in the starting skeleton. You have two ways:

**1) Control inside Controller:**
```php
public function index()
{
    if (! auth()) {
        flash('error', 'Please log in.');
        redirect('home');
        return;
    }
    // ...
}
```

**2) Create your own auth middleware:**
```bash
php umay make:middleware Auth
```
Check inside with `auth()`/`Auth::check()` and add it to the route:
```php
Route::get('/profile', 'ProfileController@index')->middleware('auth');
```

## API Authentication (Bearer Token)

The flow above is **session-based** web login. APIs are **stateless**: session and CSRF do not work; every request verifies itself with a **Bearer token** in the `Authorization` header. Umay provides this ready in the core.

Tokens are stored **hashed** (`sha256`) in the database; the plain text token is seen only **once at the time of creation** and cannot be retrieved again. An optional **ability (permission/scope)** list can be assigned to each token.

### 1) Setup

Run the migration for the token store:

```bash
php umay migrate   # creates personal_access_tokens table
```

Add the `Core\Auth\HasApiTokens` trait to the model that will produce tokens. The `App\Models\User` that comes at the start **already uses it**:

```php
use Core\Auth\HasApiTokens;

class User extends Model implements Authenticatable
{
    use HasApiTokens;
    // ...
}
```

### 2) Producing Tokens

```php
// With all permissions (default ['*'])
$result = $user->createToken('mobile-app');

// With specific permissions (scope)
$result = $user->createToken('report-service', ['posts.read', 'posts.write']);

// Give ONLY this to the client — it won't be shown again:
$plainTextToken = $result['plainTextToken']; // e.g.: "12|9f3c...e1"
$model          = $result['token'];          // PersonalAccessToken (DB record)
```

> [!WARNING]
> `plainTextToken` can only be obtained at this moment; only its hash is kept in the database. Show it to the user/transmit it securely, do not store it.

### 3) Protecting Routes

The `api-auth` middleware requires a valid token. You can mandate an **ability** using a colon:

```php
// routes/api.php
use Core\Route;

// Any valid token is enough
Route::get('/me', 'Api\\UserController@me')->middleware('api-auth');

// Token must have 'posts.write' permission
Route::post('/posts', 'Api\\PostController@store')->middleware('api-auth:posts.write');
```

A token with the `'*'` permission passes every ability check.

### 4) Client Side (Request)

The client sends the token in the `Authorization` header:

```http
GET /api/me HTTP/1.1
Host: example.com
Authorization: Bearer 12|9f3c...e1
Accept: application/json
```

Missing/invalid token returns `401`, token without permission returns `403` (both are JSON).

### 5) Accessing the Authenticated User

When `api-auth` is successful, the user is marked as authenticated **for that request** (no session is written). The same `auth()` / `Auth` API works:

```php
public function me()
{
    $user = auth();              // ?Authenticatable — token owner
    // or: Auth::user(), Auth::check(), Auth::id()

    // Token and permission check for the current request:
    if ($user->tokenCan('posts.write')) { /* ... */ }
    $token = $user->currentAccessToken(); // PersonalAccessToken|null

    return ['id' => $user->getAuthIdentifier(), 'name' => $user->name];
}
```

### 6) Token Revocation (Revoke)

```php
// Delete a single token
$user->tokens()->where('id', $tokenId)->delete();

// Cancel all tokens of the user (logout from everywhere)
$user->tokens()->delete();
```

> [!NOTE]
> API routes use the `api` group in `config/middleware.php` (Cors + throttle). Session and CSRF **do not work** on these routes — authentication is entirely token-based. CORS behavior is controlled by `cors_origin`, `cors_credentials`, etc. settings.

## Writing Your Own Login Logic (UserProvider)

If you want to authenticate the user from a different source (external API, LDAP, separate table...), implement the `Core\Contracts\UserProvider` and show it as a driver in `config/auth.php` — without touching the core:

```php
// app/Auth/ApiUserProvider.php
namespace App\Auth;

use Core\Contracts\Authenticatable;
use Core\Contracts\UserProvider;

class ApiUserProvider implements UserProvider
{
    public function retrieveById(int|string $id): ?Authenticatable { /* ... */ }
    public function retrieveByCredentials(array $credentials): ?Authenticatable { /* ... */ }
    public function validateCredentials(Authenticatable $user, array $credentials): bool { /* ... */ }
    public function retrieveByToken(int|string $id, string $token): ?Authenticatable { /* ... */ }
    public function updateRememberToken(Authenticatable $user, ?string $token): void { /* ... */ }
}
```

```php
// config/auth.php
'default' => 'api',
'providers' => [
    'api' => ['driver' => App\Auth\ApiUserProvider::class],
],
```

(End of file - total 245 lines)
