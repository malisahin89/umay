# Doğrulama (Validation)

## Amaç
Girdi doğrulamasını belgeler.

## Genel Bakış
`Core\Validator`, `validate()` yardımcısı ve `Core\FormRequest` tarafından kullanılan bağımsız bir doğrulayıcıdır. `Validator::make($data, $rules, $messages)` aracılığıyla oluşturulur, hemen çalışır ve `errors()`, `fails()`, `passes()` metodlarını sunar.

## Kural Sözdizimi
Kurallar her alan için `|` ile ayrılmış bir dize veya bir dizi olarak verilir. `sometimes` kuralı, mevcut olmayan bir alanı atlar; çoğu kural boş değerlerde hiçbir işlem yapmaz (bu nedenle zorunlu olmayan alanlar boş olduğunda geçer geçer). Dizi değerleri, erken aşamada bir `type` hatası ile reddedilir.

## Desteklenen Kurallar
`required`, `sometimes`, `min:N`, `max:N`, `email`, `numeric`, `integer`, `confirmed`, `same:field`, `in:a,b,c`, `not_in:a,b`, `alpha`, `alpha_num` (`alphanumeric`), `url`, `regex:/pattern/`, `digits:N`, `digits_between:min,max`, `date`, `before:date`, `after:date`, `unique:table,col[,ignoreId]`, `exists:table,col`.

- `alpha`/`alpha_num` Türkçe harfleri kabul eder.
- `min`/`max`/`confirmed`/`same` kuralları **ham** (temizlenmemiş) değeri karşılaştırır, böylece `" gizli "` gibi değerler bayt bayt ölçülür/karşılaştırılır; diğer kurallar temizlenmiş (trimmed) değeri kullanır.
- `unique`/`exists` kuralları veritabanını (`DB::table`) sorgular ve kullanmadan önce tablo/sütun adlarını bir regex izin listesi ile temizler.

## Hata Mesajları
Özel mesajlar şu öncelik sırasına göre çözümlenir: `"alan.kural"` $\to$ `"alan"` $\to$ yerleşik varsayılan. Mesajlar varsayılan olarak iki dillidir (İngilizce // Türkçe).

## Entegrasyon
- `validate($data, $rules, $messages): ?array` (yardımcı) geçerli olduğunda `null`, aksi takdirde hata dizisini döndürür.
- `Core\FormRequest`, istek nesnesi doğrulaması için `authorize()`/`rules()`/`messages()` ile doğrulayıcıyı kullanır.

## Çapraz Referanslar
- `DOCS/core/Validator.md`, `DOCS/core/FormRequest.md`, `DOCS/core/helpers.md`
- `DOCS/core/Facades/Validator.md`, testler: `DOCS/tests/Unit/ValidatorTest.md`, `DOCS/tests/Unit/ValidatorExtendedTest.md`

## Kaynak Referansları
- `core/Validator.php:19-335`
- `core/Validator.php:117-141` (kural dağıtımı), `core/Validator.php:289-334` (unique/exists temizleme)
