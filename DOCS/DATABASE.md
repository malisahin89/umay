# Veritabanı Katmanı

## Amaç
Veritabanı erişim katmanını: bağlantı yönetimi, ham sorgular ve işlemleri (transactions) belgeler.

## Genel Bakış
`Core\Database`, Illuminate (Eloquent) **Capsule Manager** üzerine kurulu statik bir sarmalayıcıdır. Tek bir bağlantıyı yapılandırır, Eloquent'i küresel olarak başlatır ve ham sorgu ile işlem yardımcılarını sunar. Model tabanlı tüm erişimler bu aynı bağlantı üzerinden akar.

## Başlatma (Initialization)
`Database::init(array $config): Capsule` (idempotent — statik bir örnekle korunur):
- **sqlite** sürücüsü (testler / `:memory:` için kullanılır) veya **mysql** sürücüsü (varsayılan).
- MySQL seçenekleri `ERRMODE_EXCEPTION`, `FETCH_OBJ` olarak ayarlanır, emüle edilmiş hazırlanan ifadeler (emulated prepares) devre dışı bırakılır ve `SET NAMES … COLLATE …` sabitlenir.
- Model olaylarının (creating/saved/deleted) ve observer'ların çalışması için gerçek bir Illuminate olay `Dispatcher`'ı kaydeder.
- `setAsGlobal()` ve `bootEloquent()` fonksiyonlarını çağırır.
- Hata ayıklama modunda, `UMAY_PROFILING` tanımlı olduğunda profiler'a veri sağlayan bir sorgu dinleyicisi kaydeder (`DebugBar::addQuery`).

Yapılandırma `config/database.php`'den gelir; test bootstrap'ı bunun yerine bellek içi (in-memory) bir SQLite DB başlatabilir.

## Genel API (statik)
- `getConnection(string $name = 'default'): Connection` — başlatılmamışsa `RuntimeException` fırlatır.
- Ham sorgular: `statement()`, `select()`, `selectOne()`, `insert()`, `update()` (etkilenen satırlar), `delete()` (etkilenen satırlar).
- İşlemler: `transaction(callable)`, `beginTransaction()`, `commit()`, `rollBack()`.
- Yaşam Döngüsü: `closeConnection()`, `closeAllConnections()`, `getActiveConnectionCount()`.

Kullanım aynı zamanda `DB` facade (`Core\Facades\DB`) aracılığıyla da sunulur, örneğin: `DB::table('users')->where('id',1)->first()`.

## Çapraz Referanslar
- **ORM:** `Core\Model` bu bağlantı üzerinden Eloquent'i genişletir (bkz. `DOCS/ORM.md`)
- **Facade:** `Core\Facades\DB` (bkz. `DOCS/core/Facades/DB.md`)
- **Migrasyonlar/Seeders/Factories:** bkz. `DOCS/database/index.md`
- **Şurada başlatılır:** `config/database.php`, `tests/bootstrap.php`

## Güvenlik Gözlemleri
- `select/insert/update/delete` genelinde bağlamalı (bindings) hazırlanan ifadeler kullanılır; emüle edilmiş hazırlanan ifadeler devre dışı bırakılmıştır.

## Kaynak Referansları
- `core/Database.php:26-225`
- `composer.json:28-30` (illuminate/database, illuminate/events)
