# Dosya Raporu: database/factories/UserFactory.php

## Amaç
Sahte Kullanıcı modelleri üretmek için fabrika.

## Genel Bakış
Rastgele isimler ve benzersiz e-postalar üretmek için bir faker proxy kullanarak `User` modeli için bir dizi varsayılan öznitelik tanımlar.

## Dosya Konumu
`database/factories/UserFactory.php`

## Ad Alanı
`Database\Factories`

## Sınıflar
- `class UserFactory extends Factory`

## Metotlar
- `definition(): array`: Varsayılan öznitelikleri döndürür: `name` (rastgele), `email` (benzersiz rastgele) ve `password` (statik 'password').

## Bağımlılıklar
- `App\Models\User` (Hedef Model)
- `Core\Factory` (Genişletir)

## Kaynak Referansları
- `database/factories/UserFactory.php:1-25`
