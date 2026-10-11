<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Events\Dispatcher;
use Core\Events\Event;
use Tests\TestCase;

// Test event classes
// Test event sınıfları
class OrderPlaced extends Event
{
    public function __construct(public readonly int $orderId) {}
}

class PaymentReceived extends Event
{
    public function __construct(public readonly float $amount) {}
}

class EventDispatcherTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Dispatcher::flush();
    }

    public function test_closure_listener_is_called(): void
    {
        $called = false;
        Dispatcher::listen(OrderPlaced::class, function (OrderPlaced $e) use (&$called) {
            $called = true;
        });

        Dispatcher::dispatch(new OrderPlaced(42));
        $this->assertTrue($called);
    }

    public function test_listener_receives_event_data(): void
    {
        $receivedId = null;
        Dispatcher::listen(OrderPlaced::class, function (OrderPlaced $e) use (&$receivedId) {
            $receivedId = $e->orderId;
        });

        Dispatcher::dispatch(new OrderPlaced(99));
        $this->assertEquals(99, $receivedId);
    }

    public function test_multiple_listeners_all_called(): void
    {
        $calls = [];
        Dispatcher::listen(OrderPlaced::class, function (OrderPlaced $e) use (&$calls) {
            $calls[] = 'first';
        });
        Dispatcher::listen(OrderPlaced::class, function (OrderPlaced $e) use (&$calls) {
            $calls[] = 'second';
        });

        Dispatcher::dispatch(new OrderPlaced(1));
        $this->assertEquals(['first', 'second'], $calls);
    }

    public function test_stop_propagation_halts_remaining_listeners(): void
    {
        $calls = [];
        Dispatcher::listen(OrderPlaced::class, function (OrderPlaced $e) use (&$calls) {
            $calls[] = 'first';
            $e->stopPropagation();
        });
        Dispatcher::listen(OrderPlaced::class, function (OrderPlaced $e) use (&$calls) {
            $calls[] = 'second'; // This should not run // Bu çalışmamalı
        });

        Dispatcher::dispatch(new OrderPlaced(1));
        $this->assertEquals(['first'], $calls);
    }

    public function test_wildcard_listener_receives_all_events(): void
    {
        $received = [];
        Dispatcher::listen('*', function (Event $e) use (&$received) {
            $received[] = get_class($e);
        });

        Dispatcher::dispatch(new OrderPlaced(1));
        Dispatcher::dispatch(new PaymentReceived(99.9));

        $this->assertContains(OrderPlaced::class, $received);
        $this->assertContains(PaymentReceived::class, $received);
    }

    public function test_has_listeners_returns_false_when_no_listeners(): void
    {
        $this->assertFalse(Dispatcher::hasListeners(OrderPlaced::class));
    }

    public function test_has_listeners_returns_true_after_register(): void
    {
        Dispatcher::listen(OrderPlaced::class, fn ($e) => null);
        $this->assertTrue(Dispatcher::hasListeners(OrderPlaced::class));
    }

    public function test_flush_clears_all_listeners(): void
    {
        Dispatcher::listen(OrderPlaced::class, fn ($e) => null);
        Dispatcher::flush();
        $this->assertFalse(Dispatcher::hasListeners(OrderPlaced::class));
    }

    public function test_once_listener_called_only_once(): void
    {
        $count = 0;
        Dispatcher::once(OrderPlaced::class, function (OrderPlaced $e) use (&$count) {
            $count++;
        });

        Dispatcher::dispatch(new OrderPlaced(1));
        Dispatcher::dispatch(new OrderPlaced(2));

        $this->assertEquals(1, $count);
    }
}
