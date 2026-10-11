# Dosya Raporu: core/Profiler/ProfilerController.php

## Amaç
Profiler verilerini görüntülemek için kontrolcü.

## Genel Bakış
Depolamadan belirli bir profil JSON dosyasını okuyan ve bunu ayrıntılı bir HTML raporu olarak render eden bir uç nokta (`/_profiler/{token}`) sağlar.

## Dosya Konumu
`core/Profiler/ProfilerController.php`

## Ad Alanı
`Core\Profiler`

## Sınıflar
- `class ProfilerController`

## Metotlar
- `index(Request $request): void`: Son profilleri listeleyen profiler dizin sayfasını render eder.
- `show(Request $request, string $token): void`: Belirli bir token için ayrıntılı raporu render eder.

## Bağımlılıklar
- `Core\Request` (Kullanır)
- `Core\Profiler\ProfilerStorage` (Kullanır)
- `Core\View` (Kullanır)

## Kaynak Referansları
- `core/Profiler/ProfilerController.php:1-120`
