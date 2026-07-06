# Olaylar ve Dinleyiciler (Events & Listeners)

Event (Olay) mimarisi, uygulamanızda gerçekleşen eylemleri birbirinden soyutlamanızı (Decoupling) sağlar.

Örneğin, sisteme yeni bir üye kayıt olduğunda ona bir hoşgeldin e-postası atmak isteyebilirsiniz. Bu kodu kayıt controller'ınızın içine yazmak yerine, bir "Kullanıcı Kayıt Oldu" olayı (Event) fırlatıp, bunu dinleyen bağımsız bir Listener (Dinleyici) oluşturmak çok daha temiz bir mimari sunar.

## Event Fırlatmak (Dispatch)

Herhangi bir yerde `Core\Facades\Event` sınıfını çağırarak bir olay başlatabilirsiniz.

```php
use App\Events\UserRegistered;
use Core\Facades\Event;

// Controller içinde:
public function store(Request $request)
{
    // ...Kullanıcı veritabanına kaydedilir
    
    // Olayı Fırlat! 
    Event::dispatch(new UserRegistered($user));
}
```

Event sınıfları `Core\Events\Event` sınıfından türer ve property'ler aracılığıyla veri taşır:

```php
// app/Events/UserRegistered.php
class UserRegistered extends Event
{
    public function __construct(public readonly User $user) {}
}
```

## Listener Tanımlamak

Listener'lar `app/Providers/EventServiceProvider.php` içindeki `$listen` dizisine eklenir:

```php
protected array $listen = [
    UserRegistered::class => [
        SendWelcomeEmail::class,
        LogUserActivity::class,
    ],
];
```

Listener sınıfları `handle(Event $event)` metoduyla olayı alır:

```php
// app/Listeners/SendWelcomeEmail.php
public function handle(Event $event): void
{
    /** @var UserRegistered $event */
    $mailer = new WelcomeMail($event->user);
    $mailer->sendTo($event->user->email);
}
```

Birden çok Listener aynı Event'i dinleyebilir. Olay fırlatıldığı anda sırasıyla çalıştırılır.
