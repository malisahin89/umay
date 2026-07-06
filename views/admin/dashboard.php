<?php declare(strict_types=1); ?>
<?php $this->layout('layouts/admin', ['title' => 'Panel']) ?>
<?php
$icons = [
    'posts' => 'fa-newspaper', 'pages' => 'fa-file-lines', 'categories' => 'fa-layer-group',
    'tags' => 'fa-tags', 'products' => 'fa-box', 'slides' => 'fa-images',
    'popups' => 'fa-window-restore', 'menu-items' => 'fa-bars',
];
?>

<div class="mb-6">
    <h2 class="text-xl font-bold tracking-tight text-zinc-900">Genel Bakış</h2>
    <p class="mt-1 text-sm text-zinc-500">İçerik kaynaklarının özeti.</p>
</div>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <?php foreach ($resources as $key => $cfg) { ?>
        <a href="/admin/<?= $this->e($key) ?>"
           class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-zinc-200/70 transition hover:-translate-y-0.5 hover:shadow-lg hover:ring-indigo-200">
            <div class="flex items-center justify-between">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-500 via-violet-500 to-fuchsia-500 text-white shadow-lg shadow-indigo-500/20">
                    <i class="fa-solid <?= $this->e($icons[$key] ?? 'fa-circle') ?>"></i>
                </span>
                <span class="text-3xl font-bold tracking-tight text-zinc-900"><?= (int) ($counts[$key] ?? 0) ?></span>
            </div>
            <div class="mt-4">
                <div class="text-sm font-semibold text-zinc-900 transition group-hover:text-indigo-700"><?= $this->e($cfg['label'] ?? $key) ?></div>
                <div class="mt-0.5 text-xs text-zinc-400"><?= (int) count($cfg['translatable'] ?? []) ?> çevrilebilir alan</div>
            </div>
        </a>
    <?php } ?>
</div>

<div class="mt-8 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-zinc-200/70">
    <h3 class="text-sm font-semibold text-zinc-900">Hızlı Erişim</h3>
    <div class="mt-4 flex flex-wrap gap-3">
        <a href="/admin/posts/create" class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-zinc-800">
            <i class="fa-solid fa-plus text-xs"></i> Yeni Yazı
        </a>
        <a href="/admin/languages" class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50">
            <i class="fa-solid fa-language text-zinc-400"></i> Dilleri Yönet
        </a>
    </div>
</div>
