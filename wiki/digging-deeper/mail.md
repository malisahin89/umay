# Mail (E-posta)

Umay'da mail **sen `Mailable` sınıfını yazarsın**, asıl gönderimi ise `config/mail.php`'de seçilen bir **transport** yapar.

Framework kutudan **yalnızca `log` transport'u** ile gelir — bu, maili gerçekten göndermez, `storage/logs`'a yazar (geliştirme/test için idealdir, SMTP gerektirmez). Gerçek gönderim (SMTP, API vb.) isteğe bağlıdır ve **kendi transport'unu yazarak** eklenir; çekirdeğe dokunman gerekmez (auth provider'larıyla aynı desen).

## 1. Mailable oluştur

```bash
php umay make:mail Welcome
```

Bu `app/Mail/WelcomeMail.php` üretir. İçeriği `build()` içinde tanımlarsın:

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
            ->subject('Hoş geldiniz!')
            ->from('noreply@site.com', 'Site Adı')   // opsiyonel — verilmezse config/mail.php 'from'
            ->view('emails/welcome', ['user' => $this->user]);
            // ->text('Merhaba ' . $this->user->name)  // düz metin alternatifi
            // ->attach('/path/rapor.pdf', 'rapor.pdf') // dosya eki
            // ->header('X-Custom', 'value')            // ekstra header
    }
}
```

> `->view('emails/welcome', [...])` şablonu `views/emails/welcome.php` dosyasından render edilir.

Mailable'da kullanabileceğin zincir metodları: `subject()`, `from()`, `view()`, `text()`, `attach()`, `header()`.

## 2. Mail gönder

```php
use App\Mail\WelcomeMail;
use Core\Mail\Mailer as Mail;   // veya global Mail alias'ı

// Controller içinde:
Mail::to($user->email)->send(new WelcomeMail($user));

// Ad ile:
Mail::to($user->email, $user->name)->send(new WelcomeMail($user));

// Çoklu alıcı + CC/BCC:
Mail::to(['a@b.com', 'c@d.com'])
    ->cc('mudur@site.com')
    ->bcc('arsiv@site.com')
    ->send(new WelcomeMail($user));
```

Varsayılan `log` transport'u ile bu çağrı maili **göndermez**, özetini `storage/logs/GÜN.log` dosyasına yazar:

```
[INFO] [Mail:log] App\Mail\WelcomeMail | Context: {"from":"noreply@site.com","to":"\"Ad\" <user@site.com>","subject":"Hoş geldiniz!","preview":"..."}
```

## 3. config/mail.php

```php
use Core\Mail\Transport\LogTransport;

return [
    // Aktif mailer — 'mailers' listesinden bir anahtar
    'default' => $_ENV['MAIL_MAILER'] ?? 'log',

    'mailers' => [
        'log' => ['transport' => LogTransport::class],
    ],

    // Mailable ->from() çağırmazsa kullanılan varsayılan gönderen
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
MAIL_FROM_NAME="Site Adı"
```

## 4. Gerçek gönderim — kendi transport'unu yaz

Gerçek e-posta göndermek için `Core\Contracts\MailTransport` arayüzünü implemente eden bir sınıf yaz. Burada `symfony/mailer` gibi bir kütüphane kullanman önerilir — framework SMTP'yi senin yerine yazmaz.

```php
// app/Mail/Transport/SmtpTransport.php
namespace App\Mail\Transport;

use Core\Contracts\MailTransport;
use Core\Mail\Mailable;

class SmtpTransport implements MailTransport
{
    /** @param array<string, mixed> $config  config/mail.php'deki mailer dizisi */
    public function __construct(private array $config = []) {}

    public function send(Mailable $mailable, array $to, array $cc = [], array $bcc = []): bool
    {
        // Erişebileceklerin:
        //   $this->config['host'], ['port'], ['username'], ['password'], ['encryption']
        //   $mailable->getSubject(), getFrom(), getFromName()
        //   $mailable->getHtmlBody(), getTextBody()
        //   $mailable->getAttachments(), getExtraHeaders()
        //
        // Örn. symfony/mailer ile gerçek SMTP gönderimi burada yapılır.

        return true;
    }
}
```

Sonra `config/mail.php`'ye bir mailer olarak ekle ve aktif et:

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

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=...
MAIL_PASSWORD=...
```

Artık `Mail::to(...)->send(...)` çağrıları `SmtpTransport` üzerinden gider. `log`'a geri dönmek için `MAIL_MAILER=log` yeterli — kod değişmez.

> **Neden SMTP kutudan gelmiyor?** Çünkü `mail()` ile yarım bir SMTP taklidi yerine, gerçek gönderimi olgun bir kütüphaneye (örn. `symfony/mailer`) bırakmak hem daha doğru hem daha esnek. Laravel de aynısını yapar (gönderimi Symfony Mailer'a devreder); fark, Umay'ın bu bağımlılığı zorunlu kılmayıp sana bırakmasıdır.
