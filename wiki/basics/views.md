# Görünümler (Views) ve Şablon Motoru

Umay Framework, view dosyaları için **Plates** isimli, tamamen yerel PHP (Native PHP) kodları kullanan harika bir şablon motoru kullanır. Blade veya Twig gibi yeni bir sözdizimi öğrenmenize gerek kalmaz, bildiğiniz PHP'yi yazarsınız ama çok daha düzenli bir mimaride.

Tüm view dosyaları `views/` dizininde `.php` uzantısıyla yer alır.

## Temel Kullanım

Bir view oluştururken değişkenleri `htmlspecialchars()` süzgecinden geçirerek (XSS koruması için) ekrana yazdırmak temel kuraldır.

`views/profile.php` dosyası:
```php
<h1>Hoş geldin, <?= $this->e($name) ?></h1>
```

Controller içinden çağırılışı:
```php
View::render('profile', ['name' => '<script>alert("XSS")</script>']);
// Çıktı güvenli bir şekilde encode edilecektir.
```

## Layout (Düzen) Kullanımı

Uygulamanızda tekrarlanan `<html>`, `<head>`, `<body>` taglarını, üst menü (header) ve alt kısımları (footer) bir ana Layout dosyasına almalısınız.

### Ana Layout Tanımlamak
Başlangıç iskeleti, `body` bölümü (section) olan hazır bir `views/layouts/base.php` ile gelir:
```php
<!DOCTYPE html>
<html lang="tr">
<head>
    <title><?= $this->e($title ?? 'Umay') ?></title>
</head>
<body>
    <!-- Alt sayfaların içeriği buraya gelecek -->
    <?= $this->section('body') ?>
</body>
</html>
```

### Alt Sayfalarda Layout Kullanmak
`views/home.php` dosyası:
```php
<?php $this->layout('layouts/base', ['title' => 'Ana Sayfa']) ?>

<?php $this->start('body') ?>
    <h1>Ana Sayfaya Hoş Geldiniz</h1>
    <p>Burası sayfanın içerik alanıdır.</p>
<?php $this->end() ?>
```

## Parçalar (Partials / Includes)

Tekrarlayan küçük HTML bloklarını (butonlar, uyarı kutuları, menüler) ayrı view dosyalarına alıp ana sayfaların içine dahil edebilirsiniz.

`views/partials/alert.php` dosyası:
```php
<div class="alert alert-<?= $this->e($type) ?>">
    <?= $this->e($message) ?>
</div>
```

Sayfa içinde çağırmak için `$this->insert()` kullanılır:
```php
<?= $this->insert('partials/alert', ['type' => 'danger', 'message' => 'Bir hata oluştu!']) ?>
```

## Global Veri Paylaşımı (View::share)

Bazı verileri (örneğin site adı, kullanıcı tercihleri veya global linkler) her view ve layout içinde tekrar tekrar tanımlamak yerine, tek bir noktadan tüm görünümlere dağıtabilirsiniz.

```php
// Uygulamanın başlangıcında (Provider veya Middleware içinde)
View::share('siteName', 'Umay Framework');
View::share(['langUrls' => ['tr' => '/tr', 'en' => '/en']]);
```

Paylaşılan bu verilere şablon içinde doğrudan erişebilirsiniz:
```php
<footer><?= $this->e($siteName) ?> &copy; <?= date('Y') ?></footer>
```

**Öncelik Sırası:** Paylaşılan veri < Sayfa verisi < Framework global'leri. Yani sayfa içinden gönderilen bir veri, paylaşılan aynı isimli veriyi ezer.
