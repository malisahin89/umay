<?php

declare(strict_types=1);

namespace Core\Facades;

use Core\Events\Dispatcher;
use Core\Support\Facade;

/**
 * Event Facade — static proxy for Core\Events\Dispatcher.
 * Event Facade — Core\Events\Dispatcher için statik proxy.
 *
 * Usage / Kullanım:
 *   Event::dispatch(new UserRegistered($user));
 *   Event::listen(UserRegistered::class, SendWelcomeEmail::class);
 *   Event::subscribe([...]);
 *   Event::hasListeners(UserRegistered::class);
 *   Event::flush();
 *
 * @method static \Core\Events\Event dispatch(\Core\Events\Event $event)
 * @method static void listen(string $eventClass, string|\Closure $listener)
 * @method static void subscribe(array $map)
 * @method static void once(string $eventClass, string|\Closure $listener)
 * @method static void forget(string $eventClass, ?\Closure $specific = null)
 * @method static bool hasListeners(string $eventClass)
 * @method static void flush()
 *
 * @see Dispatcher
 */
class Event extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Dispatcher::class;
    }
}
