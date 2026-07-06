<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\User;
use Core\Events\Event;

class UserRegistered extends Event
{
    public function __construct(public readonly User $user) {}
}
