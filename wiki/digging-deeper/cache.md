# Önbellekleme (Cache)

Uygulamanızın performansını artırmak için pahalı veritabanı sorgularını veya yavaş çalışan servis cevaplarını önbelleğe (Cache) almak çok önemlidir.

Umay Framework basit ve hızlı bir dosya tabanlı (File) Cache mekanizmasına sahiptir.

## Cache Facade Kullanımı

Önbellekleme işlemlerine en hızlı şekilde `Core\Facades\Cache` üzerinden ulaşabilirsiniz.

### Veri Kaydetmek (Set)
```php
use Core\Facades\Cache;

// 'users_list' anahtarıyla datayı 3600 saniye (1 saat) önbellekte tutar
Cache::set('users_list', $usersArray, 3600);
```

### Veri Okumak (Get)
```php
// Eğer önbellek yoksa veya süresi dolmuşsa null döner
$users = Cache::get('users_list');

// Varsayılan değer vermek isterseniz:
$users = Cache::get('users_list', []);
```

### Veri Kontrolü (Has)
```php
if (Cache::has('users_list')) {
    echo "Önbellekten geliyor!";
}
```

### Hatırla (Remember) 

En sık kullanacağınız metotlardan biri `remember` olacaktır. Bu metot; veri önbellekte varsa doğrudan getirir, yoksa sizin verdiğiniz Closure (anonim) fonksiyonu çalıştırıp veriyi çeker, önbelleğe kaydeder ve ardından sonucu döndürür.

```php
$users = Cache::remember('users_list', 3600, function () {
    return User::all();
});
```
Bu tek satırlık kod parçası, N+1 veya veritabanı yorgunluğu gibi problemleri anında çözer.

### Silmek ve Temizlemek (Forget / Flush)

```php
// Sadece tek bir anahtarı siler
Cache::forget('users_list');

// Tüm önbellek klasörünü (storage/cache) tertemiz yapar!
Cache::flush();
```

> [!TIP]
> Değişen bir veriniz olduğunda (Örneğin sisteme yeni bir kullanıcı eklendiğinde), ilgili modeli kaydettikten hemen sonra `Cache::forget('users_list')` diyerek eski listeyi geçersiz kılabilirsiniz. Böylece bir sonraki listeleme talebi anında veritabanına gidip güncel veriyi önbelleğe alacaktır.
