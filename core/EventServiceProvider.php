<?php

declare(strict_types=1);

namespace Core;

use Core\Events\Dispatcher;

/**
 * EventServiceProvider base sınıfı.
 * App\Providers\EventServiceProvider bunu extend eder.
 *
 * // app/Providers/EventServiceProvider.php:
 * class EventServiceProvider extends \Core\EventServiceProvider
 * {
 *     protected array $listen = [
 *         UserRegistered::class => [
 *             SendWelcomeEmail::class,
 *             LogNewUser::class,
 *         ],
 *         PostPublished::class => [
 *             NotifyFollowers::class,
 *         ],
 *     ];
 * }
 */
abstract class EventServiceProvider extends ServiceProvider
{
    /**
     * Event → Listener eşleştirmesi.
     * Alt sınıf bu property'yi override eder.
     *
     * @var array<string, string[]>
     */
    protected array $listen = [];

    public function register(): void
    {
        // Event dispatcher singleton container'a kayıt
        $this->container->singleton(Dispatcher::class, fn () => Dispatcher::getInstance());
    }

    public function boot(): void
    {
        // $listen map'ini Dispatcher'a kaydet
        Dispatcher::subscribe($this->listen);
    }
}
