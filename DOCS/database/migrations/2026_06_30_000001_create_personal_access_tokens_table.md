# Dosya Raporu: database/migrations/2026_06_30_000001_create_personal_access_tokens_table.php

## Amaç
API kimlik doğrulaması için `personal_access_tokens` tablosunu oluşturma migrasyonu.

## Genel Bakış
Bearer token'lar için destekleyici depoyu oluşturur. Token'lar sha256 hex özetleri olarak saklanır.

## Dosya Konumu
`database/migrations/2026_06_30_000001_create_personal_access_tokens_table.php`

## Uygulama
- `up()`: `id`, `tokenable` (morphs), `name`, `token` (benzersiz), `abilities` (text/JSON), `last_used_at`, `expires_at` ve zaman damgaları ile tabloyu oluşturur.
- `down()`: Tabloyu siler.

## Kaynak Referansları
- `database/migrations/2026_06_30_000001_create_personal_access_tokens_table.php:1-40`
