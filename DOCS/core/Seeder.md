# Dosya Raporu: core/Seeder.php

## Amaç
Veritabanı seed'leri için temel sınıf.

## Genel Bakış
Veritabanını başlangıç veya sahte verilerle doldurmak için bir yapı sağlar. Seeder'lar, veri doldurma işlemini organize etmek için diğer seeder'ları çağırabilir.

## Dosya Konumu
`core/Seeder.php`

## Ad Alanı
`Core`

## İçe Aktarmalar
- `Illuminate\Database\Capsule\Manager as DB`

## Sınıflar
- `abstract class Seeder`

## Metotlar
- `run(): void`: Seed mantığının uygulandığı soyut metot.
- `call(string|array $seederClass): void`: Diğer seeder sınıflarını çalıştırır.
- `insert(string $table, array $data): void`: Bir tabloya ham veri ekler.
- `truncateAndInsert(string $table, array $rows): void`: Bir tabloyu temizler (yabancı anahtarları yoksayarak) ve yeni satırlar ekler.
- `count(string $table): int`: Bir tablodaki kayıt sayısını döndürür.

## Kaynak Referansları
- `core/Seeder.php:1-69`
