# Dosya Raporu: core/Support/Facade.php

## Amaç
Tüm framework Facade'ları için temel sınıf.

## Genel Bakış
Alt katmandaki servisi `Container`'dan çözen ve istenen metodu üzerinde çağıran statik bir `__callStatic` metodu sağlayarak Facade desenini uygular. Çözümlenen örnekler `$resolvedInstances` içinde önbelleğe alınır.

## Dosya Konumu
`core/Support/Facade.php`

## Ad Alanı
`Core\Support`

## Sınıflar
- `class Facade` (soyut)

## Metotlar
- `getFacadeAccessor(): string`: Bu Facade'ın Container anahtarını veya sınıf adını döndüren soyut metot; her alt sınıf uygulamak zorundadır.
- `getFacadeRoot(): object`: Arka plandaki servis örneğini döndürür; daha önce çözümlendiyse önbellekten verir, aksi halde `Container::make()` (auto-wiring) ile çözer.
- `__callStatic(string $method, array $args): mixed`: Statik çağrıları yakalar, kök servisi konteynerdan çözer ve metodu yürütür.
- `swap(object $instance): void`: Test/mock için çözümlenmiş örneği değiştirir; Container binding'ini de güncelle ki diğer çözümleyiciler aynı örneği görsün.
- `clearResolvedInstances(): void`: Tüm çözümlenmiş Facade örneklerini temizler; ayrıca her accessor'ın arkasındaki Container singleton'ını `forgetInstance()` ile unutur, böylece `swap()` edilmiş bir mock veya bayat singleton sonraki teste sızmaz (testler arasında çağırın).
- `clearResolvedInstance(string $accessor): void`: Tek bir çözümlenmiş Facade örneğini temizler.

## Bağımlılıklar
- `Core\Container` (Kullanır)

## Kaynak Referansları
- `core/Support/Facade.php:1-161`
