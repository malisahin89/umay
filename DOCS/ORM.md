# ORM

## Amaç
Eloquent üzerine inşa edilmiş nesne-ilişkisel eşleme (ORM) katmanını belgeler.

## Genel Bakış
`Core\Model`, `Illuminate\Database\Eloquent\Model` sınıfını genişleten soyut bir temel sınıftır. Uygulama modelleri (`App\Models\*`) bu sınıfı genişletir ve `Core\Database` tarafından yapılandırılan bağlantı aracılığıyla tüm Eloquent özellik setini kazanır.

## Temel Model (`Core\Model`)
- `abstract class Model extends EloquentModel`
- `public $timestamps = true` olarak ayarlanmıştır.
- Desteklenen özellikler (Eloquent'ten gelen): eager loading (`with`), sorgu kapsamları (query scopes), accessors/mutators, nitelik dönüştürme (`$casts`), yumuşak silme (`SoftDeletes` trait'i), polimorfik ve çoktan-çoğa ilişkiler, `hasManyThrough`, model olayları ve Collection metotları.

## Destekleyici Bileşenler
| Bileşen | Rol | Kaynak |
|-----------|------|--------|
| `Core\Concerns\SoftDeletes` | Yumuşak silme trait'i (`deleted_at`) | `core/Concerns/SoftDeletes.php` |
| `Core\Migration` | Şema migrasyonları için temel (`up`/`down`, `execute`, `tableExists`) | `core/Migration.php` |
| `Core\Migrator` | Migrasyonları bir `migrations` tablosu üzerinden yürütür ve takip eder | `core/Migrator.php` |
| `Core\Seeder` | Veritabanı seed'leri için temel (`run`) | `core/Seeder.php` |
| `Core\Factory` | Sahte veri fabrikaları (`definition`, Faker proxy) | `core/Factory.php` |
| `Core\Paginator` | Sonuç kümeleri üzerinde sayfalama (pagination) | `core/Paginator.php` |

Uygulama örneği: `App\Models\User` (bkz. `DOCS/app/Models/User.md`).

## Çapraz Referanslar
- **Bağlantı:** `Core\Database` (bkz. `DOCS/DATABASE.md`)
- **Migrasyonlar/Seeders/Factories:** `DOCS/database/index.md`
- **Temel model raporu:** `DOCS/core/Model.md`

## Kaynak Referansları
- `core/Model.php:27-30`
- `core/Concerns/SoftDeletes.php`, `core/Migration.php`, `core/Migrator.php`, `core/Seeder.php`, `core/Factory.php`, `core/Paginator.php`
