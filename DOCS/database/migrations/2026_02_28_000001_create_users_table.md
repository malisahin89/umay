# Dosya Raporu: database/migrations/2026_02_28_000001_create_users_table.php

## Amaç
`users` tablosunu oluşturma migrasyonu.

## Genel Bakış
`id`, `name`, `email` (benzersiz), `password`, `remember_token` ve zaman damgaları sütunları ile `users` tablosunu oluşturur. Veritabanları arası uyumluluk için Eloquent Şema oluşturucuyu kullanır.

## Dosya Konumu
`database/migrations/2026_02_28_000001_create_users_table.php`

## Uygulama
- `up()`: Eğer yoksa `users` tablosunu oluşturur.
- `down()`: `users` tablosunu siler.

## Kaynak Referansları
- `database/migrations/2026_02_28_000001_create_users_table.php:1-36`
