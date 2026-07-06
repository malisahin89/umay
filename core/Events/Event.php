<?php

declare(strict_types=1);

namespace Core\Events;

/**
 * Base class for all event classes.
 * Tüm event sınıflarının base sınıfı.
 *
 * class UserRegistered extends Event
 * {
 *     public function __construct(public readonly User $user) {}
 * }
 *
 * event(new UserRegistered($user));
 */
abstract class Event
{
    /** Stop event propagation (with stopPropagation) // Event'in yayılmasını durdur (stopPropagation ile) */
    private bool $propagationStopped = false;

    public function stopPropagation(): void
    {
        $this->propagationStopped = true;
    }

    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }
}
