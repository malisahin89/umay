# Dosya Raporu: core/Route.php

## Amaç
HTTP Rota Kayıt Defteri ve Dağıtıcı (Dispatcher).

## Genel Bakış
Çekirdek yönlendirme motorudur. Rotaların (GET, POST vb.) tanımlanmasına, önekler ve middleware ile gruplandırılmasına ve mevcut isteğin doğru işleyiciye yönlendirilmesine olanak tanır. İsimlendirilmiş rotaları, RESTful kaynakları ve regex eşleşmeli parametreli URI'ları destekler.

## Dosya Konumu
`core/Route.php`

## Ad Alanı
`Core`

## Sınıflar
- `class Route`

## Özellikler
- `static array $routes`: Tüm kayıtlı rotaların kayıt defteri.
- `static array $prefixStack`: Gruplandırma için aktif öneklerin yığını.
- `static array $middlewareStack`: Gruplandırma için aktif middleware yığını.
- `static array $namedRoutes`: Rota isimlerinin URI'lar ile eşlemesi.
- `static string $currentGroup`: Aktif middleware grubu ('web' veya 'api').

## Metotlar
- `get()`, `post()`, `put()`, `patch()`, `delete()`: Belirli HTTP metotları için rotaları kaydeder.
- `match(array $methods, string $uri, \Closure|string $action): static`: Birden fazla metot için tek bir rota kaydeder.
- `any(string $uri, \Closure|string $action): static`: Tüm metotlar için bir rota kaydeder.
- `view(string $uri, string $view, array $data = []): static`: Doğrudan bir görünüm işleyen bir rota kaydeder.
- `redirect(string $from, string $to, int $status = 302): static`: Bir yönlendirme rotası kaydeder.
- `resource()`, `apiResource()`: `ResourceRegistrar` aracılığıyla bir dizi RESTful kaynak rotası kaydeder.
- `prefix(string $prefix): static`: Sonraki rotalar için yığına bir önek ekler.
- `currentPrefix(): string`: Aktif birleşik grup önekini döndürür (ör. `/api/v1`); `ResourceRegistrar` kurulum anında bunu yakalar.
- `withPrefix(string $prefix, callable $callback): void`: Önek yığınını ekleyerek değil geçici olarak tam `$prefix` yapıp callback'i çalıştırır, sonunda geri yükler; ertelenmiş kayıtta çift öneki önler.
- `removeRoute(string $method, string $uri): void`: Belirtilen HTTP metodu/URI için kayıtlı rotayı kayıt defterinden kaldırır.
- `group(\Closure $callback): static`: Rota tanımlarını bir closure içine alır ve yürütme sonrası önek/middleware yığınlarını temizler.
- `name(string $routeName): static`: Mevcut rotaya bir isim atar.
- `middleware(string|array $middlewareName): static`: Mevcut rotaya veya gruba middleware atar.
- `url(string $name, array $params = []): string`: Yer tutucuları çözümleyerek isimlendirilmiş bir rota için URL oluşturur (`route()` helper'ı tarafından kullanılır; bilinmeyen isimde hata fırlatır).
- `dispatch(): void`: Ana dağıtıcı. İstek URI/metodunu bir rota ile eşleştirir, middleware'leri çözer ve işleyiciyi yürütür.

## Dahili İş Akışı
- **Rota Derlemesi**: Parametreli rotalar (örneğin, `/users/{id}`), dağıtımı optimize etmek için kayıt sırasında regex desenlerine derlenir.
- **Metot Taklidi (Method Spoofing)**: HTML formlarında PUT/PATCH/DELETE'e izin vermek için `_method` POST parametresini (veya `X-HTTP-Method-Override` başlığını) destekler; eşleşen fiili controller'lara yansıtmak için `Request::setMethod()` çağrılır, böylece `method()`/`isMethod()` spoof edilen fiili görür.
- **Middleware Hattı**: Middleware'leri ve nihai işleyiciyi yürütmek için iç içe geçmiş bir closure zinciri ("soğan" modeli) oluşturmak üzere `array_reduce` kullanır.
- **Parametre Dönüştürme**: `castRouteParam()`, URL dize segmentlerini kontrolcünün beyan ettiği skaler türlere (int, float, bool) zorlar. Yakalanan segmentler önce `rawurldecode` ile çözümlenir, böylece `%2F` gibi kaçışlı değerler işleyiciye doğru ulaşır.
- **Sondaki Eğik Çizgi Yönlendirmesi**: Kanonik URL'leri sağlamak için GET isteklerinde `/path/` adresini `/path` adresine yönlendirir.

## Bağımlılıklar
- `Core\ResourceRegistrar` (Kullanır)
- `Core\Request` (Kullanır)
- `Core\Container` (Kullanır)
- `Core\Profiler\ProfilerController` (`/_profiler` rotaları için kullanır)

## Kaynak Referansları
- `core/Route.php:1-1223`
