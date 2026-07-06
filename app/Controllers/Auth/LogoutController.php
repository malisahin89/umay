<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use Core\Facades\Auth;
use Core\Facades\Log;

class LogoutController
{
    public function handle(): void
    {
        $userId = Auth::id() ?? 'unknown';

        Auth::logout();

        // logout() destroys the session; restart one to carry the flash message.
        // logout() session'ı yok eder; flash mesajını taşımak için yeniden başlat.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        Log::info('User logout', ['user_id' => $userId]);

        flash('success', 'Çıkış yapıldı!');
        redirect('login.show');
    }
}
