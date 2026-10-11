# Yönlendirme Sistemi

Bu rapor, Umay framework'ündeki yönlendirme motorunun uygulamasını ve işlevselliğini açıklar.

## 1. Rota Tanımlama
Rotalar, `Core\Route` tarafından sağlanan akıcı (fluent) bir statik API kullanılarak tanımlanır.

### Desteklenen Metotlar
Yönlendirici, standart HTTP fiilleri için yardımcı metotlar sağlar:
- `Route::get($uri, $action)`
- `Route::post($uri, $action)`
- `Route::put($uri, $action)`
- `Route::patch($uri, $action)`
- `Route::delete($uri, $action)`
- `Route::match($methods, $uri, $action)`: Birden fazla metot için bir rota tanımlar.
- `Route::any($uri, $action)`: Tüm metotlar için bir rota tanımlar.

### Özelleştirilmiş Rotalar
- **Görünüm Rotaları**: `Route::view($uri, $view, $data)` doğrudan bir görünüm işler.
- **Yönlendirme Rotaları**: `Route::redirect($from, $to, $status)` doğrudan bir HTTP yönlendirmesi gerçekleştirir.
- **Kaynak Rotaları**: `Route::resource()` ve `Route::apiResource()`, `Core\ResourceRegistrar` kullanarak bir kontrolcü için bir dizi RESTful rota oluşturur.

## 2. Rota Gruplama ve Organizasyon
Yönlendirici, ortak nitelikleri paylaşmak için rotaların mantıksal olarak gruplandırılmasını destekler.

### Önekler (Prefixes)
`Route::prefix($prefix)`, takip eden grup içinde tanımlanan tüm rotalara bir URI segmenti ekler.

### Middleware
`Route::middleware($name)`, rotaya veya gruba bir veya daha fazla middleware ekler.

### Gruplar
`Route::group($callback)`, rotaları sarmalar ve callback yürütüldükten sonra önek ve middleware yığınlarını geri yükler.

### İsimlendirilmiş Rotalar
Rotalar `->name($name)` aracılığıyla isimlendirilebilir, bu da `Route::url($name, $params)` kullanılarak ters URL üretimine olanak tanır.

## 3. Dağıtım Süreci (Dispatching)
`Route::dispatch()` çağrıldığında aşağıdaki adımlar gerçekleşir:

### URI Normalizasyonu ve Yönlendirme
- İstek URI'sinin sonundaki eğik çizgiler temizlenir.
- Eğer bir GET/HEAD isteğinin sonunda eğik çizgi varsa ve eşleşen eğik çizgisiz bir rota mevcutsa, kanonik URI'ye 301 yönlendirmesi yapılır.

### Rota Eşleştirme
1. **Tam Eşleşme**: Yönlendirici önce URI'nin rota tablosunda sabit bir anahtar olarak mevcut olup olmadığını kontrol eder.
2. **Desen Eşleşme**: Tam eşleşme bulunamazsa, yönlendirici yer tutucular (örneğin, `{id}`) içeren rotalar üzerinden geçer. Bunlar önceden derlenmiş düzenli ifadeler (regex) kullanılarak eşleştirilir.

### Eylem Çözümleme
Yönlendirici, eylem türüne göre isteğin nasıl işleneceğine karar verir:
- **Closures**: Doğrudan yürütülür.
- **Kontrolcüler**: `config('app.controller_namespace')` aracılığıyla çözümlenir ve `Core\Container` üzerinden örneklendirilir.
- **Sözde-eylemler (Pseudo-actions)**: `_view` ve `_redirect` dahili mantıkla işlenir.

## 4. Middleware Hattı (Pipeline)
Umay, `array_reduce` kullanarak bir "Pipeline" deseni uygular.
1. **Toplama**: Yönlendirici; global, grup ve rotaya özel middleware'leri toplar.
2. **Sarma**: Middleware'ler closure'lar ile sarmalanarak iç içe geçmiş bir zincir (soğan mimarisi) oluşturulur.
3. **Yürütme**: İstek her bir middleware'den geçer. Her middleware, bir sonraki katmana veya nihai eyleme geçmek için `$next` closure'ını çağırmalıdır.

## 5. Parametre Enjeksiyonu
Yönlendirici, eylem metodunun parametrelerini analiz etmek için PHP Reflection kullanır:
- **Request Enjeksiyonu**: `Core\Request` otomatik olarak enjekte edilir.
- **Form Request**: `Request` alt sınıfları örneklendirilir ve doğrulanır.
- **Rota Parametreleri**: URI'deki yer tutucular, beyan edilen skaler türe (`int`, `float`, `bool`) dönüştürülür ve enjekte edilir.
- **Konteyner Çözümleme**: Tip belirtilmiş diğer tüm sınıflar `Core\Container` aracılığıyla çözümlenir.
