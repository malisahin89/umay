# Validator.php

## Amaç
`Validator` sınıfı, verileri bir dizi kurala göre doğrulamak için kullanılan bağımsız bir doğrulama motorudur. `validate()` yardımcısı ve `Core\FormRequest` tarafından kullanılır.

## Metadatalar
- **Ad Alanı**: `Core`
- **Dosya Konumu**: `core/Validator.php`

## Bağımlılıklar
- `Core\Database` (`unique` ve `exists` kuralları için `DB` facade üzerinden)

## Temel Metotlar
- `make(array $data, array $rules, array $messages)`: Bir doğrulayıcı örneği oluşturan ve doğrulamayı çalıştıran fabrika metodudur.
- `fails()`: Doğrulama hataları bulunduğunda true döndürür.
- `passes()`: Doğrulama hatası bulunmadığında true döndürür.
- `errors()`: Doğrulama hata mesajlarının bir dizisini döndürür.

## Dahili İş Akışı
1. **Kural İşleme**: `run()` metodu tanımlanan kurallar üzerinde döner.
2. **Koşullu Doğrulama**: `sometimes` kuralı, bir alan girdide eksikse doğrulamanın atlanmasına izin verir.
3. **Değer İşleme**:
    - **Ham Değer**: Baştaki/sondaki boşlukları korumak için uzunluk ve eşitlik kontrollerinde (min/max/confirmed) kullanılır.
    - **Kırpılmış (Trimmed) Değer**: Format kontrollerinde (email/numeric/url) kullanılır.
4. **Kural Uygulama**: Bir `match` ifadesi, kural isimlerini (örneğin, `required`, `email`, `unique`) belirli dahili doğrulama metotlarına eşler.
5. **Hata Toplama**: Bir kural başarısız olduğunda `addError()` çağrılır. Özel bir mesajı şu sırayla arar: `alan.kural` $\to$ `alan` $\to$ `varsayılan`.

## Desteklenen Kurallar
Doğrulayıcı geniş bir kural yelpazesini destekler:
- **Varlık**: `required`, `sometimes`.
- **String/Sayısal**: `min`, `max`, `numeric`, `integer`, `digits`, `digits_between`, `alpha`, `alpha_num`.
- **Format**: `email`, `url`, `regex`, `date`.
- **Karşılaştırma**: `confirmed`, `same`.
- **Küme Üyeliği**: `in`, `not_in`.
- **Veritabanı**: `unique`, `exists`.
- **Tarih Aralığı**: `before`, `after`.
