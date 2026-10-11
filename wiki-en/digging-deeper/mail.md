# Mail (Email)

In Umay, **you write the `Mailable` class**, and a **transport** selected in `config/mail.php` handles the actual delivery.

The framework comes out of the box **only with the `log` transport** — this does not actually send the mail, it writes it to `storage/logs` (ideal for development/testing, no SMTP required). Actual delivery (SMTP, API, etc.) is optional and added by **writing your own transport**; you don't need to touch the core (same pattern as auth providers).

## 1. Create a Mailable

```bash
php umay make:mail Welcome
```

This produces `app/Mail/WelcomeMail.php`. You define the content inside `build()`:

```php
namespace App\Mail;

use App\Models\User;
use Core\Mail\Mailable;

class WelcomeMail extends Mailable
{
    public function __construct(private User $user) {}

    public function build(): static
    {
        return $this
            ->subject('Welcome!')
            ->from('noreply@site.com', 'Site Name')   // optional — if not given, uses config/mail.php 'from'
            ->view('emails/welcome', ['user' => $this->user]);
            // ->text('Hello ' . $this->user->name)  // plain text alternative
            // ->attach('/path/report.pdf', 'report.pdf') // file attachment
            // ->header('X-Custom', 'value')            // extra header
    }
}
```

> The `->view('emails/welcome', [...])` template is rendered from the `views/emails/welcome.php` file.

Chainable methods you can use in Mailable: `subject()`, `from()`, `view()`, `text()`, `attach()`, `header()`.

## 2. Send Mail

```php
use App\Mail\WelcomeMail;
use Core\Mail\Mailer as Mail;   // or global Mail alias

// Inside a Controller:
Mail::to($user->email)->send(new WelcomeMail($user));

// With name:
Mail::to($user->email, $user->name)->send(new WelcomeMail($user));

// Multiple recipients + CC/BCC:
Mail::to(['a@b.com', 'c@d.com'])
    ->cc('manager@site.com')
    ->bcc('archive@site.com')
    ->send(new WelcomeMail($user));
```

With the default `log` transport, this call **does not send** the mail, it writes the summary to the `storage/logs/DATE.log` file:

```
[INFO] [Mail:log] App\Mail\WelcomeMail | Context: {"from":"noreply@site.com","to":"\"Name\" <user@site.com>","subject":"Welcome!","preview":"..."}
```

## 3. config/mail.php

```php
use Core\Mail\Transport\LogTransport;

return [
    // Active mailer — a key from the 'mailers' list
    'default' => $_ENV['MAIL_MAILER'] ?? 'log',

    'mailers' => [
        'log' => ['transport' => LogTransport::class],
    ],

    // Default sender if Mailable ->from() is not called
    'from' => [
        'address' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@localhost',
        'name' => $_ENV['MAIL_FROM_NAME'] ?? 'Umay',
    ],
];
```

`.env`:
```
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@site.com
MAIL_FROM_NAME="Site Name"
```

## 4. Real Delivery — Write Your Own Transport

To send real emails, write a class that implements the `Core\Contracts\MailTransport` interface. It is recommended to use a library like `symfony/mailer` here — the framework doesn't write SMTP for you.

```php
// app/Mail/Transport/SmtpTransport.php
namespace App\Mail\Transport;

use Core\Contracts\MailTransport;
use Core\Mail\Mailable;

class SmtpTransport implements MailTransport
{
    /** @param array<string, mixed> $config  the mailer array in config/mail.php */
    public function __construct(private array $config = []) {}

    public function send(Mailable $mailable, array $to, array $cc = [], array $bcc = []): bool
    {
        // You can access:
        //   $this->config['host'], ['port'], ['username'], ['password'], ['encryption']
        //   $mailable->getSubject(), getFrom(), getFromName()
        //   $mailable->getHtmlBody(), getTextBody()
        //   $mailable->getAttachments(), getExtraHeaders()
        //
        // E.g., real SMTP delivery with symfony/mailer is done here.

        return true;
    }
}
```

Then add it as a mailer to `config/mail.php` and activate it:

```php
use App\Mail\Transport\SmtpTransport;
use Core\Mail\Transport\LogTransport;

return [
    'default' => $_ENV['MAIL_MAILER'] ?? 'smtp',

    'mailers' => [
        'log' => ['transport' => LogTransport::class],

        'smtp' => [
            'transport' => SmtpTransport::class,
            'host' => $_ENV['MAIL_HOST'] ?? '127.0.0.1',
            'port' => (int) ($_ENV['MAIL_PORT'] ?? 587),
            'username' => $_ENV['MAIL_USERNAME'] ?? '',
            'password' => $_ENV['MAIL_PASSWORD'] ?? '',
            'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'tls',
        ],
    ],

    'from' => [
        'address' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@localhost',
        'name' => $_ENV['MAIL_FROM_NAME'] ?? 'Umay',
    ],
];
```

`.env`:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=...
MAIL_PASSWORD=...
```

Now `Mail::to(...)->send(...)` calls go via `SmtpTransport`. To go back to `log`, just set `MAIL_MAILER=log` — no code change.

> **Why doesn't SMTP come out of the box?** Because instead of a half-baked SMTP imitation with `mail()`, it is more correct and flexible to leave real delivery to a mature library (e.g., `symfony/mailer`). Laravel does the same (delegates delivery to Symfony Mailer); the difference is that Umay doesn't make it a mandatory dependency and leaves it to you.
