# Önbellek

## Amaç
Dosya tabanlı önbelleği belgeler: depolama formatı, bütünlük, eşzamanlılık ve API.

## Genel Bakış
`Core\Cache`, konteynerden çözülen (`Cache` facade'ı aracılığıyla), örnek tabanlı ve dosya destekli bir önbellektir. Kayıtlar `config('cache.path')` (varsayılan `storage/cache/`) altında, `sha256(prefix + key)` anahtarıyla saklanır ve HMAC imzası ile TTL ile korunur.

## Depolama Formatı ve Bütünlük
- Yük (Payload): `hash_hmac('sha256', json, appKey) . ':' . json`, burada `json = {"value":…, "expires":ts}`.
- Uygulama anahtarı `APP_KEY` veya türetilmiş bir yedek (`getAppKey()`)'dir.
- Okuma sırasında, `decode()` HMAC'ı (`hash_equals`) ve son kullanma tarihini doğrular; eksik/kurcalanmış/hatalı/süresi dolmuş yükler varsayılan değeri döndürür ve bozuk/süresi dolmuş dosyalar silinir. Meşru olarak saklanan bir `null` değerinin doğru şekilde dönebilmesi için `array_key_exists` kullanılır.

## Eşzamanlılık
- `set()`, geçici bir dosyaya yazar ve ardından atomik olarak `rename()` yapar, böylece eşzamanlı kilitlenmemiş okuyucular asla yarıda kalmış bir dosya görmez.
- `atomic($key, $mutator, $ttl)`, 256 adet kova kilit dosyasından (`umay-lock-N.lock`) oluşan sabit bir havuz üzerinde süreçler arası `flock(LOCK_EX)` ile yarışmasız bir oku-değiştir-yaz işlemi gerçekleştirir ve kilit dosyalarının sınırsız büyümesini önler. Kilit alınamazsa **kapalı olarak hata verir** (exception fırlatır) — hız sınırlayıcıyı TOCTOU-güvenli yapan budur.

## Genel API
- `get(key, default)`, `set(key, value, ttl?)`, `has(key)`, `forget(key)`, `flush()`, `pull(key, default)`, `remember(key, ttl, callback)`, `atomic(key, mutator, ttl?)`.
- `"\0__umay_cache_miss__\0"` nöbetçisi, saklanan bir `null` değerini önbellek ıskalamasından ayırır (`remember`/`has` tarafından kullanılır).
- Profilleme sırasında, önbellek işlemlerini `DebugBar::addCacheOp` aracılığıyla kaydeder.

## Konfigürasyon (`config/cache.php`)
- `path` (varsayılan `storage/cache`), `prefix` (varsayılan `umay_`), `default_ttl` (varsayılan 3600s).

## Çapraz Referanslar
- **Tüketiciler:** `Core\RateLimiter` (`DOCS/SECURITY.md` bakın), profiler.
- **Facade:** `Core\Facades\Cache` (`DOCS/core/Facades/Cache.md` bakın)
- **Testler:** `DOCS/tests/Unit/CacheTest.md`

## Güvenlik Gözlemleri
- HMAC bütünlüğü önbelleğin kurcalanmasını önler; atomik yazmalar + kapalı hata veren kilitleme yarışları önler.

## Kaynak Referansları
- `core/Cache.php:23-319`
- `config/cache.php:12-30`
