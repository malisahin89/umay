# Dosya Raporu: core/Paginator.php

## Amaç
Navigasyon bağlantılarını oluşturmak için sayfalama (pagination) aracı.

## Genel Bakış
Eloquent'in `LengthAwarePaginator`'ını veya ham verileri sarmalayarak Bootstrap 5 uyumlu sayfalama HTML'i oluşturur. Sayfa aralığı hesaplamalarını (kayan pencere) yönetir ve sorgu parametrelerini korur.

## Dosya Konumu
`core/Paginator.php`

## Ad Alanı
`Core`

## İçe Aktarmalar
- `Illuminate\Contracts\Pagination\LengthAwarePaginator`

## Sınıflar
- `class Paginator`

## Özellikler
- `int $currentPage`: Mevcut aktif sayfa.
- `int $lastPage`: Toplam sayfa sayısı.
- `int $perPage`: Sayfa başına öğe sayısı.
- `int $total`: Toplam öğe sayısı.
- `mixed $items`: Gerçek veri öğeleri.
- `string $path`: Temel URL yolu.
- `array $queryParams`: 'page' hariç sorgu parametreleri.

## Metotlar
- `fromEloquent(LengthAwarePaginator $paginator): static`: Eloquent sayfalama sonucundan bir `Paginator` oluşturur.
- `make(mixed $items, int $total, int $perPage = 15, ?int $currentPage = null): static`: Ham verilerden bir `Paginator` oluşturur.
- `links(string $style = 'bootstrap'): string`: Sayfalama HTML'ini oluşturur ('bootstrap' veya 'simple').
- `pageUrl(int $page): string`: Diğer sorgu parametrelerini koruyarak belirli bir sayfa için URL oluşturur.
- `getPageRange(): array`: Hangi sayfa numaralarının gösterileceğini hesaplar (her zaman ilk, son ve mevcut ±2).

## Kaynak Referansları
- `core/Paginator.php:1-288`
