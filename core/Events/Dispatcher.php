<?php

declare(strict_types=1);

namespace Core\Events;

use Core\Container;
use Core\DebugBar;

/**
 * Event Dispatcher — global singleton event bus.
 *
 * Listener registration (usually in EventServiceProvider):
 * Listener kaydı (genellikle EventServiceProvider içinde):
 *   Dispatcher::listen(UserRegistered::class, SendWelcomeEmail::class);
 *   Dispatcher::listen(UserRegistered::class, LogNewUser::class);
 *   // Closure listener:
 *   Dispatcher::listen(UserRegistered::class, function(UserRegistered $e) {
 *       logger()->info('User registered: ' . $e->user->email);
 *   });
 *
 * Dispatch:
 *   event(new UserRegistered($user));
 *   Dispatcher::dispatch(new UserRegistered($user));
 *
 * Wildcard:
 *   Dispatcher::listen('*', function(Event $e) { ... }); // All events // Tüm event'ler
 */
class Dispatcher
{
    protected static ?self $instance = null;

    /** event class → [listener class|Closure, ...] */
    private array $listeners = [];

    private function __construct() {}

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    // ── Listener registration ────────────────────────────────────────────────
    // ── Listener kaydı ───────────────────────────────────────────────────────

    /**
     * Add a listener to an event class.
     * Event sınıfına listener ekle.
     *
     * @param  string  $eventClass  Event FQCN or '*' (wildcard) // Event FQCN veya '*' (wildcard)
     * @param  string|\Closure  $listener  Listener FQCN or Closure // Listener FQCN veya Closure
     */
    public static function listen(string $eventClass, string|\Closure $listener): void
    {
        static::getInstance()->listeners[$eventClass][] = $listener;
    }

    /**
     * Bulk register multiple event → listener mappings.
     * Birden fazla event → listener eşleşmesini toplu kaydet.
     *
     * Dispatcher::subscribe([
     *     UserRegistered::class => [SendWelcomeEmail::class, LogNewUser::class],
     *     PostPublished::class  => [NotifyFollowers::class],
     * ]);
     */
    public static function subscribe(array $map): void
    {
        foreach ($map as $eventClass => $listeners) {
            foreach ((array) $listeners as $listener) {
                static::listen($eventClass, $listener);
            }
        }
    }

    /**
     * One-time listener — removed after running once.
     * Tek kullanımlık listener — bir kez çalıştıktan sonra kaldırılır.
     */
    public static function once(string $eventClass, string|\Closure $listener): void
    {
        $wrapper = null;
        $wrapper = function (Event $event) use ($eventClass, $listener, &$wrapper) {
            static::getInstance()->callListener($listener, $event);
            static::forget($eventClass, $wrapper);
        };
        static::listen($eventClass, $wrapper);
    }

    /**
     * Remove all listeners registered to an event.
     * Bir event'e kayıtlı tüm listener'ları kaldır.
     */
    public static function forget(string $eventClass, ?\Closure $specific = null): void
    {
        $instance = static::getInstance();
        if ($specific === null) {
            unset($instance->listeners[$eventClass]);
        } else {
            $instance->listeners[$eventClass] = array_filter(
                $instance->listeners[$eventClass] ?? [],
                fn ($l) => $l !== $specific
            );
        }
    }

    // ── Dispatch ─────────────────────────────────────────────────────────────

    /**
     * Dispatch an event — run all registered listeners in order.
     * Event'i dispatch et — tüm kayıtlı listener'ları sırayla çalıştır.
     *
     * @return Event Dispatched event (for stopPropagation() check) // Dispatch edilen event (stopPropagation() kontrolü için)
     */
    public static function dispatch(Event $event): Event
    {
        if (class_exists(DebugBar::class) && DebugBar::isEnabled()) {
            DebugBar::addEvent(get_class($event), method_exists($event, 'toArray') ? $event->toArray() : null);
        }

        $instance = static::getInstance();
        $eventClass = get_class($event);

        // 1. Specific listeners
        // 1. Spesifik listener'lar
        foreach ($instance->listeners[$eventClass] ?? [] as $listener) {
            if ($event->isPropagationStopped()) {
                break;
            }
            $instance->callListener($listener, $event);
        }

        // 2. Wildcard listeners ('*')
        // 2. Wildcard listener'lar ('*')
        foreach ($instance->listeners['*'] ?? [] as $listener) {
            if ($event->isPropagationStopped()) {
                break;
            }
            $instance->callListener($listener, $event);
        }

        return $event;
    }

    /**
     * Check if a specific event has any registered listeners.
     * Belirli bir event'e kayıtlı listener var mı?
     */
    public static function hasListeners(string $eventClass): bool
    {
        $instance = static::getInstance();

        return ! empty($instance->listeners[$eventClass]) || ! empty($instance->listeners['*']);
    }

    /**
     * Clear all listeners (useful after tests).
     * Tüm listener'ları temizle (test sonrası kullanışlı).
     */
    public static function flush(): void
    {
        static::getInstance()->listeners = [];
    }

    // ── Internal ─────────────────────────────────────────────────────────────
    // ── Dahili ───────────────────────────────────────────────────────────────

    private function callListener(string|\Closure $listener, Event $event): void
    {
        if ($listener instanceof \Closure) {
            $listener($event);

            return;
        }

        // If it's a class name — resolve from Container (supports dependency injection)
        // Sınıf adıysa — Container'dan resolve et (inject desteği)
        if (class_exists($listener)) {
            $container = Container::getInstance();
            $instance = $container->make($listener);
            $instance->handle($event);

            return;
        }

        throw new \RuntimeException("Cannot resolve event listener // Event listener çözülemiyor: {$listener}");
    }
}
