<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\UserRegistered;
use Core\Events\Event;
use Core\Events\Listener;
use Core\Facades\Log;

class SendWelcomeEmail extends Listener
{
    public function handle(Event $event): void
    {
        /** @var UserRegistered $event */
        // A real app would dispatch a Mailable here, e.g.:
        // Gerçek uygulama burada bir Mailable gönderirdi, örn.:
        //   Mail::to($event->user->email)->send(new WelcomeMail($event->user));
        Log::info('Welcome email queued', ['user_id' => $event->user->id]);
    }
}
