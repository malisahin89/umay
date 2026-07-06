<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\UserRegistered;
use Core\Events\Event;
use Core\Events\Listener;
use Core\Facades\Log;

class LogUserActivity extends Listener
{
    public function handle(Event $event): void
    {
        /** @var UserRegistered $event */
        Log::info('New user registered', [
            'user_id' => $event->user->id,
            'username' => $event->user->username,
            'role' => $event->user->role,
        ]);
    }
}
