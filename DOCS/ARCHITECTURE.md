# Umay Framework Mimari Genel Bakış

## 1. Sistem Tasarımı
Umay, hem geleneksel Web hem de durumsuz (stateless) API uygulamaları için tasarlanmış minimal, yüksek performanslı bir PHP MVC framework'üdür. Tüm isteklerin `public/index.php` üzerinden yönlendirildiği bir **Front Controller** deseni kullanır.

## 2. Çekirdek Bileşenler

### Bağımlılık Enjeksiyonu ve Konteyner
`Core\Container`, framework'ün omurgasıdır. Servis yaşam döngülerini (singleton'lar vs fabrikalar) yönetir ve otomatik bağımlılık çözümlemesine olanak tanır.

### Yönlendirme Sistemi
`Core\Route` sistemi şunları destekler:
- **Web Rotaları**: Durumlu (stateful), oturum destekli, CSRF korumalı.
- **API Rotaları**: Durumsuz (stateless), ön ekli (varsayılan `/api`), Bearer token kimlik doğrulaması için optimize edilmiş.
- **Özellikler**: İsimlendirilmiş rotalar, Rota Grupları, Kaynak Kontrolcüleri (`resource` ve `apiResource`).

### Middleware Hattı
İstekler, kontrolcüye ulaşmadan önce bir middleware hattından geçer.
- **Global Middleware**: Tüm isteklere uygulanır.
- **Grup Middleware**: Belirli rota gruplarına uygulanır (örneğin, `api` grubu).
- **Rotaya Özel Middleware**: `->middleware()` aracılığıyla uygulanır.

### Kimlik Doğrulama ve Yetkilendirme
- **Guard'lar**: Birden fazla kimlik doğrulama guard'ını destekler.
- **API Kimlik Doğrulaması**: `personal_access_tokens` tablosunda saklanan Bearer token'ları ve yetenek tabanlı (izin) kontrollerini uygular.
- **Kullanıcı Sağlayıcıları**: `UserProvider` arayüzü ile genişletilebilir; varsayılan uygulama Eloquent kullanır.

### Veritabanı ve ORM
`illuminate/database` (Eloquent) ile entegre edilmiştir.
- **Migrasyonlar**: Versiyon kontrollü şema değişiklikleri.
- **Seeders ve Factories**: Otomatik test verisi oluşturma.
- **Model**: Temel `Core\Model`, active-record yetenekleri sağlar.

### Görünüm Motoru
Şablonlama için `league/plates` kullanır.
- **Layout'lar**: Bölümleri olan ana şablonlar.
- **Partials**: Yeniden kullanılabilir kullanıcı arayüzü bileşenleri.
- **Güvenlik**: `$this->nonce()` aracılığıyla CSP nonce'ları için yerel destek.

### Hata Ayıklama ve Profilleme
Yerleşik bir `Profiler` sistemi istek metriklerini yakalar:
- **Depolama**: Anlık görüntüler `storage/profiler/` dizinine JSON olarak kaydedilir.
- **Araç Çubuğu**: Hızlı tanılama için web sayfalarında kompakt bir katman.
- **Kontrol**: `PROFILER_ENABLED` aracılığıyla bağımsız konfigürasyon.

## 3. İstek Yaşam Döngüsü
1. **Giriş**: İstek `public/.htaccess` $\rightarrow$ `public/index.php` yoluna ulaşır.
2. **Önyükleme (Bootstrapping)**:
    - `Profiler::init()`
    - `Application` singleton örneği oluşturulur.
    - `FacadeServiceProvider`, `EventServiceProvider`, `RouteServiceProvider` kaydı yapılır.
    - `Application::boot()` (sağlayıcıları çözer ve rotaları yükler).
3. **Dağıtım (Dispatch)**: `Route::dispatch()` URI'yi bir işleyiciyle eşleştirir.
4. **Hat (Pipeline)**: Middleware'ler sırayla çalıştırılır.
5. **Yürütme**: Kontrolcü metodu çağrılır $\rightarrow$ bir `Response` döndürür.
6. **Sonlandırma**: `Profiler::finish()` tanılama verilerini kaydeder.

## 4. Dizin Yapısı Özeti
- `app/`: Uygulamaya özel mantık (Kontrolcüler, Modeller, Sağlayıcılar).
- `config/`: Tüm çekirdek servisler için konfigürasyon dosyaları.
- `core/`: Framework çekirdeği (Konteyner, Rota, Kimlik Doğrulama, Görünüm vb.).
- `database/`: Migrasyonlar, Seeders ve Factories.
- `public/`: Web kök dizini (index.php, varlıklar).
- `routes/`: Rota tanımlama dosyaları (`web.php`, `api.php`).
- `storage/`: Günlükler, önbellek ve profiller için yazılabilir alan.
- `views/`: Şablon dosyaları.
