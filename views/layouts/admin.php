<?php declare(strict_types=1); ?>
<?php
/**
 * Admin layout — premium dark sidebar (Linear/Vercel-style) with grouped navigation, a
 * clean white topbar, and a light zinc canvas with soft ring cards. Indigo accent.
 * Nested on layouts/base for the <head> (Tailwind, Inter, helpers).
 *
 * Admin layout — premium koyu sidebar (Linear/Vercel tarzı), gruplu nav, temiz beyaz
 * topbar, açık zinc kanvas + yumuşak ring kartlar. Indigo vurgu.
 */
$this->layout('layouts/base', ['title' => $title ?? 'Yönetim']);

$resources = is_array($r = $this->config('admin_resources')) ? $r : [];
$currentPath = (string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

$groups = [
    'İçerik' => ['posts', 'pages', 'categories', 'tags'],
    'Katalog' => ['products'],
    'Görünüm' => ['slides', 'popups'],
    'Menü' => ['menu-items'],
];
$icons = [
    'posts' => 'fa-newspaper', 'pages' => 'fa-file-lines', 'categories' => 'fa-layer-group',
    'tags' => 'fa-tags', 'products' => 'fa-box', 'slides' => 'fa-images',
    'popups' => 'fa-window-restore', 'menu-items' => 'fa-bars', 'languages' => 'fa-language',
];
$grouped = array_merge(...array_values($groups));
$others = array_values(array_diff(array_keys($resources), $grouped));
if ($others !== []) {
    $groups['Diğer'] = $others;
}

$navLink = function (string $href, string $icon, string $label, bool $active): string {
    $state = $active ? 'bg-white/10 text-white' : 'text-zinc-400 hover:bg-white/5 hover:text-zinc-100';
    $iconCls = $active ? 'text-indigo-400' : 'text-zinc-500 group-hover/nav:text-zinc-300';

    return '<a href="'.$this->e($href).'" class="group/nav relative flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition '.$state.'">'
        .($active ? '<span class="absolute inset-y-1.5 left-0 w-0.5 rounded-full bg-gradient-to-b from-indigo-400 to-violet-500"></span>' : '')
        .'<i class="fa-solid '.$icon.' w-4 text-center '.$iconCls.'"></i><span>'.$this->e($label).'</span></a>';
};

$user = $this->auth();
$userName = is_object($user) && isset($user->name) && is_string($user->name) ? $user->name : 'Admin';
?>
<?php $this->start('body') ?>
<div class="min-h-screen bg-zinc-50">

    <!-- SIDEBAR -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full bg-zinc-950 transition-transform lg:translate-x-0">
        <div class="flex h-16 items-center gap-2.5 px-5">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-tr from-indigo-500 via-violet-500 to-fuchsia-500 text-white shadow-lg shadow-indigo-500/20"><i class="fa-solid fa-bolt text-sm"></i></span>
            <span class="text-[15px] font-bold text-white">Umay <span class="text-zinc-500">Admin</span></span>
        </div>

        <nav class="flex h-[calc(100vh-4rem)] flex-col gap-5 overflow-y-auto px-3 pb-5">
            <div class="space-y-0.5">
                <?= $navLink('/admin', 'fa-gauge', 'Panel', $currentPath === '/admin') ?>
            </div>
            <?php foreach ($groups as $groupLabel => $keys) { ?>
                <div>
                    <p class="mb-1 px-3 text-[10px] font-semibold uppercase tracking-wider text-zinc-600"><?= $this->e($groupLabel) ?></p>
                    <div class="space-y-0.5">
                        <?php foreach ($keys as $key) { ?>
                            <?php if (! isset($resources[$key])) { continue; } ?>
                            <?php
                            $label = is_string($resources[$key]['label'] ?? null) ? $resources[$key]['label'] : $key;
                            $active = str_starts_with($currentPath, '/admin/'.$key);
                            ?>
                            <?= $navLink('/admin/'.$key, $icons[$key] ?? 'fa-circle', $label, $active) ?>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <div>
                <p class="mb-1 px-3 text-[10px] font-semibold uppercase tracking-wider text-zinc-600">Sistem</p>
                <div class="space-y-0.5">
                    <?= $navLink('/admin/languages', 'fa-language', 'Diller', str_starts_with($currentPath, '/admin/languages')) ?>
                </div>
            </div>

            <div class="mt-auto space-y-0.5 border-t border-white/5 pt-4">
                <a href="/" target="_blank" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-zinc-400 transition hover:bg-white/5 hover:text-zinc-100">
                    <i class="fa-solid fa-arrow-up-right-from-square w-4 text-center text-zinc-500"></i> Siteyi Gör
                </a>
                <form method="POST" action="/logout">
                    <?= $this->csrf() ?>
                    <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-zinc-400 transition hover:bg-red-500/10 hover:text-red-400">
                        <i class="fa-solid fa-right-from-bracket w-4 text-center text-zinc-500"></i> Çıkış
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <div id="sidebarOverlay" class="fixed inset-0 z-30 hidden bg-zinc-950/40 backdrop-blur-sm lg:hidden"></div>

    <!-- MAIN -->
    <div class="lg:pl-64">
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-zinc-200 bg-white/80 px-6 backdrop-blur">
            <div class="flex items-center gap-3">
                <button id="sidebarToggle" class="text-zinc-500 lg:hidden" aria-label="Menü"><i class="fa-solid fa-bars"></i></button>
                <h1 class="text-[17px] font-semibold tracking-tight text-zinc-900"><?= $this->e((string) ($title ?? 'Yönetim')) ?></h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden text-sm text-zinc-500 sm:inline"><?= $this->e($userName) ?></span>
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-tr from-indigo-500 via-violet-500 to-fuchsia-500 text-sm font-semibold text-white"><?= $this->e(mb_strtoupper(mb_substr($userName, 0, 1))) ?></span>
            </div>
        </header>

        <?php if (! empty($success)) { ?>
            <div class="mx-6 mt-6 flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                <i class="fa-solid fa-circle-check text-emerald-500"></i> <?= $this->e($success) ?>
            </div>
        <?php } ?>
        <?php if (! empty($error)) { ?>
            <div class="mx-6 mt-6 flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <i class="fa-solid fa-circle-exclamation text-red-500"></i> <?= $this->e($error) ?>
            </div>
        <?php } ?>

        <main class="p-6"><?= $this->section('content') ?></main>
    </div>
</div>

<script nonce="<?= $this->nonce() ?>">
(function () {
    var sb = document.getElementById('sidebar'), ov = document.getElementById('sidebarOverlay'), tg = document.getElementById('sidebarToggle');
    if (tg) tg.addEventListener('click', function () { sb.classList.remove('-translate-x-full'); ov.classList.remove('hidden'); });
    if (ov) ov.addEventListener('click', function () { sb.classList.add('-translate-x-full'); ov.classList.add('hidden'); });
})();
</script>
<?php $this->end() ?>
