# Profiler (Debug Toolbar)

Umay Framework, geliştirme sürecini hızlandırmak için yerleşik bir uygulama profilleme sistemine sahiptir.

## Profiler Nedir?

Profiler, her HTTP isteği sırasında gerçekleşen olayları (SQL sorguları, Route eşleşmeleri, Middleware çalışma süreleri, bellek kullanımı) takip eder ve bunları bir rapor olarak sunar.

## Etkinleştirme

Profiler'ı aktif etmek için `.env` dosyanızda şu ayarı yapın:

```env
PROFILER_ENABLED=true
```

Veya `config/profiler.php` üzerinden yönetin.

## Kullanım ve İzleme

Profiler etkin olduğunda, web sayfalarının altında kompakt bir araç çubuğu (toolbar) görünür.

1. **Toolbar**: Sayfanın altında anlık metrikleri gösterir.
2. **Detaylı Rapor**: Toolbar üzerindeki linke tıkladığınızda, o isteğe özel oluşturulan detaylı JSON raporunun HTML versiyonu açılır (`/_profiler/{token}`).

## Teknik Detaylar

- **Veri Saklama**: Her istek için benzersiz bir token oluşturulur ve veriler `storage/profiler/` dizinine JSON olarak kaydedilir.
- **Otomatik Temizlik**: Profiler, belirli bir sayıdan fazla kayıt oluştuğunda veya eski kayıtlar için otomatik temizlik yapar.
- **Performans**: `UMAY_PROFILING` sabiti üzerinden kontrol edilerek, kapalı olduğunda sistem üzerinde neredeyse sıfır yük oluşturur.
