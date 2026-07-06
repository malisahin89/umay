<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Facades\View;
use Core\Model;

class DashboardController
{
    public function index(): void
    {
        /** @var array<string, array<string, mixed>> $resources */
        $resources = is_array($r = config('admin_resources')) ? $r : [];

        // Record count per resource, shown on each dashboard card.
        // Kaynak başına kayıt sayısı; her dashboard kartında gösterilir.
        $counts = [];
        foreach ($resources as $key => $cfg) {
            $counts[$key] = 0;
            if (is_string($class = $cfg['model'] ?? null) && is_subclass_of($class, Model::class)) {
                /** @var class-string<Model> $class */
                $counts[$key] = $class::query()->count();
            }
        }

        View::render('admin/dashboard', [
            'title' => 'Yönetim Paneli',
            'resources' => $resources,
            'counts' => $counts,
        ]);
    }
}
