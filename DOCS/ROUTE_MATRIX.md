# Rota Matrisi (Route Matrix)

## Amaç
Doğrulanmış ve aktif olarak kaydedilmiş rotaları listeler.

## Genel Bakış
Rotalar `routes/web.php` (oturum tabanlı, `web` grubu) ve `routes/api.php` (durumsuz, `api` grubu, varsayılan `api_prefix` `/api`) dosyalarında tanımlanır. Analiz edilen kaynakta yalnızca bir rota aktif olarak kaydedilmiştir; her iki dosyadaki diğer tüm rota tanımları yorum satırına alınmış örneklerdir.

## Web Rotaları (`routes/web.php`)
| Metot | URI | İsim | Kontrolcü / Eylem | Middleware | Kaynak |
|--------|-----|------|---------------------|-----------|--------|
| GET | `/` | `home` | `Route::view` $\to$ `welcome` şablonunu işler (`title` ayarlanmış) | `web` grubu (`RememberMe`, `SecurityHeaders`, `VerifyCsrfToken`) | `routes/web.php:8` |

## API Rotaları (`routes/api.php`)
Aktif rota bulunmamaktadır. Dosya, kullanım desenlerini (`api-auth`, `api-auth:ability`, `Route::apiResource`) yalnızca yorumlanmış örnekler olarak belgeler.

> Analiz edilen kaynak kodda başka doğrulanmış rota bulunamadı. Yorumlarda gösterilen ek rotalar örneklerdir, kaydedilmiş rotalar değildir.

## Çapraz Referanslar
- `DOCS/ROUTING_SYSTEM.md`, `DOCS/MIDDLEWARE.md`, `DOCS/routes/index.md`

## Kaynak Referansları
- `routes/web.php:8`
- `routes/api.php:1-78` (yalnızca örnekler)
- `config/middleware.php:53` (api_prefix), `config/middleware.php:84-105` (gruplar)
