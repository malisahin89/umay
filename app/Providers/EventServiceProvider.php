<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\UserRegistered;
use App\Listeners\LogUserActivity;
use App\Listeners\SendWelcomeEmail;
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
        UserRegistered::class => [
            // LogUserActivity runs first: the audit log must be guaranteed even if a
            // side-effect listener (mail) throws and aborts the chain.
            // LogUserActivity önce çalışır: yan etki listener'ı (mail) hata verip
            // zinciri kesse bile audit log garanti edilmelidir.
            LogUserActivity::class,
            SendWelcomeEmail::class,
        ],
    ];
}
