<?php

declare(strict_types=1);

use Core\Route;

$defaultLocale = is_string($d = config('locale.default')) ? $d : 'tr';

// ===== Home → default locale / Ana sayfa → varsayılan dil =====
Route::redirect('/', '/'.$defaultLocale)->name('home');

// ===== Public Auth Routes / Açık Auth Route'ları =====
Route::get('/register', 'Auth\\RegisterController@show')->name('register.show');
Route::post('/register', 'Auth\\RegisterController@store')->name('register.store');
Route::get('/login', 'Auth\\LoginController@show')->name('login.show');
Route::post('/login', 'Auth\\LoginController@authenticate')->middleware('throttle:5,300')->name('login.authenticate');
Route::post('/logout', 'Auth\\LogoutController@handle')->middleware('auth')->name('logout');

// ===== Dashboard (Requires Auth) / Dashboard (Auth gerekli) =====
Route::get('/dashboard', 'DashboardController@index')->middleware('auth')->name('dashboard');

// ===== DB Setup (local-only, APP_KEY protected — no console needed) =====
// Usage / Kullanım: /db-setup/{APP_KEY}?action=migrate|seed|fresh
Route::get('/db-setup/{key}', 'DbSetupController@handle')->name('db.setup');

// ===== Admin Panel (auth + admin) =====
Route::prefix('/admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', 'Admin\\DashboardController@index')->name('admin.dashboard');

    // Languages — specific routes BEFORE the generic {resource} block so they win.
    // Diller — spesifik route'lar generic {resource} bloğundan ÖNCE ki öncelikli olsun.
    Route::get('/languages', 'Admin\\LanguageController@index')->name('admin.languages');
    Route::get('/languages/create', 'Admin\\LanguageController@create')->name('admin.languages.create');
    Route::post('/languages', 'Admin\\LanguageController@store')->name('admin.languages.store');
    Route::get('/languages/{id}/edit', 'Admin\\LanguageController@edit')->name('admin.languages.edit');
    Route::post('/languages/{id}', 'Admin\\LanguageController@update')->name('admin.languages.update');
    Route::post('/languages/{id}/default', 'Admin\\LanguageController@setDefault')->name('admin.languages.default');
    Route::post('/languages/{id}/delete', 'Admin\\LanguageController@destroy')->name('admin.languages.delete');

    // Rich-text inline image upload — BEFORE the generic {resource} block, otherwise
    // "/admin/uploads/image" would match POST /admin/{resource}/{id} (resource=uploads).
    // Rich-text satır-içi görsel yükleme — generic {resource} bloğundan ÖNCE; aksi halde
    // "/admin/uploads/image" POST /admin/{resource}/{id}'ye (resource=uploads) düşerdi.
    Route::post('/uploads/image', 'Admin\\UploadController@image')->name('admin.uploads.image');

    // Generic translatable resources (posts, pages, categories, tags, products, slides, popups, menu-items)
    // Generic çevrilebilir kaynaklar
    Route::get('/{resource}', 'Admin\\ResourceController@index')->name('admin.resource.index');
    Route::get('/{resource}/create', 'Admin\\ResourceController@create')->name('admin.resource.create');
    Route::post('/{resource}', 'Admin\\ResourceController@store')->name('admin.resource.store');
    Route::get('/{resource}/{id}/edit', 'Admin\\ResourceController@edit')->name('admin.resource.edit');
    Route::post('/{resource}/{id}', 'Admin\\ResourceController@update')->name('admin.resource.update');
    Route::post('/{resource}/{id}/delete', 'Admin\\ResourceController@destroy')->name('admin.resource.delete');
});

// ===== Public localized front end / Herkese açık dilli ön yüz =====
// Registered LAST — the wildcard {locale} acts as a fallback so it never shadows
// the specific routes above.
// EN SONA kaydedilir — joker {locale} bir fallback'tir; yukarıdaki spesifik
// route'ları asla gölgelemez.
Route::prefix('/{locale}')->middleware('locale')->group(function () {
    Route::get('/', 'FrontController@home')->name('front.home');
    Route::get('/page/{page}', 'FrontController@home')->name('front.home.page');
    Route::get('/blog', 'FrontController@blog')->name('front.blog');
    Route::get('/blog/page/{page}', 'FrontController@blog')->name('front.blog.page');
    Route::get('/search', 'FrontController@search')->name('front.search');
    Route::get('/search/{query}', 'FrontController@search')->name('front.search.query');
    Route::get('/categories', 'FrontController@categoriesIndex')->name('front.categories');
    Route::get('/tags', 'FrontController@tagsIndex')->name('front.tags');
    Route::get('/category/{slug}/page/{page}', 'FrontController@category')->name('front.category.page');
    Route::get('/category/{slug}', 'FrontController@category')->name('front.category');
    Route::get('/tag/{slug}/page/{page}', 'FrontController@tag')->name('front.tag.page');
    Route::get('/tag/{slug}', 'FrontController@tag')->name('front.tag');
    Route::get('/products/page/{page}', 'FrontController@products')->name('front.products.page');
    Route::get('/products', 'FrontController@products')->name('front.products');
    Route::get('/products/{slug}', 'FrontController@product')->name('front.product');
    Route::get('/posts/{slug}', 'FrontController@post')->name('front.post');
    Route::get('/pages/{slug}', 'FrontController@page')->name('front.page');
});
