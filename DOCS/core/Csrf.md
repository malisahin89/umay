# Dosya Raporu: core/Csrf.php

## Amaç
Siteler Arası İstek Sahteciliği (CSRF) koruması.

## Genel Bakış
Durum değiştiren isteklerin (POST, PUT vb.) kimliği doğrulanmış kullanıcıdan geldiğinden emin olmak için oturumda saklanan gizli bir CSRF token'ı oluşturur ve doğrular.

## Dosya Konumu
`core/Csrf.php`

## Ad Alanı
`Core`

## Sınıflar
- `class Csrf`

## Metotlar
- `generate(): string`: Rastgele bir 32-baytlık token oluşturur, bunu `$_SESSION['csrf_token']` içinde saklar ve döndürür.
- `check(mixed $token): bool`: Zamanlama saldırılarını önlemek için `hash_equals` kullanarak sağlanan token'ı oturumdakine kıyaslar.

## Kaynak Referansları
- `core/Csrf.php:1-41`
