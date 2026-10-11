# Dosya Raporu: core/ResourceRegistrar.php

## Amaç
RESTful kaynak rotalarını kaydetmek için yardımcı araç.

## Genel Bakış
Verilen bir kontrolcü için standart REST rotalarının (index, create, store, show, edit, update, destroy) oluşturulmasını otomatikleştirir. Görünümle ilgili rotaları (create, edit) hariç tutmak için `apiResource` desteği sunar.

## Dosya Konumu
`core/ResourceRegistrar.php`

## Ad Alanı
`Core`

## Sınıflar
- `class ResourceRegistrar`

## Özellikler
- `string $name`: Kaynak URI öneki.
- `string $controller`: Kontrolcü sınıfı.
- `bool $api`: Bunun bir API kaynağı olup olmadığı.
- `array $only`, `$except`: Hangi eylemlerin kaydedileceğine dair filtreler.
- `array $middlewares`: Tüm kaynak rotalarına uygulanacak middleware'ler.
- `string $prefix`: Kurulum anında yakalanan aktif grup öneki (ertelenmiş kayıt için).

## Metotlar
- `only(array $actions): static`: Kaydı belirli eylemlerle sınırlandırır.
- `except(array $actions): static`: Belirli eylemleri hariç tutar.
- `middleware(string|array $middleware): static`: Tüm kaynak rotalarına middleware ekler.
- `registerRoutes(): void`: Yakalanan öneki uygulayarak eylemleri `Route::get/post/...` çağrılarına dönüştüren dahili adım.
- `register(): void`: `__destruct()`'tan çağrılan final adım; kayıtları `Route::withPrefix($this->prefix, ...)` içinde çalıştırır.
- `__destruct()`: Tüm akıcı `only()`/`except()`/`middleware()` çağrıları bittikten sonra kaydı tetikler.

## Dahili İş Akışı
- **Ertelenmiş Kayıt**: `register()` metodu `__destruct()` metodu içinde çağrılır. Bu, tüm akıcı çağrıların (`only`, `except`, `middleware`) rotalar gerçekten `Route` kayıt defterine eklenmeden önce tamamlandığından emin olur ve önek yığınlarıyla ilgili sorunları önler.
- **Önek Yakalama**: Kurulumda aktif grup öneki `Route::currentPrefix()` ile `$prefix`'e alınır; kayıt sırasında `Route::withPrefix()` ile **tam olarak** (eklenerek değil) yeniden uygulanır, böylece kapsayan grup önekini pop'lamış olsa bile rotalar doğru önek altına düşer ve çift önek oluşmaz.
- **`update` PUT+PATCH**: `update` eylemi hem `PUT` hem `PATCH` için kaydedilir; yalnızca PUT kayıtlıyken gerçek bir PATCH isteği (veya `method_field('PATCH')` formu) 404 dönerdi.

## Bağımlılıklar
- `Core\Route` (Kullanır)

## Kaynak Referansları
- `core/ResourceRegistrar.php:1-156`
