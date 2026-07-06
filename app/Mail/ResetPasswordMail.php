<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\User;
use Core\Mail\Mailable;

/**
 * Password-reset notification.
 * Şifre sıfırlama bildirimi.
 *
 * Sent via the configured mailer (default: 'log' transport — writes to storage/logs).
 * Yapılandırılmış mailer ile gönderilir (varsayılan: 'log' transport — storage/logs'a yazar).
 *
 * Usage / Kullanım:
 *   Mail::to($user->email)->send(new ResetPasswordMail($user, $resetUrl));
 */
class ResetPasswordMail extends Mailable
{
    public function __construct(
        private User $user,
        private string $resetUrl,
    ) {}

    public function build(): static
    {
        return $this
            ->subject('Şifre Sıfırlama Talebi — Umay')
            ->view('emails/reset-password', [
                'name' => $this->user->name,
                'resetUrl' => $this->resetUrl,
            ]);
    }
}
