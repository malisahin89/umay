<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Migrator;

/**
 * DB Setup — runs migrate/seed/fresh from the browser without a console.
 * DB Setup — konsol olmadan tarayıcıdan migrate/seed/fresh çalıştırır.
 *
 * Requires APP_KEY in the URL: /db-setup/{APP_KEY}?action=migrate|seed|fresh
 * URL'de APP_KEY gerekir: /db-setup/{APP_KEY}?action=migrate|seed|fresh
 *
 * Disabled in production — only local (or CLI). Fail-safe: an unset APP_ENV is
 * treated as production → disabled.
 * Production'da kapalı — yalnızca local (veya CLI). Fail-safe: APP_ENV tanımsızsa
 * production sayılır → kapalı.
 */
class DbSetupController
{
    public function handle(string $key): void
    {
        // Fully disabled unless local. Missing APP_ENV → treated as production.
        // Local değilse tamamen kapalı. APP_ENV yoksa → production sayılır.
        if (($_ENV['APP_ENV'] ?? 'production') !== 'local') {
            abort(404);
        }

        // Timing-safe APP_KEY check — prevents unauthorized execution / brute force.
        // Zamanlama-güvenli APP_KEY kontrolü — yetkisiz çalıştırmayı / brute force'u önler.
        $appKey = is_string($k = $_ENV['APP_KEY'] ?? null) ? $k : '';
        if (! hash_equals($appKey, $key)) {
            http_response_code(403);
            echo '⛔ Yetkisiz erişim.';
            exit;
        }

        $action = is_string($a = $_GET['action'] ?? null) ? $a : 'migrate';

        echo "<pre style='font-family:monospace;font-size:14px;padding:20px;background:#1a1a2e;color:#e0e0e0;border-radius:8px;max-width:600px;margin:40px auto'>";
        echo "🔧 Umay DB Setup\n".str_repeat('─', 40)."\n\n";

        try {
            if ($action === 'fresh') {
                $results = Migrator::runFresh();
                /** @var array{count: int, files: string[]} $results */
                echo "🗑️  Tüm tablolar silindi\n";
                echo "✅ {$results['count']} migration çalıştırıldı\n";
                foreach ($results['files'] as $f) {
                    echo "   ├─ $f\n";
                }
                echo "🌱 Seeder'lar çalıştırıldı\n";
            } elseif ($action === 'seed') {
                Migrator::runSeedersOnly();
                echo "🌱 Seeder'lar çalıştırıldı\n";
            } else {
                $results = Migrator::runMigrationsOnly();
                /** @var array{count: int, files: string[]} $results */
                echo "✅ {$results['count']} migration çalıştırıldı\n";
                foreach ($results['files'] as $f) {
                    echo "   ├─ $f\n";
                }
                if ($results['count'] === 0) {
                    echo "   └─ Bekleyen migration yok\n";
                }
            }
        } catch (\Throwable $e) {
            echo '❌ Hata: '.htmlspecialchars($e->getMessage())."\n";
        }

        echo "\n".str_repeat('─', 40)."\n🎉 İşlem tamamlandı!\n\n";
        echo "?action=migrate → Bekleyen migration'lar\n";
        echo "?action=seed    → Seeder'lar\n";
        echo "?action=fresh   → Sıfırla + migrate + seed\n";
        echo '</pre>';
        exit;
    }
}
