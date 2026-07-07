# Dosya Raporu: core/helpers.php

## Amaç
Yaygın framework görevleri için küresel yardımcı fonksiyonlar.

## Genel Bakış
Yönlendirme, yapılandırma, kimlik doğrulama ve diğer yardımcı görevler için, büyük framework'lerin ergonomisini yansıtan bir dizi kısa yol fonksiyonu sağlar.

## Dosya Konumu
`core/helpers.php`

## Fonksiyonlar
### Yönlendirme & HTTP
- `route(string $name, array $params = [])`: İsimlendirilmiş bir rota için URL oluşturur.
- `redirect(string $urlOrName)`: Kullanıcıyı bir URL'ye veya isimlendirilmiş rotaya yönlendirir.
- `back()`: Kullanıcıyı önceki sayfaya (HTTP_REFERER) yönlendirir.
- `asset(string $path)`: Statik bir varlık için URL oluşturur.
- `abort(int $code, string $message = '')`: Bir hata sayfasını tetiklemek için `HttpException` fırlatır.
- `response(string $body = '', int $status = 200)`: Bir `ResponseBuilder` örneği döndürür.

### Ortam & Yapılandırma
- `env(string $key, mixed $default = null)`: `.env` dosyasından tip dönüşümüyle birlikte bir ortam değişkeni alır.
- `config(string $key, mixed $default = null)`: `config/` dizinindeki dosyalardan nokta notasyonu kullanarak bir yapılandırma değeri alır.

### Oturum & Flash
- `flash(string $key, ?string $message = null)`: Geçici bir flash mesajı ayarlar veya alır.
- `old(string $key, string $default = '', bool $escape = true)`: Oturumdan eski girdiyi alır.

### Kimlik Doğrulama & Güvenlik
- `auth()`: `Core\Auth` aracılığıyla mevcut kimliği doğrulanmış kullanıcıyı döndürür.
- `csrf()` / `csrf_token()`: Bir CSRF token'ı oluşturur.

### Doğrulama & Veri
- `validate(array $data, array $rules, array $messages = [])`: Verileri doğrular ve hataları veya null döndürür.
- `collect(mixed $items = [])`: Bir `Illuminate\Support\Collection` oluşturur.
- `factory(string $modelClass, int $count = 1)`: Bir model fabrikası örneği döndürür.

### Diğer Araçlar
- `event(Event $event)`: Bir olayı dağıtır.
- `paginator(mixed $items, int $total = 0, int $perPage = 15)`: Bir `Core\Paginator` örneği döndürür.
- `method_field(string $method)`: HTTP metot taklidi için gizli bir girdi oluşturur.
- `cache(?string $key = null, mixed $value = null, ?int $ttl = null)`: `Core\Cache` sistemine erişir.
- `str_slug(string $text, string $separator = '-')`: URL uyumlu bir slug oluşturur.
- `str_limit(string $text, int $limit = 100, string $end = '...')`: Dize uzunluğunu sınırlar.
- `now(string $format = 'Y-m-d H:i:s')`: Mevcut tarih/saati döndürür.
- `today(string $format = 'Y-m-d')`: Mevcut tarihi döndürür.
- `isCloudflareIP(string $ip)`: Bir IP'nin Cloudflare'e ait olup olmadığını kontrol eder.
- `getRealIP()`: İstemcinin gerçek IP adresini güvenli bir şekilde alır.

## Bağımlılıklar
- Neredeyse tüm çekirdek bileşenleri kullanır (`Route`, `Redirect`, `Auth`, `Cache`, `Container`, `Request`, `ResponseBuilder`, `Validator`, `Paginator`, `Factory`, `Dispatcher`).

## Kaynak Referansları
- `core/helpers.php:1-647`
