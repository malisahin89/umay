<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Facades\View;

class DashboardController
{
    public function index(): void
    {
        View::render('dashboard', [
            'title' => 'Panelim',
            'user' => auth(),
        ]);
    }
}
