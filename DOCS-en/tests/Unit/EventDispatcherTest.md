# File Report: tests/Unit/EventDispatcherTest.php

## Purpose
Unit tests for the event dispatcher.

## Overview
Verifies `Core\Events\Dispatcher`: closure listeners are invoked and receive event data, multiple listeners all fire, propagation can be stopped, wildcard listeners receive all events, `hasListeners` reflects registration, `flush` clears listeners, and `once` listeners fire only once. Defines `OrderPlaced` and `PaymentReceived` event fixtures.

## File Location
`tests/Unit/EventDispatcherTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class OrderPlaced extends Core\Events\Event` (`:13`)
- `class PaymentReceived extends Core\Events\Event` (`:18`)
- `class EventDispatcherTest extends Tests\TestCase` (`:23`)

## Subject Under Test
- `Core\Events\Dispatcher`, `Core\Events\Event`

## Test Methods
- `test_closure_listener_is_called` — `:31`
- `test_listener_receives_event_data` — `:42`
- `test_multiple_listeners_all_called` — `:53`
- `test_stop_propagation_halts_remaining_listeners` — `:67`
- `test_wildcard_listener_receives_all_events` — `:82`
- `test_has_listeners_returns_false_when_no_listeners` — `:96`
- `test_has_listeners_returns_true_after_register` — `:101`
- `test_flush_clears_all_listeners` — `:107`
- `test_once_listener_called_only_once` — `:114`

## Cross References
- **Tests:** `Core\Events\Dispatcher` (see `DOCS/core/Events/Dispatcher.md`), `Core\Events\Event` (see `DOCS/core/Events/Event.md`)

## Source References
- `tests/Unit/EventDispatcherTest.php:1-126`
