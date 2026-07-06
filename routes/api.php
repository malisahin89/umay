<?php

declare(strict_types=1);

/**
 * API Route Definitions
 *
 * All routes in this file automatically:
 *   - Use the 'api' middleware group (config/middleware.php)
 *   - Use the 'api_prefix' (default: /api) (config/middleware.php)
 *
 * So when you define '/posts' here, the actual URL is '/api/posts'.
 *
 * Session and CSRF DO NOT WORK on these routes (stateless).
 * Use the 'api-auth' middleware for authentication:
 *   Route::get('/me', 'Api\\UserController@me')->middleware('api-auth');
 *
 * Ability (permission) check:
 *   ->middleware('api-auth:posts.read')
 *
 *
 * API Route Tanımları
 *
 * Bu dosyadaki tüm route'lar otomatik olarak:
 *   - config/middleware.php → 'api' middleware grubuyla çalışır
 *   - config/middleware.php → 'api_prefix' (varsayılan: /api) prefix'i alır
 *
 * Yani burada '/posts' tanımladığınızda, gerçek URL '/api/posts' olur.
 *
 * Session ve CSRF bu route'larda ÇALIŞMAZ (stateless).
 * Kimlik doğrulama için 'api-auth' middleware kullanın:
 *   Route::get('/me', 'Api\\UserController@me')->middleware('api-auth');
 *
 * Ability (yetki) kontrolü:
 *   ->middleware('api-auth:posts.read')
 */

use App\Models\User;
use Core\Response;
use Core\Route;

// ── Public API Endpoints ────────────────────────────────────────────────────
// These routes do not require any authentication.
// Bu route'lar herhangi bir kimlik doğrulama gerektirmez.

// Example:
// Örnek:
// Route::get('/posts', 'Api\\PostController@index')->name('api.posts.index');
// Route::get('/posts/{id}', 'Api\\PostController@show')->name('api.posts.show');

// ── Authenticated API Endpoints ─────────────────────────────────────────────
// These routes require authentication with a Bearer token.
// Bu route'lar Bearer token ile kimlik doğrulama gerektirir.
// Header: Authorization: Bearer <token>
//
// Issue tokens from a model using \Core\Auth\HasApiTokens (already on App\Models\User)
// after running the personal_access_tokens migration:
//   ['plainTextToken' => $token] = $user->createToken('mobile', ['posts.read']);
// Token üretmek için \Core\Auth\HasApiTokens kullanın (App\Models\User'da hazır),
// personal_access_tokens migration'ını çalıştırdıktan sonra:
//   ['plainTextToken' => $token] = $user->createToken('mobile', ['posts.read']);

// Public: exchange credentials for a Bearer token.
// Açık: kimlik bilgilerini Bearer token ile takas et.
Route::post('/login', 'Api\\AuthController@login')->middleware('throttle:5,300')->name('api.login');

// Protected: requires Authorization: Bearer <token>.
// Korumalı: Authorization: Bearer <token> gerektirir.
Route::prefix('/user')->middleware('api-auth')->group(function () {
    Route::get('/me', 'Api\\UserController@me')->name('api.user.me');
});

// Ability-scoped example — token must carry 'users.view'.
// Ability-kapsamlı örnek — token 'users.view' taşımalı.
Route::get('/users-count', function () {
    return Response::json(['users' => User::query()->count()]);
})->middleware('api-auth:users.view')->name('api.users.count');

// ── API Resource Example ────────────────────────────────────────────────────
// Quick CRUD with apiResource():
//
// ── API Resource Örneği ─────────────────────────────────────────────────────
// apiResource() ile hızlı CRUD:
//   GET    /api/products       → index
//   POST   /api/products       → store
//   GET    /api/products/{id}  → show
//   PUT    /api/products/{id}  → update
//   DELETE /api/products/{id}  → destroy

// Route::apiResource('products', 'Api\\ProductController')->middleware('api-auth');
