# Konteyner

Bu rapor, Umay framework'ündeki Bağımlılık Enjeksiyonu (DI) Konteyner uygulamasını açıklar.

## Genel Bakış
`Core\Container`, sınıf bağımlılıklarını ve nesne yaşam döngülerini yöneten PSR-11 uyumlu bir servis konteyneridir. Reflection (otomatik kablolama - auto-wiring) yoluyla bağımlılıkları otomatik olarak çözerek gevşek bağlı (decoupled) bir mimari sağlar.

## Temel İşlevsellik

### 1. Bağlama (Binding)
Konteyner, bir "soyutlama"yı (genellikle bir arayüz veya sınıf adı) "somut" bir uygulamaya eşlemenize olanak tanır.

- **Basit Bağlama (`bind`)**: Soyutlama her çözümlendiğinde yeni bir örnek oluşturulur.
- **Singleton Bağlama (`singleton`)**: Çözümlenen ilk örnek önbelleğe alınır ve sonraki tüm istekler için döndürülür.
- **Örnek Bağlama (`instance`)**: Mevcut bir nesne örneği soyutlamaya bağlanır.

### 2. Çözümleme (`make` / `get`)
- **`make($abstract)`**: Bir girdiyi çözümlemek için kullanılan temel metottur. Önce tanımlanmış bağlamaları çözmeye çalışır, ardından otomatik kablolamaya (auto-wiring) geçer.
- **`get($id)`**: PSR-11 uyumlu bir metottur. `make()`'den farklı olarak, girdi kayıtlı değilse `EntryNotFoundException` fırlatır ve otomatik kablolama geri dönüşünü devre dışı bırakır.

### 3. Otomatik Kablolama (`build`)
Herhangi bir bağlama mevcut olmadığında, konteyner bağımlılıkları otomatik olarak çözmek için PHP'nin `ReflectionClass` sınıfını kullanır:
1. **Kurucu İncelemesi**: Sınıf kurucusunu tip belirtilmiş parametreler için inceler.
2. **Özyinelemeli Çözümleme**: Her parametre `make()` aracılığıyla özyinelemeli olarak çözümlenir.
3. **Union Type Desteği**: PHP 8.0+ union tipleri için konteyner, kayıtlı olan veya sınıf olarak mevcut olan ilk tipi çözümlemeye çalışır.
4. **Varsayılan Değerler**: Bir parametre çözümlenemezse ancak varsayılan bir değere sahipse, bu değer kullanılır.
5. **Skaler Dönüşüm**: Yerleşik tipler (int, string, vb.) otomatik olarak kablolanmaz; varsayılan değerleri olmalı veya sağlanmalıdır.

## Gelişmiş Özellikler

### Döngüsel Bağımlılık Koruması
Konteyner, şu anda çözümlenmekte olan sınıfları `resolving` dizisinde takip eder. Eğer bir sınıf, kendi oluşturulması sırasında kendisini (doğrudan veya dolaylı olarak) çözümlemeye çalışırsa, sonsuz döngüleri önlemek için bir `ContainerException` fırlatılır.

### Performans Optimizasyonu
Reflection maliyetini en aza indirmek için konteyner bir `reflectionCache` tutar. Somut bir sınıf için bir `ReflectionClass` oluşturulduğunda, bu saklanır ve yeniden kullanılır.

## Metot Özeti

| Metot | Açıklama |
| :--- | :--- |
| `getInstance()` | Konteyner'in singleton örneğini döndürür. |
| `bind($abs, $conc)` | Bir sınıf için fabrika kaydeder. |
| `singleton($abs, $conc)` | Bir sınıf için singleton kaydeder. |
| `instance($abs, $inst)` | Mevcut bir örneği bağlar. |
| `make($abs)` | Örneği çözer (otomatik kablolama ile). |
| `get($id)` | Örneği çözer (PSR-11, otomatik kablolama yok). |
| `has($abs)` | Bir bağlama veya örneğin mevcut olup olmadığını kontrol eder. |
