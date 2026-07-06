<?php

declare(strict_types=1);

namespace Core\Console;

use Core\Migration;
use Core\Migrator;
use Core\Route;
use Core\Seeder;
use Illuminate\Database\Capsule\Manager as DB;

/**
 * Console command runner.
 * Console komut çalıştırıcısı.
 *
 * php umay <command> [arguments]
 *
 * Commands / Komutlar:
 *   key:generate                  → Generate a new APP_KEY // Yeni APP_KEY oluştur
 *   make:controller Name         → app/Controllers/NameController.php
 *   make:model Name              → app/Models/Name.php
 *   make:migration name          → database/migrations/YYYY_MM_DD_HHMMSS_name.php
 *   make:middleware Name         → app/Middleware/NameMiddleware.php
 *   make:request Name            → app/Requests/Name.php
 *   make:mail Name               → app/Mail/Name.php
 *   make:event Name              → app/Events/Name.php
 *   make:listener Name           → app/Listeners/Name.php
 *   make:factory Name            → database/factories/NameFactory.php
 *   make:test Name               → tests/Feature/NameTest.php
 *   make:test Name --unit        → tests/Unit/NameTest.php
 *   migrate                      → Run all migrations // Tüm migration'ları çalıştır
 *   migrate:rollback             → Rollback last migration // Son migration'ı geri al
 *   migrate:fresh                → Drop all tables and re-migrate // Tüm tabloları düşür + yeniden migrate
 *   db:seed                      → Run DatabaseSeeder // DatabaseSeeder çalıştır
 *   route:list                   → List registered routes // Kayıtlı route'ları listele
 *   storage:link                 → Symlink public/storage → storage/app/public
 *   cache:clear                  → Clear all cache files // Tüm cache dosyalarını sil
 *   test                         → Run PHPUnit // PHPUnit çalıştır
 */
class Kernel
{
    private string $basePath;

    private string $stubsPath;

    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, '/\\');
        $this->stubsPath = $this->basePath.'/stubs';
    }

    public function handle(array $argv): int
    {
        $command = $argv[1] ?? 'help';
        $args = array_slice($argv, 2);

        try {
            return match ($command) {
                'key:generate' => $this->keyGenerate($args),
                'make:controller' => $this->makeController($args),
                'make:model' => $this->makeModel($args),
                'make:migration' => $this->makeMigration($args),
                'make:middleware' => $this->makeMiddleware($args),
                'make:request' => $this->makeRequest($args),
                'make:mail' => $this->makeMail($args),
                'make:event' => $this->makeEvent($args),
                'make:listener' => $this->makeListener($args),
                'make:factory' => $this->makeFactory($args),
                'make:test' => $this->makeTest($args),
                'migrate' => $this->migrate($args),
                'migrate:rollback' => $this->migrateRollback(),
                'migrate:fresh' => $this->migrateFresh(),
                'db:seed' => $this->dbSeed($args),
                'route:list' => $this->routeList(),
                'storage:link' => $this->storageLink(),
                'cache:clear' => $this->cacheClear(),
                'test' => $this->runTests($args),
                'help', '--help' => $this->showHelp(),
                default => $this->unknownCommand($command),
            };
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return 1;
        }
    }

    // ── Key generation ───────────────────────────────────────────────────────
    // ── Anahtar oluşturma ────────────────────────────────────────────────────

    /**
     * Generate a new APP_KEY and write it to .env
     * Yeni bir APP_KEY oluştur ve .env'ye yaz
     */
    private function keyGenerate(array $args): int
    {
        // Generate 32 random bytes → 64-char hex string
        // 32 rastgele bayt → 64 karakter hex string
        $key = bin2hex(random_bytes(32));

        $envPath = $this->basePath.'/.env';
        $examplePath = $this->basePath.'/.env.example';

        // If .env does not exist, copy from .env.example
        // .env yoksa .env.example'dan kopyala
        if (! file_exists($envPath)) {
            if (file_exists($examplePath)) {
                copy($examplePath, $envPath);
                $this->info('.env created from .env.example // .env.example\'den .env oluşturuldu.');
            } else {
                file_put_contents($envPath, "APP_KEY={$key}\n");

                return $this->success("APP_KEY generated // oluşturuldu: {$key}");
            }
        }

        $envContent = file_get_contents($envPath);

        // Replace existing APP_KEY line or append it
        // Mevcut APP_KEY satırını değiştir veya ekle
        if (preg_match('/^APP_KEY=.*$/m', $envContent)) {
            $envContent = preg_replace('/^APP_KEY=.*$/m', "APP_KEY={$key}", $envContent);
        } else {
            $envContent .= "\nAPP_KEY={$key}\n";
        }

        file_put_contents($envPath, $envContent);

        // Show confirmation with --show flag support
        // --show flag desteğiyle onay göster
        if (in_array('--show', $args, true)) {
            $this->info("APP_KEY={$key}");
        }

        return $this->success("APP_KEY generated and written to .env // APP_KEY oluşturuldu ve .env'ye yazıldı.");
    }

    // ── Make commands ────────────────────────────────────────────────────────
    // ── Make komutları ────────────────────────────────────────────────────────

    private function makeController(array $args): int
    {
        $name = $args[0] ?? null;
        if (! $name) {
            return $this->error('Controller name is required // Controller adı gerekli. Örn: make:controller User');
        }

        $name = $this->studlyCase($name);
        $className = str_ends_with($name, 'Controller') ? $name : $name.'Controller';
        $path = $this->basePath.'/app/Controllers/'.$className.'.php';

        if (file_exists($path)) {
            return $this->error("File already exists // Dosya zaten mevcut: {$path}");
        }

        $content = $this->renderStub('controller', [
            '{{ClassName}}' => $className,
            '{{viewPrefix}}' => strtolower(str_replace('Controller', '', $className)),
        ]);

        $this->writeFile($path, $content);

        return $this->success("Controller created // oluşturuldu: app/Controllers/{$className}.php");
    }

    private function makeModel(array $args): int
    {
        $name = $args[0] ?? null;
        if (! $name) {
            return $this->error('Model name is required // Model adı gerekli. Örn: make:model Post');
        }

        $className = $this->studlyCase($name);
        $tableName = $this->pluralSnake($className);
        $path = $this->basePath.'/app/Models/'.$className.'.php';

        if (file_exists($path)) {
            return $this->error("File already exists // Dosya zaten mevcut: {$path}");
        }

        $content = $this->renderStub('model', [
            '{{ClassName}}' => $className,
            '{{tableName}}' => $tableName,
        ]);

        $this->writeFile($path, $content);

        return $this->success("Model created // oluşturuldu: app/Models/{$className}.php");
    }

    private function makeMigration(array $args): int
    {
        $name = $args[0] ?? null;
        if (! $name) {
            return $this->error('Migration name is required // Migration adı gerekli. Örn: make:migration create_posts_table');
        }

        $timestamp = date('Y_m_d_His');
        $fileName = $timestamp.'_'.$this->snakeCase($name).'.php';
        $className = $this->studlyCase($name);
        $tableName = $this->guessTableName($name);
        $path = $this->basePath.'/database/migrations/'.$fileName;

        $content = $this->renderStub('migration', [
            '{{ClassName}}' => $className,
            '{{tableName}}' => $tableName,
        ]);

        $this->writeFile($path, $content);

        return $this->success("Migration created // oluşturuldu: database/migrations/{$fileName}");
    }

    private function makeMiddleware(array $args): int
    {
        $name = $args[0] ?? null;
        if (! $name) {
            return $this->error('Middleware name is required // Middleware adı gerekli. Örn: make:middleware Auth');
        }

        // Suffix is carried in the class name (like controller/request/mail) so the
        // file name and class name always match — even if the user already typed 'Middleware'.
        // Suffix sınıf adında taşınır (controller/request/mail gibi) — kullanıcı 'Middleware'
        // ekiyle yazsa bile dosya adı ile sınıf adı her zaman eşleşir.
        $studly = $this->studlyCase($name);
        $className = str_ends_with($studly, 'Middleware') ? $studly : $studly.'Middleware';
        $path = $this->basePath.'/app/Middleware/'.$className.'.php';

        if (file_exists($path)) {
            return $this->error("File already exists // Dosya zaten mevcut: {$path}");
        }

        $content = $this->renderStub('middleware', ['{{ClassName}}' => $className]);
        $this->writeFile($path, $content);

        return $this->success("Middleware created // oluşturuldu: app/Middleware/{$className}.php");
    }

    private function makeRequest(array $args): int
    {
        $name = $args[0] ?? null;
        if (! $name) {
            return $this->error('Request name is required // Request adı gerekli. Örn: make:request StoreUser');
        }

        $className = $this->studlyCase($name);
        if (! str_ends_with($className, 'Request')) {
            $className .= 'Request';
        }

        $dir = $this->basePath.'/app/Requests';
        $path = $dir.'/'.$className.'.php';

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        if (file_exists($path)) {
            return $this->error("File already exists // Dosya zaten mevcut: {$path}");
        }

        $content = $this->renderStub('request', ['{{ClassName}}' => $className]);
        $this->writeFile($path, $content);

        return $this->success("Request created // oluşturuldu: app/Requests/{$className}.php");
    }

    private function makeMail(array $args): int
    {
        $name = $args[0] ?? null;
        if (! $name) {
            return $this->error('Mail name is required // Mail adı gerekli. Örn: make:mail Welcome');
        }

        $className = $this->studlyCase($name);
        if (! str_ends_with($className, 'Mail')) {
            $className .= 'Mail';
        }

        $dir = $this->basePath.'/app/Mail';
        $path = $dir.'/'.$className.'.php';

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        if (file_exists($path)) {
            return $this->error("File already exists // Dosya zaten mevcut: {$path}");
        }

        $content = $this->renderStub('mail', ['{{ClassName}}' => $className]);
        $this->writeFile($path, $content);

        return $this->success("Mail created // oluşturuldu: app/Mail/{$className}.php");
    }

    private function makeEvent(array $args): int
    {
        $name = $args[0] ?? null;
        if (! $name) {
            return $this->error('Event name is required // Event adı gerekli. Örn: make:event UserRegistered');
        }

        $className = $this->studlyCase($name);
        $dir = $this->basePath.'/app/Events';
        $path = $dir.'/'.$className.'.php';

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        if (file_exists($path)) {
            return $this->error("File already exists // Dosya zaten mevcut: {$path}");
        }

        $content = $this->renderStub('event', ['{{ClassName}}' => $className]);
        $this->writeFile($path, $content);

        return $this->success("Event created // oluşturuldu: app/Events/{$className}.php");
    }

    private function makeListener(array $args): int
    {
        $name = $args[0] ?? null;
        if (! $name) {
            return $this->error('Listener name is required // Listener adı gerekli. Örn: make:listener SendWelcomeEmail');
        }

        $className = $this->studlyCase($name);
        $dir = $this->basePath.'/app/Listeners';
        $path = $dir.'/'.$className.'.php';

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        if (file_exists($path)) {
            return $this->error("File already exists // Dosya zaten mevcut: {$path}");
        }

        $content = $this->renderStub('listener', ['{{ClassName}}' => $className]);
        $this->writeFile($path, $content);

        return $this->success("Listener created // oluşturuldu: app/Listeners/{$className}.php");
    }

    private function makeFactory(array $args): int
    {
        $name = $args[0] ?? null;
        if (! $name) {
            return $this->error('Factory name is required // Factory adı gerekli. Örn: make:factory User');
        }

        $modelClass = $this->studlyCase($name);
        $factoryName = $modelClass.'Factory';
        $dir = $this->basePath.'/database/factories';
        $path = $dir.'/'.$factoryName.'.php';

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        if (file_exists($path)) {
            return $this->error("File already exists // Dosya zaten mevcut: {$path}");
        }

        $content = $this->renderStub('factory', [
            '{{ClassName}}' => $factoryName,
            '{{ModelClass}}' => $modelClass,
        ]);
        $this->writeFile($path, $content);

        return $this->success("Factory created // oluşturuldu: database/factories/{$factoryName}.php");
    }

    private function makeTest(array $args): int
    {
        $name = $args[0] ?? null;
        $isUnit = in_array('--unit', $args, true);

        if (! $name) {
            return $this->error('Test name is required // Test adı gerekli. Örn: make:test UserAuth');
        }

        $className = $this->studlyCase($name);
        if (! str_ends_with($className, 'Test')) {
            $className .= 'Test';
        }

        $subDir = $isUnit ? 'Unit' : 'Feature';
        $dir = $this->basePath.'/tests/'.$subDir;
        $path = $dir.'/'.$className.'.php';

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        if (file_exists($path)) {
            return $this->error("File already exists // Dosya zaten mevcut: {$path}");
        }

        $content = $this->renderStub('test', [
            '{{ClassName}}' => $className,
            '{{namespace}}' => $subDir,
        ]);
        $this->writeFile($path, $content);

        return $this->success("Test created // oluşturuldu: tests/{$subDir}/{$className}.php");
    }

    private function runTests(array $args): int
    {
        $phpunit = $this->basePath.'/vendor/bin/phpunit';
        if (! file_exists($phpunit)) {
            return $this->error('PHPUnit not found. First run: // PHPUnit bulunamadı. Önce: composer install');
        }

        $suite = '';
        if (in_array('--unit', $args)) {
            $suite = ' --testsuite Unit';
        }
        if (in_array('--feature', $args)) {
            $suite = ' --testsuite Feature';
        }

        $this->info('Running PHPUnit... // PHPUnit çalıştırılıyor...');
        // Yol tırnaklı — proje kökü boşluk içerirse ("C:\my projects\umay") komut kırılırdı.
        // Path is quoted — an unquoted root with spaces ("C:\my projects\umay") broke the command.
        passthru('php "'.$phpunit.'"'.$suite, $exitCode);

        return $exitCode;
    }

    // ── Migrate commands ──────────────────────────────────────────────────────
    // ── Migrate komutları ─────────────────────────────────────────────────────

    private function migrate(array $args): int
    {
        $this->info('Running migrations... // Migration çalıştırılıyor...');
        $this->bootstrapDb();
        $results = Migrator::runMigrationsOnly();
        foreach ($results['files'] as $f) {
            $this->info("  Executed // Çalıştırıldı: $f");
        }

        return $this->success("{$results['count']} migrations completed // migration tamamlandı.");
    }

    private function migrateRollback(): int
    {
        $this->info('Rolling back last migration... // Son migration geri alınıyor...');
        $this->bootstrapDb();

        // Migration tablosu yoksa geri alınacak bir şey yok
        // Nothing to roll back if the migrations table does not exist
        if (! DB::schema()->hasTable('migrations')) {
            return $this->error('No migration to roll back. // Geri alınacak migration yok.');
        }

        // Son çalıştırılan migration'ı kayda göre seç (dosya adı sırasına göre değil)
        // Pick the last-run migration by record (not by filename order)
        $last = DB::table('migrations')->orderBy('batch', 'desc')->orderBy('id', 'desc')->first();
        if ($last === null) {
            return $this->error('No migration to roll back. // Geri alınacak migration yok.');
        }

        $file = $this->basePath.'/database/migrations/'.$last->migration.'.php';
        if (! file_exists($file)) {
            return $this->error('Migration file not found // Migration dosyası bulunamadı: '.$last->migration);
        }

        // Migration dosyaları anonim sınıf döndürür (return new class extends Migration)
        // Migration files return an anonymous class (return new class extends Migration)
        $migration = require $file;
        if (! $migration instanceof Migration) {
            return $this->error('Invalid migration file // Geçersiz migration dosyası: '.basename($file));
        }

        $migration->down();

        // Kaydı sil — yoksa sonraki migrate bu migration'ı atlar ve düşen tablo yeniden oluşturulmaz
        // Delete the record — otherwise the next migrate skips it and the dropped table is never recreated
        DB::table('migrations')->where('migration', $last->migration)->delete();

        $this->success('Rolled back // Geri alındı: '.basename($file));

        return 0;
    }

    private function migrateFresh(): int
    {
        $this->info('Initializing Fresh DB... // Fresh DB başlatılıyor...');
        $this->bootstrapDb();
        $results = Migrator::runFresh();
        foreach ($results['files'] as $f) {
            $this->info("  Executed // Çalıştırıldı: $f");
        }

        return $this->success("migrate:fresh completed // tamamlandı. ({$results['count']} migration + seed)");
    }

    private function dbSeed(array $args): int
    {
        $this->info('Running seeder... // Seeder çalıştırılıyor...');
        $this->bootstrapDb();

        // Argüman verilmişse o seeder'ı çalıştır; yoksa DatabaseSeeder.
        // Run the requested seeder when an argument is given; otherwise DatabaseSeeder.
        $arg = $args[0] ?? 'DatabaseSeeder';
        $requested = is_string($arg) ? $arg : 'DatabaseSeeder';
        $fqcn = str_contains($requested, '\\') ? $requested : 'Database\\Seeders\\'.$requested;

        $seederPath = $this->basePath.'/database/seeders/DatabaseSeeder.php';
        if (! file_exists($seederPath)) {
            return $this->error("DatabaseSeeder not found // bulunamadı: {$seederPath}");
        }

        // Tüm seeder dosyalarını yükle (bağımlılıklar + hedef sınıf için)
        // Load all seeder files (for dependencies + the target class)
        foreach (glob($this->basePath.'/database/seeders/*.php') ?: [] as $f) {
            require_once $f;
        }

        if (! class_exists($fqcn)) {
            return $this->error("Seeder not found // bulunamadı: {$fqcn}");
        }

        $seeder = new $fqcn;
        if (! $seeder instanceof Seeder) {
            return $this->error("Not a seeder // seeder değil: {$fqcn}");
        }

        $seeder->run();

        return $this->success("Seeder completed // tamamlandı: {$fqcn}");
    }

    // ── Helper commands ───────────────────────────────────────────────────────
    // ── Yardımcı komutlar ─────────────────────────────────────────────────────

    private function routeList(): int
    {
        $this->bootstrapRoutes();

        $routes = Route::getRoutes();
        if (empty($routes)) {
            return $this->error('No routes registered. // Hiç route kayıtlı değil.');
        }

        $this->line(str_pad('METHOD', 10).str_pad('URI', 40).str_pad('ACTION', 50).'MIDDLEWARE');
        $this->line(str_repeat('-', 110));

        foreach ($routes as $method => $methodRoutes) {
            foreach ($methodRoutes as $uri => $route) {
                if (! isset($route['action'])) {
                    continue;
                }
                $actionStr = $route['action'] instanceof \Closure ? 'Closure' : $route['action'];
                $middleware = implode(',', $route['middleware'] ?? []);
                $this->line(
                    str_pad($method, 10).
                    str_pad($uri, 40).
                    str_pad($actionStr, 50).
                    $middleware
                );
            }
        }

        return 0;
    }

    /**
     * Symlink public/storage → storage/app/public so uploaded files are web-served.
     * public/storage → storage/app/public symlink'i kur; yüklenen dosyalar web'den servis edilsin.
     */
    private function storageLink(): int
    {
        $target = $this->basePath.'/storage/app/public';
        $link = $this->basePath.'/public/storage';

        if (! is_dir($target) && ! mkdir($target, 0775, true) && ! is_dir($target)) {
            return $this->error("Target directory could not be created // Hedef dizin oluşturulamadı: {$target}");
        }

        if (is_link($link) || file_exists($link)) {
            return $this->success('public/storage already exists // zaten var.');
        }

        if (@symlink($target, $link)) {
            return $this->success('Linked // Bağlandı: public/storage → storage/app/public');
        }

        // Windows without Developer Mode / admin can't create symlinks. Fall back to a
        // directory junction (mklink /J), which needs no elevation and behaves the same
        // for serving files.
        // Developer Mode / admin olmadan Windows symlink oluşturamaz. Dizin junction'ına
        // (mklink /J) düş — yükseltme gerektirmez ve dosya servisinde aynı davranır.
        if (PHP_OS_FAMILY === 'Windows') {
            $winLink = str_replace('/', '\\', $link);
            $winTarget = str_replace('/', '\\', $target);
            $out = [];
            $code = 1;
            exec('cmd /c mklink /J "'.$winLink.'" "'.$winTarget.'" 2>&1', $out, $code);
            if ($code === 0) {
                return $this->success('Junction created // oluşturuldu: public/storage → storage/app/public');
            }
        }

        return $this->error(
            "Could not link // bağlanamadı. Manuel (yönetici CMD): ".
            'mklink /J "'.str_replace('/', '\\', $link).'" "'.str_replace('/', '\\', $target).'"'
        );
    }

    private function cacheClear(): int
    {
        $cacheDir = $this->basePath.'/storage/cache';
        if (! is_dir($cacheDir)) {
            return $this->success('Cache directory not found (already clean) // Cache dizini bulunamadı (zaten temiz).');
        }

        $files = glob($cacheDir.'/*.cache') ?: [];
        $deleted = 0;
        foreach ($files as $file) {
            unlink($file);
            $deleted++;
        }

        // Cache::flush() ile aynı kapsam: atomic()'in .lock/.tmp yan dosyalarını da
        // temizle (@ — başka bir süreç kilidi o an tutuyorsa sessizce atla).
        // Same scope as Cache::flush(): also remove atomic()'s .lock/.tmp sidecars
        // (@ — silently skip if another process holds the lock right now).
        foreach (array_merge(glob($cacheDir.'/*.lock') ?: [], glob($cacheDir.'/*.tmp') ?: []) as $file) {
            @unlink($file);
        }

        return $this->success("{$deleted} cache files deleted // cache dosyası silindi.");
    }

    private function showHelp(): int
    {
        $logo = <<<'EOT'
                          ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑                          
                    ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑                     
                ↑↑↑↑↑↑↑↑↑↑↑↑↑                 ↑↑↑↑                 
              ↑↑↑↑↑↑↑↑↑↑↑                          ↑↑              
           ↑↑↑↑↑↑↑↑↑↑↑                                ↑            
         ↑↑↑↑↑↑↑↑↑↑                                                
        ↑↑↑↑↑↑↑↑↑                                                  
      ↑↑↑↑↑↑↑↑↑↑                                                   
     ↑↑↑↑↑↑↑↑↑                ↑↑↑↑↑↑↑↑↑↑↑↑                         
    ↑↑↑↑↑↑↑↑↑              ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑                      
   ↑↑↑↑↑↑↑↑↑             ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑                     
  ↑↑↑↑↑↑↑↑↑                        ↑↑↑↑↑↑↑↑↑↑↑↑                    
 ↑↑↑↑↑↑↑↑↑↑                          ↑↑↑↑↑↑↑↑↑↑↑                   
 ↑↑↑↑↑↑↑↑↑                            ↑↑↑↑↑↑↑↑↑↑                   
 ↑↑↑↑↑↑↑↑↑ ↑                           ↑↑↑↑↑↑↑↑↑ ↑↑↑↑↑↑↑↑↑         
↑↑↑↑↑↑↑↑↑↑ ↑                           ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑        
↑↑↑↑↑↑↑↑↑↑ ↑↑                          ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑        
↑↑↑↑↑↑↑↑↑↑ ↑↑↑↑                       ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑         
↑↑↑↑↑↑↑↑↑↑ ↑↑↑↑↑↑                    ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑         ↑
↑↑↑↑↑↑↑↑↑↑  ↑↑↑↑↑↑↑↑              ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑            ↑
↑↑↑↑↑↑↑↑↑↑  ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑         ↑↑
↑↑↑↑↑↑↑↑↑↑↑   ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑       ↑ 
 ↑↑↑↑↑↑↑↑↑↑↑   ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑      ↑↑ 
 ↑↑↑↑↑↑↑↑↑↑↑     ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑        ↑↑↑↑↑↑↑    ↑↑  
  ↑↑↑↑↑↑↑↑↑↑↑      ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑     ↑↑↑↑   ↑↑↑↑↑↑   ↑↑↑  
   ↑↑↑↑↑↑↑↑↑↑↑         ↑↑↑↑↑↑↑↑↑↑↑↑↑↑        ↑↑↑  ↑  ↑↑↑↑↑   ↑↑↑   
    ↑↑↑↑↑↑↑↑↑↑↑↑                            ↑↑↑↑    ↑↑↑↑↑↑  ↑↑↑    
     ↑↑↑↑↑↑↑↑↑↑↑↑                           ↑↑↑↑↑↑↑↑↑↑↑↑  ↑↑↑↑     
      ↑↑↑↑↑↑↑↑↑↑↑↑↑                          ↑↑↑↑↑↑↑↑↑  ↑↑↑↑       
        ↑↑↑↑↑↑↑↑↑↑↑↑↑↑                           ↑↑    ↑↑↑↑        
         ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑                          ↑↑↑↑↑↑          
           ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑                ↑↑↑↑↑↑↑↑↑↑           
              ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑              
                ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑                 
                    ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑                    
                         ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑                         
EOT;

        $this->line("\033[36m{$logo}\033[0m");
        $this->line('');
        $this->line("  \033[32mUmay Console\033[0m");
        $this->line('');
        $this->line("  \033[33mUsage / Kullanım:\033[0m");
        $this->line('    php umay <komut> [argümanlar]');
        $this->line('');
        $this->line("  \033[33mSetup Commands / Kurulum Komutları:\033[0m");
        $this->line('    key:generate               Yeni APP_KEY oluştur');
        $this->line('    key:generate --show        APP_KEY oluştur ve göster');
        $this->line('');
        $this->line("  \033[33mMake Commands / Make Komutları:\033[0m");
        $this->line('    make:controller Name       Controller oluştur');
        $this->line('    make:model Name            Model oluştur');
        $this->line('    make:migration name        Migration oluştur');
        $this->line('    make:middleware Name       Middleware oluştur');
        $this->line('    make:request Name          FormRequest oluştur');
        $this->line('    make:mail Name             Mailable oluştur');
        $this->line('    make:event Name            Event oluştur');
        $this->line('    make:listener Name         Listener oluştur');
        $this->line('    make:factory Name          Factory oluştur');
        $this->line('    make:test Name [--unit]    Test oluştur (Feature/Unit)');
        $this->line('');
        $this->line("  \033[33mDatabase Commands / Database Komutları:\033[0m");
        $this->line("    migrate                    Migration'ları çalıştır");
        $this->line("    migrate:rollback           Son migration'ı geri al");
        $this->line('    migrate:fresh              Sıfırdan migrate et');
        $this->line("    db:seed                    Seeder'ları çalıştır");
        $this->line('');
        $this->line("  \033[33mOther / Diğer:\033[0m");
        $this->line('    route:list                 Route listesini göster');
        $this->line('    storage:link               public/storage symlink oluştur');
        $this->line("    cache:clear                Cache'i temizle");
        $this->line('    test [--unit|--feature]    PHPUnit testlerini çalıştır');
        $this->line('');

        return 0;
    }

    private function unknownCommand(string $command): int
    {
        return $this->error("Unknown command // Bilinmeyen komut: {$command}. Run 'php umay help' for help // yardım alın.");
    }

    // ── Internal helpers ──────────────────────────────────────────────────────
    // ── Dahili yardımcılar ────────────────────────────────────────────────────

    private function bootstrapDb(): void
    {
        if (! defined('BASE_PATH')) {
            define('BASE_PATH', $this->basePath);
        }
        require_once $this->basePath.'/vendor/autoload.php';
        require_once $this->basePath.'/core/helpers.php';
        require_once $this->basePath.'/config/database.php';
    }

    private function bootstrapRoutes(): void
    {
        $this->bootstrapDb();

        // Mirror RouteServiceProvider so route:list reflects the real routing table:
        // web group, then api group under api_prefix.
        // route:list'in gerçek routing tablosunu yansıtması için RouteServiceProvider'ı
        // taklit et: web grubu, sonra api_prefix altında api grubu.
        Route::setGroup('web');
        require $this->basePath.'/routes/web.php';

        $apiRoutes = $this->basePath.'/routes/api.php';
        if (file_exists($apiRoutes)) {
            $apiPrefix = (string) config('middleware.api_prefix', '/api');
            Route::setGroup('api');
            Route::prefix($apiPrefix)->group(function () use ($apiRoutes) {
                require $apiRoutes;
            });
            Route::setGroup('web');
        }
    }

    private function renderStub(string $stub, array $replacements): string
    {
        $path = $this->stubsPath.'/'.$stub.'.stub';
        if (! file_exists($path)) {
            throw new \RuntimeException("Stub not found // bulunamadı: {$path}");
        }
        $content = file_get_contents($path);

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }

    private function writeFile(string $path, string $content): void
    {
        $dir = dirname($path);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        file_put_contents($path, $content);
    }

    private function studlyCase(string $value): string
    {
        // Harf/rakam dışı HER karakter ayraç sayılır (-, _, /, \, nokta…) — üretilen ad
        // dosya yoluna girdiğinden "Blog/Post" ya da "../../evil" gibi bir girdi hedef
        // dizinin dışına dosya yazamamalı.
        // EVERY non-alphanumeric character is a separator (-, _, /, \, dots…) — the
        // generated name lands in a file path, so input like "Blog/Post" or
        // "../../evil" must not be able to write outside the target directory.
        $normalized = preg_replace('/[^a-zA-Z0-9]+/', ' ', $value) ?? '';
        $studly = str_replace(' ', '', ucwords(trim($normalized)));

        if ($studly === '') {
            throw new \InvalidArgumentException(
                "Invalid name // Geçersiz ad: {$value} (only letters/digits/-/_ // yalnızca harf/rakam/-/_)"
            );
        }

        return $studly;
    }

    private function snakeCase(string $value): string
    {
        $snake = strtolower(preg_replace('/[A-Z]/', '_$0', lcfirst($value)) ?? $value);

        // Migration DOSYA adına girer — path ayracı/nokta gibi karakterleri süz
        // (bkz. studlyCase'teki gerekçe).
        // Lands in the migration FILE name — strip path separators/dots etc.
        // (see the rationale in studlyCase).
        $snake = preg_replace('/[^a-z0-9_]+/', '_', $snake) ?? $snake;

        return trim($snake, '_');
    }

    private function pluralSnake(string $value): string
    {
        $snake = $this->snakeCase($value);
        // Simple pluralization (English)
        // Basit çoğullama (İngilizce)
        if (str_ends_with($snake, 'y')) {
            return rtrim($snake, 'y').'ies';
        }

        return $snake.'s';
    }

    private function guessTableName(string $migrationName): string
    {
        // "create_posts_table" → "posts"
        if (preg_match('/create_(.+)_table/', $migrationName, $m)) {
            return $m[1];
        }
        // "add_status_to_posts_table" → "posts"
        if (preg_match('/to_(.+)_table/', $migrationName, $m)) {
            return $m[1];
        }

        return $this->snakeCase($migrationName);
    }

    // ── Output methods ────────────────────────────────────────────────────────
    // ── Output metodları ──────────────────────────────────────────────────────

    private function success(string $message): int
    {
        echo "\033[32m  ✓ {$message}\033[0m\n";

        return 0;
    }

    private function error(string $message): int
    {
        echo "\033[31m  ✗ {$message}\033[0m\n";

        return 1;
    }

    private function info(string $message): void
    {
        echo "\033[36m  → {$message}\033[0m\n";
    }

    private function line(string $message): void
    {
        echo $message."\n";
    }
}
