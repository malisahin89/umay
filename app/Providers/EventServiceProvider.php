<?php

declare(strict_types=1);

namespace App\Providers;

use Core\EventServiceProvider as BaseEventServiceProvider;

class EventServiceProvider extends BaseEventServiceProvider
{
    /**
     * Event → Listener mapping.
     * Event → Listener eşleştirmesi.
     *
     * Multiple listeners can be defined for each event.
     * Her event için birden fazla listener tanımlanabilir.
     *
     * @var array<string, string[]>
     */
    protected array $listen = [
        // \App\Events\UserRegistered::class => [
        //     \App\Listeners\SendWelcomeEmail::class,
        // ],
    ];
}
