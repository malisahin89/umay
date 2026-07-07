# Dosya Raporu: tests/Unit/EventDispatcherTest.php

## Amaç
Olay dağıtıcısı (event dispatcher) için birim (unit) testler.

## Genel Bakış
`Core\Events\Dispatcher`'ı doğrular: closure dinleyicileri çağrılır ve olay verilerini alır, birden fazla dinleyici tetiklenir, yayılım durdurulabilir, wildcard dinleyiciler tüm olayları alır, `hasListeners` kaydı yansıtır, `flush` dinleyicileri temizler ve `once` dinleyicileri yalnızca bir kez çalışır. `OrderPlaced` ve `PaymentReceived` olay fixture'larını tanımlar.

## Dosya Konumu
`tests/Unit/EventDispatcherTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class OrderPlaced extends Core\Events\Event` (`:13`)
- `class PaymentReceived extends Core\Events\Event` (`:18`)
- `class EventDispatcherTest extends Tests\TestCase` (`:23`)

## Test Edilen Konu
- `Core\Events\Dispatcher`, `Core\Events\Event`

## Test Metotları
- `test_closure_listener_is_called` — `:31`
- `test_listener_receives_event_data` — `:42`
- `test_multiple_listeners_all_called` — `:53`
- `test_stop_propagation_halts_remaining_listeners` — `:67`
- `test_wildcard_listener_receives_all_events` — `:82`
- `test_has_listeners_returns_false_when_no_listeners` — `:96`
- `test_has_listeners_returns_true_after_register` — `:101`
- `test_flush_clears_all_listeners` — `:107`
- `test_once_listener_called_only_once` — `:114`

## Çapraz Referanslar
- **Test Eder:** `Core\Events\Dispatcher` (bkz. `DOCS/core/Events/Dispatcher.md`), `Core\Events\Event` (bkz. `DOCS/core/Events/Event.md`)

## Kaynak Referansları
- `tests/Unit/EventDispatcherTest.php:1-126`
