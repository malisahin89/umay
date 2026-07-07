# Dosya Raporu: core/Csp.php

## Amaç
İçerik Güvenliği Politikası (CSP) nonce tutucusu.

## Genel Bakış
Mevcut istek için kriptografik olarak güvenli rastgele bir nonce yönetir. Bu nonce, CSP başlıklarında kullanılır ve XSS'yi önlemek için `<script>` ve `<style>` etiketlerine render edilir.

## Dosya Konumu
`core/Csp.php`

## Ad Alanı
`Core`

## Sınıflar
- `final class Csp`

## Özellikler
- `static ?string $nonce`: Mevcut istek için nonce.

## Metotlar
- `nonce(): string`: Mevcut nonce'u döndürür; mevcut değilse tembelce (lazily) oluşturur.
- `reset(): void`: Mevcut nonce'u temizler, bir sonraki erişimde yenisinin oluşturulmasını zorunlu kılar.

## Kaynak Referansları
- `core/Csp.php:1-45`
