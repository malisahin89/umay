# Günlükleme (Logging)

## Amaç
Uygulama günlükleyicisini belgeler.

## Genel Bakış
`Core\Logger`, günlüklerin günlük bazlı döndürülmesine (daily rotation) izin veren, örnek tabanlı ve konteynerden çözümlenen (`Log` facade'ı aracılığıyla) dosya tabanlı bir günlükleyicidir. Günlükler `storage/logs/Y-m-d.log` yoluna yazılır.

## API
- `info(string $message, array $context = [])`
- `warning(string $message, array $context = [])`
- `error(string $message, array $context = [])`

## Günlük Formatı
Her satır: `[timestamp] LEVEL: message | IP: … | Context: <json> | User-Agent: …` şeklindedir.
- Zaman damgası `Y-m-d H:i:s`; IP adresi `REMOTE_ADDR`'den; kullanıcı aracısı `HTTP_USER_AGENT`'den alınır.
- `FILE_APPEND | LOCK_EX` kullanılarak yazılır.
- Günlük dizini, `0700` modu ile talep üzerine oluşturulur.

## Güvenlik Gözlemleri
- **Günlük Enjeksiyonu Savunması:** Yazmadan önce `message`, `IP` ve `User-Agent` içerisindeki CR/LF karakterleri temizlenir.

## Profiler Entegrasyonu
- `DebugBar` etkin olduğunda, her girdi aynı zamanda `DebugBar::addLog(level, message, context)` aracılığıyla iletilir.

## Tüketiciler
- `Core\ExceptionHandler` CSRF/HTTP uyarılarını ve yakalanamayan hataları günlüğe kaydeder; diğer bileşenler `Log` facade'ı üzerinden günlük tutar.

## Çapraz Referanslar
- `DOCS/core/Logger.md`, `DOCS/core/Facades/Log.md`, `DOCS/ERROR_HANDLING.md`, `DOCS/PERFORMANCE.md`
- Testler: `DOCS/tests/Unit/LoggerTest.md`

## Kaynak Referansları
- `core/Logger.php:19-73`
- `core/Logger.php:49-54` (enjeksiyon savunması), `core/Logger.php:66-67` (günlük dosya + ekleme)
