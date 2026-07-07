# Dosya Raporu: config/database.php

## Amaç
Veritabanı ve Eloquent yapılandırması.

## Genel Bakış
`phpdotenv` kullanarak `.env` değişkenlerini yükler, veritabanı bağlantı ayarlarını (varsayılan olarak MySQL) tanımlar ve `Core\Database::init()` aracılığıyla Eloquent ORM'yi başlatır.

## Dosya Konumu
`config/database.php`

## Yapılandırma
- `driver`: 'mysql'
- `host`: `DB_HOST`'tan gelir (varsayılan: '127.0.0.1')
- `port`: `DB_PORT`'tan gelir (varsayılan: '3306')
- `database`: `DB_DATABASE`'den gelir (varsayılan: 'umay')
- `username`: `DB_USERNAME`'den gelir (varsayılan: 'root')
- `password`: `DB_PASSWORD`'den gelir (varsayılan: '')
- `charset`: `DB_CHARSET`'den gelir (varsayılan: 'utf8mb4')
- `collation`: `DB_COLLATION`'dan gelir (varsayılan: 'utf8mb4_unicode_ci')
- `prefix`: ''
- `strict`: true

## Dahili İş Akışı
1. `.env` dosyasını güvenli bir şekilde yüklemek için `Dotenv`'i başlatır.
2. `$config` dizisini oluşturur.
3. Veritabanı bağlantısını kurmak için `Core\Database::init($config)` çağrılır.
4. `config()` yardımcısı üzerinden kullanım için `$config` dizisini döndürür.

## Bağımlılıklar
- `Core\Database` (Kullanır)
- `Dotenv\Dotenv` (Kullanır)

## Kaynak Referansları
- `config/database.php:1-42`
