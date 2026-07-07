# Dosya Raporu: core/Events/Dispatcher.php

## Amaç
Uygulama için küresel olay veri yolu (event bus).

## Genel Bakış
Olayların kaydını ve dağıtımını yönetir. Bir veya daha fazla dinleyicinin yanıt verebileceği olayları tetikleyerek, bağımsız bileşenlerin birbirleriyle iletişim kurmasını sağlar.

## Dosya Konumu
`core/Events/Dispatcher.php`

## Ad Alanı
`Core\Events`

## Sınıflar
- `class Dispatcher`

## Özellikler
- `static ?self $instance`: Dağıtıcının singleton örneği.
- `array $listeners`: Olay sınıflarının kayıtlı dinleyicileriyle eşleşmesi.

## Metotlar
- `getInstance(): self`: Singleton örneğini döndürür.
- `listen(string $eventClass, string|\Closure $listener): void`: Belirli bir olay veya joker karakter `*` (tüm olaylar) için bir dinleyici kaydeder.
- `subscribe(array $map): void`: Birden fazla olay-dinleyici eşlemesini toplu olarak kaydeder.
- `once(string $eventClass, string|\Closure $listener): void`: İlk yürütmeden sonra kaldırılacak bir dinleyici kaydeder.
- `forget(string $eventClass, ?\Closure $specific = null): void`: Belirli bir olay için dinleyicileri kaldırır.
- `dispatch(Event $event): Event`: Bir olayı tetikler ve kayıtlı tüm dinleyicileri sırayla yürütür.
- `hasListeners(string $eventClass): bool`: Bir olayın herhangi bir kayıtlı dinleyicisi olup olmadığını kontrol eder.
- `flush(): void`: Tüm kayıtlı dinleyicileri temizler.

## Dahili İş Akışı
- **Yürütme**: `dispatch()` çağrıldığında, dağıtıcı önce o olay sınıfına özgü dinleyicileri, ardından joker karakterli dinleyicileri yürütür. Olay nesnesi üzerinde çağrılan `stopPropagation()` metoduna saygı duyar.
- **Dinleyici Çözümleme**: Eğer bir dinleyici sınıf adı olarak sağlanmışsa, bağımlılık enjeksiyonunu desteklemek için `Container` üzerinden çözümlenir.

## Bağımlılıklar
- `Core\Container` (Kullanır)
- `Core\DebugBar` (İsteğe bağlı profilleme)

## Kaynak Referansları
- `core/Events/Dispatcher.php:1-192`
