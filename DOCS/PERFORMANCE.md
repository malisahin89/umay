# Performans ve Profilleme (Performance & Profiling)

## Amaç
Profilleme/tanılama alt sistemini ve performansla ilgili mekanizmaları belgeler.

## Genel Bakış
Umay, sorguları, önbellek işlemlerini, günlükleri, görünümleri ve istisnaları toplayan ve bir hata ayıklama araç çubuğu (debug toolbar) sunan bir geliştirme profiler'ı ile birlikte gelir. Profilleme, yapılandırma ve çalışma zamanındaki `UMAY_PROFILING` bayrağı ile kontrol edilir, böylece production ortamında herhangi bir ek yük oluşturmaz.

## Bileşenler
| Bileşen | Rol | Kaynak |
|-----------|------|--------|
| `Core\Profiler\Profiler` | Merkezi toplayıcı; `startMeasure`/`stopMeasure`, `addView`, `finish`, `renderToolbar`, `isEnabled` | `core/Profiler/Profiler.php` |
| `Core\DebugBar` | Sorgular/önbellek/günlükler/istisnalar için koleksiyon facade'ı | `core/DebugBar.php` |
| `Core\Profiler\ProfilerStorage` | Profiler verilerini saklar (`storage/profiler/`) | `core/Profiler/ProfilerStorage.php` |
| `Core\Profiler\ProfilerController` | Saklanan profiler verilerini sunar (AJAX uç noktası) | `core/Profiler/ProfilerController.php` |
| `Core\Profiler\Contracts\DataCollectorInterface` | Toplayıcı sözleşmesi | `core/Profiler/Contracts/DataCollectorInterface.php` |
| `core/Profiler/Views/toolbar.php` | Araç çubuğu şablonu | `core/Profiler/Views/toolbar.php` |

## Enstrümantasyon Kancaları (Doğrulanmış)
- **Veritabanı:** hata ayıklama modunda, `Core\Database::init` `UMAY_PROFILING` ayarlı olduğunda `DebugBar::addQuery` (SQL, bağlamalar, süre, çağırıcı/model ile birlikte) fonksiyonunu çağıran bir sorgu dinleyicisi kaydeder.
- **Önbellek:** `Core\Cache`, `UMAY_PROFILING` altında `DebugBar::addCacheOp('get'|'set', key, hit?)` kaydeder.
- **Görünüm:** `Core\View::render` işleme sürecini `Profiler::startMeasure/stopMeasure` ile sarmalar, `Profiler::addView`, `Profiler::finish` fonksiyonlarını çağırır ve `</body>`'den önce `Profiler::renderToolbar()`'ı enjekte eder.
- **Günlükleyici:** Etkin olduğunda girdileri `DebugBar::addLog`'a iletir.

## Performans Odaklı Tasarım
- Atomik yazmalara ve pahalı işlemleri memoize etmek için `remember()`'a sahip dosya önbelleği (bkz. `DOCS/CACHE.md`).
- `Core\Auth`, çözümlenen kullanıcıyı her istek için bir kez önbelleğe alır; `Core\View` tek bir Plates motoru örneğini yeniden kullanır.

## Yapılandırma
- `config/profiler.php` (ve `PROFILER_ENABLED`) profillemeyi açıp kapatır.

## Çapraz Referanslar
- `DOCS/core/Profiler/index.md`, `DOCS/core/DebugBar.md`, `DOCS/config/profiler.md`, `DOCS/CACHE.md`

## Kaynak Referansları
- `core/Database.php:76-90`, `core/Cache.php:122-154`, `core/View.php:276-296`, `core/Logger.php:69-71`
- `core/Profiler/Profiler.php`, `core/DebugBar.php`
