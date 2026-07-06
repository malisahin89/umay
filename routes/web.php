<?php

declare(strict_types=1);

use App\Support\RouteSlugs;
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
// One route group per locale with that locale's LOCALIZED path segments, so
// /es/productos, /de/kategorie/{slug}, /tr/ara/… all resolve to the same
// FrontController actions. Content slugs and page numbers stay as raw {params}.
// Her dil için o dilin LOCALIZED yol segmentleriyle bir route grubu; böylece
// /es/productos, /de/kategorie/{slug}, /tr/ara/… hepsi aynı FrontController
// action'larına gider. İçerik slug'ları ve sayfa numaraları ham {param} kalır.
$registerFront = static function (string $loc): void {
    // canonical route word → this locale's localized segment
    $s = static fn (string $canonical): string => RouteSlugs::seg($canonical, $loc);
    // locale:{loc} — literal-prefix groups carry no {locale} param, so hand the
    // active locale to LocaleMiddleware explicitly.
    Route::prefix('/'.$loc)->middleware('locale:'.$loc)->group(function () use ($s) {
        Route::get('/', 'FrontController@home');
        Route::get('/'.$s('page').'/{page}', 'FrontController@home');
        Route::get('/'.$s('blog'), 'FrontController@blog');
        Route::get('/'.$s('blog').'/'.$s('page').'/{page}', 'FrontController@blog');
        Route::get('/'.$s('search'), 'FrontController@search');
        Route::get('/'.$s('search').'/{query}', 'FrontController@search');
        Route::get('/'.$s('categories'), 'FrontController@categoriesIndex');
        Route::get('/'.$s('tags'), 'FrontController@tagsIndex');
        Route::get('/'.$s('category').'/{slug}/'.$s('page').'/{page}', 'FrontController@category');
        Route::get('/'.$s('category').'/{slug}', 'FrontController@category');
        Route::get('/'.$s('tag').'/{slug}/'.$s('page').'/{page}', 'FrontController@tag');
        Route::get('/'.$s('tag').'/{slug}', 'FrontController@tag');
        Route::get('/'.$s('products').'/'.$s('page').'/{page}', 'FrontController@products');
        Route::get('/'.$s('products'), 'FrontController@products');
        Route::get('/'.$s('products').'/{slug}', 'FrontController@product');
        Route::get('/'.$s('posts').'/{slug}', 'FrontController@post');
        Route::get('/'.$s('pages').'/{slug}', 'FrontController@page');
    });
};

foreach (RouteSlugs::locales() as $loc) {
    $registerFront($loc);
}

// Canonical fallback — registered LAST. Serves unknown locales (LocaleMiddleware
// redirects them to the default) and keeps the canonical English segments working.
// Named here (front.*) since these are the single source for Route::url().
// Kanonik fallback — EN SONA kaydedilir. Bilinmeyen dilleri karşılar
// (LocaleMiddleware onları varsayılana yönlendirir) ve kanonik İngilizce
// segmentleri çalışır tutar. İsimler burada (front.*) — Route::url() tek kaynağı.
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
