<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Models\User;
use Core\Csrf;
use Core\Facades\Auth;
use Core\Facades\Log;
use Core\Facades\View;
use Core\Request;

class LoginController
{
    public function show(): void
    {
        if (Auth::check()) {
            redirect('dashboard');

            return;
        }

        View::render('auth/login');
    }

    public function authenticate(Request $request): void
    {
        if (Auth::check()) {
            redirect('dashboard');

            return;
        }

        $email = trim(is_string($e = $request->post('email')) ? $e : '');
        $password = is_string($p = $request->post('password')) ? $p : '';

        if ($email === '' || $password === '') {
            flash('error', 'E-posta ve şifre gereklidir!');
            redirect('login.show');

            return;
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Geçerli bir e-posta adresi girin!');
            redirect('login.show');

            return;
        }

        $remember = $request->post('remember-me') === 'on';

        if (Auth::attempt(['email' => $email, 'password' => $password, 'remember' => $remember])) {
            // Rotate the CSRF token after a privilege change (login).
            // Yetki değişiminden (login) sonra CSRF token'ı döndür.
            unset($_SESSION['csrf_token']);
            Csrf::generate();

            /** @var User $user */
            $user = Auth::user();

            Log::info('Successful login', [
                'user_id' => $user->id,
                'role' => $user->role,
            ]);

            flash('success', 'Giriş başarılı, hoş geldin '.htmlspecialchars($user->name).'!');
            redirect('dashboard');

            return;
        }

        Log::warning('Failed login attempt', ['ip' => $request->ip()]);

        flash('error', 'E-posta veya şifre hatalı!');
        redirect('login.show');
    }
}
