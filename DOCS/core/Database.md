# Dosya Raporu: core/Database.php

## Amaç
Veritabanı bağlantısı ve Eloquent ORM sarmalayıcısı.

## Genel Bakış
Eloquent Capsule Manager'ı başlatır ve yönetir. Ham SQL sorguları yürütmek ve işlemleri (transactions) yönetmek için statik bir API sağlarken, Eloquent modellerinin uygulama genelinde kullanılmasına olanak tanır.

## Dosya Konumu
`core/Database.php`

## Ad Alanı
`Core`

## İçe Aktarmalar
- `Illuminate\Container\Container`
- `Illuminate\Database\Capsule\Manager as Capsule`
- `Illuminate\Database\Connection`
- `Illuminate\Events\Dispatcher`
- `PDO`

## Sınıflar
- `class Database`

## Özellikler
- `static ?Capsule $instance`: Eloquent Capsule örneği.

## Metotlar
- `init(array $config): Capsule`: Eloquent Capsule'ü sağlanan yapılandırma ile başlatır (MySQL veya SQLite). Olay dağıtıcısını kurar ve Eloquent'i başlatır.
- `getConnection(string $name = 'default'): Connection`: Eloquent bağlantı nesnesini döndürür.
- `statement(string $sql, array $bindings = []): bool`: Ham bir SQL ifadesi yürütür.
- `select(string $sql, array $bindings = []): array`: Bir select sorgusu yürütür ve sonuçları döndürür.
- `selectOne(string $sql, array $bindings = []): ?object`: Bir select sorgusu yürütür ve ilk sonucu döndürür.
- `insert(string $sql, array $bindings = []): bool`: Bir insert sorgusu yürütür.
- `update(string $sql, array $bindings = []): int`: Bir update sorgusu yürütür ve etkilenen satır sayısını döndürür.
- `delete(string $sql, array $bindings = []): int`: Bir delete sorgusu yürütür ve etkilenen satır sayısını döndürür.
- `transaction(callable $callback): mixed`: Bir geri çağırma fonksiyonunu (callback) veritabanı işlemi içinde çalıştırır.
- `beginTransaction(): void`: Bir işlem başlatır.
- `commit(): void`: Mevcut işlemi onaylar.
- `rollBack(): void`: Mevcut işlemi geri alır.

## Dahili İş Akışı
- `init()`: PDO bağlantısını yapılandırır, `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION` ayarlarını yapar ve hata ayıklama modu etkinse `DebugBar` için bir sorgu dinleyicisi ekler.

## Bağımlılıklar
- `Illuminate\Database\Capsule\Manager` (Kullanır)
- `Illuminate\Events\Dispatcher` (Kullanır)
- `Core\DebugBar` (İsteğe bağlı profilleme)

## Kaynak Referansları
- `core/Database.php:1-225`
