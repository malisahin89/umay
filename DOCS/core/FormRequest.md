# Dosya Raporu: core/FormRequest.php

## Amaç
Otomatik doğrulama ve yetkilendirme için özelleştirilmiş İstek (Request) sınıfı.

## Genel Bakış
Doğrulama kurallarını ve yetkilendirme mantığını tanımlamak için yapılandırılmış bir yol sağlamak amacıyla temel `Request` sınıfını genişletir. Kontrolcü metotlarına enjekte edilmek üzere tasarlanmıştır.

## Dosya Konumu
`core/FormRequest.php`

## Ad Alanı
`Core`

## Sınıflar
- `abstract class FormRequest extends Request`

## Özellikler
- `array $validatedData`: Doğrulamadan geçmiş verileri saklar.

## Metotlar
- `rules(): array`: Doğrulama kurallarını tanımlayan soyut metot.
- `messages(): array`: Kurallar için özel hata mesajlarını döndürür.
- `authorize(): bool`: Yetkilendirme kontrolü yapar; 403 Forbidden'ı tetiklemek için `false` döndürür.
- `createFrom(Request $parent): static`: Standart bir `Request`'ten, durumunu koruyarak bir `FormRequest` oluşturan fabrika metodudur.
- `validated(): array`: Başarıyla doğrulanmış verileri döndürür.

## Dahili İş Akışı
1. `resolve()`: Oluşturulduğunda tetiklenir.
2. **Yetkilendirme**: `authorize()` çağrılır. `false` ise 403 döndürür.
3. **Doğrulama**: `rules()` ve `messages()` ile `Validator::make()` kullanır.
4. **Hata İşleme**: Doğrulama başarısız olursa:
    - JSON istekleri için: 422 yanıtı döndürür.
    - Web istekleri için: Hataları ve eski girdileri oturuma flash'lar ve geri yönlendirir.
5. **Veri Filtreleme**: Yalnızca `rules()` içinde tanımlanan anahtarları `$validatedData` içine saklar.

## Bağımlılıklar
- `Core\Request` (Genişletir)
- `Core\Validator` (Kullanır)
- `Core\Response` (Kullanır)

## Kaynak Referansları
- `core/FormRequest.php:1-108`
