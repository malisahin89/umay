# Umay Framework - Views Directory (Görünümler)

`views/` dizini uygulamanızın kullanıcıya yansıtılan yüzüdür (HTML dosyaları). Controller sınıflarında işlenen veriler bu dizindeki görünümlere aktarılır.

## Plates Şablon Motoru
Umay Framework, View katmanı için **League\Plates** native PHP şablon motorunu kullanır. Özel bir syntax (örn. Blade'deki `@if`) öğrenmenize gerek yoktur; düz PHP (`<?php if(...): ?>`) kullanırsınız. Ancak Plates, layout (şablon), partials (parçalar) ve data-sharing gibi güçlü özellikler sunar. Ayrıca sistem 18'den fazla zenginleştirilmiş yardımcı fonksiyon (`route`, `asset`, `auth`, `old` vb.) ile Plates motorunu genişletmiştir. `View::share()` ile tüm görünümlere global veri dağıtılabilir.

## Dizinin Yapısı
- **`layouts/`**: Ana HTML şablonları burada yer alır. İskelette `base.php` gelir; `body` adında bir bölüm (section) içerir, sayfa iskeletini barındırır.
- **`partials/`**: Birden fazla sayfada tekrar eden HTML blokları (header, footer, sidebar) burada tutulur. Ayrıca `alert`, `button`, `card`, `input` gibi 4 adet XSS korumalı, Component benzeri yeniden kullanılabilir UI bileşeni de bu dizinde örnek olarak yer alır.
- **`errors/`**: Hata sayfaları (`403.php`, `404.php`, `500.php`).
- **Diğer Dosyalar**: Controller'lardan (veya `Route::view` ile) doğrudan çağrılan sayfalardır (ör. iskelette gelen `welcome.php`).

## Kullanım Örneği

**Controller İçinde:**
```php
use Core\Facades\View;

View::render('home', ['name' => 'John']);
```

**`views/home.php` İçinde:**
```php
<?php $this->layout('layouts/base', ['title' => 'Ana Sayfa']) ?>

<?php $this->start('body') ?>
    <h1>Hoş Geldin, <?= $this->e($name) ?></h1>
<?php $this->end() ?>
```

`$this->e()` fonksiyonu XSS açıklarına karşı veriyi otomatik olarak temizler (escape eder).

## Partial (Bileşen) Kullanımı

```php
<?= $this->insert('partials::alert', ['type' => 'success', 'message' => 'İşlem başarılı!']) ?>
```
