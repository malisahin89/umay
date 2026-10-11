<?php

declare(strict_types=1);

namespace Core\Events;

/**
 * Base class for all listener classes.
 * Tüm listener sınıflarının base sınıfı.
 *
 * class SendWelcomeEmail extends Listener
 * {
 *     public function handle(UserRegistered $event): void
 *     {
 *         Mail::to($event->user->email)->send(new WelcomeMail($event->user));
 *     }
 * }
 */
abstract class Listener
{
    /**
     * Process the event.
     * Event'i işle.
     *
     * Subclasses override with proper type-hinting.
     * Alt sınıf doğru type-hint ile override eder.
     */
    abstract public function handle(Event $event): void;
}
