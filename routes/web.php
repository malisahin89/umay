<?php

declare(strict_types=1);

use Core\Route;

// ===== Home =====
Route::view('/', 'welcome', ['title' => 'Umay Framework — Modern PHP MVC'])->name('home');

// ===== Your application routes =====
// ===== Uygulama route'larınız =====
//
// Route::get('/about', fn () => 'About page');
// Route::get('/users/{id}', 'UserController@show')->name('users.show');
// Route::post('/users', 'UserController@store')->middleware('throttle:10,60');
// Route::resource('posts', 'PostController');
//
// Group example / Grup örneği:
// Route::prefix('/admin')->middleware('auth')->group(function () {
//     Route::get('/', 'AdminController@index')->name('admin.dashboard');
// });
