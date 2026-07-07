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

## Metotlar
- `only(array $actions): static`: Kaydı belirli eylemlerle sınırlandırır.
- `except(array $actions): static`: Belirli eylemleri hariç tutar.
- `middleware(string|array $middleware): static`: Tüm kaynak rotalarına middleware ekler.
- `register(): void`: `Route::get`, `Route::post` vb. metotlarını gerçekten çağıran final adımıdır.

## Dahili İş Akışı
- **Ertelenmiş Kayıt**: `register()` metodu `__destruct()` metodu içinde çağrılır. Bu, tüm akıcı çağrıların (`only`, `except`, `middleware`) rotalar gerçekten `Route` kayıt defterine eklenmeden önce tamamlandığından emin olur ve önek yığınlarıyla ilgili sorunları önler.

## Bağımlılıklar
- `Core\Route` (Kullanır)

## Kaynak Referansları
- `core/ResourceRegistrar.php:1-129`
