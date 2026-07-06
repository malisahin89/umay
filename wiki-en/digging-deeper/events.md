# Events & Listeners

The Event architecture allows you to decouple actions that occur in your application.

For example, when a new member registers in the system, you may want to send them a welcome email. Instead of writing this code inside your registration controller, it provides a much cleaner architecture to fire a "User Registered" event and create an independent Listener that listens to it.

## Dispatching Events

You can start an event anywhere by calling the `Core\Facades\Event` class.

```php
use App\Events\UserRegistered;
use Core\Facades\Event;

// Inside a Controller:
public function store(Request $request)
{
    // ...User is saved to database
    
    // Fire the Event! 
    Event::dispatch(new UserRegistered($user));
}
```

Event classes derive from the `Core\Events\Event` class and carry data via properties:

```php
// app/Events/UserRegistered.php
class UserRegistered extends Event
{
    public function __construct(public readonly User $user) {}
}
```

## Defining Listeners

Listeners are added to the `$listen` array in `app/Providers/EventServiceProvider.php`:

```php
protected array $listen = [
    UserRegistered::class => [
        SendWelcomeEmail::class,
        LogUserActivity::class,
    ],
];
```

Listener classes receive the event via the `handle(Event $event)` method:

```php
// app/Listeners/SendWelcomeEmail.php
public function handle(Event $event): void
{
    /** @var UserRegistered $event */
    $mailer = new WelcomeMail($event->user);
    $mailer->sendTo($event->user->email);
}
```

Multiple Listeners can listen to the same Event. They are executed sequentially the moment the event is fired.
