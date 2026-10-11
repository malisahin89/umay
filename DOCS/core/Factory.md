# Dosya Raporu: core/Factory.php

## Amaç
Model Fabrikaları için temel sınıf.

## Genel Bakış
Testler veya seeding için sahte model örnekleri oluşturmak ve bunları veritabanına kaydetmek için akıcı bir API sağlar. Varsayılan tanımı değiştirmek için "durumları" (states) destekler.

## Dosya Konumu
`core/Factory.php`

## Ad Alanı
`Core`

## Sınıflar
- `abstract class Factory`
- `class FakerProxy` (Sahte veri oluşturmak için dahili yardımcı)

## Özellikler
- `string $model`: Bu fabrikanın oluşturduğu modelin FQCN'i.
- `int $count`: Oluşturulacak örnek sayısı.
- `array $states`: Öznitelik geçersiz kılma kuyruğu (durumlar).

## Metotlar
- `definition(): array`: Varsayılan öznitelik setini döndüren soyut metot.
- `count(int $count): static`: Oluşturulacak öğe sayısını ayarlar.
- `state(array $attributes): static`: Durum kuyruğuna bir öznitelik geçersiz kılma ekler.
- `make(array $override = []): mixed`: Model örneklerini veritabanına kaydetmeden oluşturur.
- `create(array $override = []): mixed`: Model örneklerini oluşturur ve veritabanına kaydeder.
- `raw(array $override = []): array`: Model oluşturmadan çözümlenmiş öznitelik dizisini döndürür.
- `register(string $modelClass, string $factoryClass): void`: Bir modeli açıkça bir fabrika sınıfına eşler.
- `forModel(string $modelClass, int $count = 1): static`: Kayıt defterini veya adlandırma kurallarını kullanarak verilen model için bir fabrika çözer.
- `faker(): FakerProxy`: Rastgele veri oluşturmak için bir proxy döndürür.

## Dahili İş Akışı
1. `resolveAttributes()`: Varsayılan `definition()`, uygulanan tüm `states` ve sağlanan `$override` dizisini birleştirir.
2. `newModel()`: Modeli örneklendirir ve korumalı (guarded) alanların da ayarlanmasını sağlayarak `forceFill()` kullanır.

## Bağımlılıklar
- `Core\FakerProxy` (Kullanır)

## Kaynak Referansları
- `core/Factory.php:1-281`
