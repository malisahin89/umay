<?php declare(strict_types=1); ?>
<?php
/**
 * Public front-end layout — a modern editorial aesthetic: warm off-white canvas, an
 * aurora gradient wash, a floating glassy nav, serif display headings (Fraunces) over
 * Inter body, and an indigo→violet→fuchsia accent. Nested on layouts/base.
 *
 * Herkese açık ön yüz layout'u — modern editoryal estetik: sıcak off-white kanvas,
 * aurora gradient, yüzen cam nav, serif display başlıklar (Fraunces) + Inter gövde,
 * indigo→violet→fuchsia vurgu. layouts/base üzerine.
 */
$this->layout('layouts/base', ['title' => $title ?? null]);

$frontLangs = \App\Models\Language::active();
$frontLocale = \App\Support\Locale::get();
$brand = config('app.name', 'Umay');
$brand = is_string($brand) ? $brand : 'Umay';

// Active popups for this request. target_routes empty → every page; otherwise the
// current path must match (or contain) one of the configured route strings.
// Bu istek için aktif popup'lar. target_routes boş → her sayfa; değilse mevcut yol
// yapılandırılan route dizelerinden birini eşlemeli (ya da içermeli).
$currentPath = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
$activePopups = \App\Models\Popup::activeNow()->filter(function ($p) use ($currentPath): bool {
    $routes = $p->target_routes;
    if (! is_array($routes) || $routes === []) {
        return true;
    }
    foreach ($routes as $r) {
        $r = is_string($r) ? trim($r) : '';
        if ($r !== '' && ($r === $currentPath || str_contains($currentPath, $r))) {
            return true;
        }
    }

    return false;
});
?>
<?php $this->start('body') ?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&display=swap">

<div class="relative min-h-screen overflow-x-hidden bg-[#f7f7f5] text-zinc-900 antialiased">

    <!-- Floating glassy nav -->
    <?php
    $navItems = [
        [\App\Support\RouteSlugs::to($frontLocale, ''), \App\Support\Lang::t('nav.home')],
        [\App\Support\RouteSlugs::to($frontLocale, 'blog'), \App\Support\Lang::t('nav.posts')],
        [\App\Support\RouteSlugs::to($frontLocale, 'products'), \App\Support\Lang::t('nav.products')],
        [\App\Support\RouteSlugs::to($frontLocale, 'categories'), \App\Support\Lang::t('nav.categories')],
        [\App\Support\RouteSlugs::to($frontLocale, 'tags'), \App\Support\Lang::t('nav.tags')],
    ];
    ?>
    <header class="sticky top-0 z-50 px-4 pt-4">
        <div class="mx-auto max-w-6xl">
            <div class="flex items-center justify-between gap-3 rounded-2xl border border-white/60 bg-white/70 px-4 py-2.5 shadow-[0_1px_3px_rgba(0,0,0,0.04),0_8px_30px_-12px_rgba(0,0,0,0.15)] backdrop-blur-xl sm:px-5">
                <a href="/<?= $this->e($frontLocale) ?>" class="flex shrink-0 items-center gap-2">
                    <span class="h-6 w-6 rounded-lg bg-gradient-to-tr from-indigo-500 via-violet-500 to-fuchsia-500 shadow-sm"></span>
                    <span class="text-[15px] font-bold tracking-tight text-zinc-900"><?= $this->e($brand) ?></span>
                </a>

                <!-- Desktop links -->
                <nav class="hidden items-center gap-0.5 text-sm lg:flex">
                    <?php foreach ($navItems as $it) { ?>
                        <a href="<?= $this->e($it[0]) ?>" class="rounded-full px-3 py-1.5 font-medium text-zinc-600 transition hover:bg-zinc-900/5 hover:text-zinc-900"><?= $this->e($it[1]) ?></a>
                    <?php } ?>
                </nav>

                <!-- Right cluster -->
                <div class="flex items-center gap-1.5">
                    <form action="<?= $this->e(\App\Support\RouteSlugs::to($frontLocale, 'search')) ?>" method="get"
                          class="js-search hidden items-center gap-2 rounded-full bg-zinc-900/[0.04] px-3 py-1.5 ring-1 ring-inset ring-zinc-900/5 transition focus-within:bg-white focus-within:ring-violet-300 lg:flex">
                        <i class="fa-solid fa-magnifying-glass text-xs text-zinc-400"></i>
                        <input type="text" name="q" placeholder="<?= $this->e(\App\Support\Lang::t('search.placeholder_short')) ?>" class="w-24 bg-transparent text-sm text-zinc-800 placeholder-zinc-400 outline-none transition-all focus:w-32">
                    </form>

                    <?php if (count($frontLangs) > 1) { ?>
                        <div class="relative" id="langMenu">
                            <?php $curFlag = \App\Support\Locale::flag($frontLocale); ?>
                            <button type="button" id="langToggle"
                                    class="flex items-center gap-1.5 rounded-full px-2.5 py-1.5 text-sm font-medium text-zinc-600 transition hover:bg-zinc-900/5 hover:text-zinc-900">
                                <img src="https://flagcdn.com/24x18/<?= $this->e($curFlag) ?>.png" width="18" height="13" alt="" class="rounded-sm ring-1 ring-black/5"><span class="hidden sm:inline"><?= $this->e(strtoupper($frontLocale)) ?></span>
                                <i class="fa-solid fa-chevron-down text-[8px] text-zinc-400"></i>
                            </button>
                            <div id="langPanel" class="absolute right-0 mt-2 hidden min-w-[160px] overflow-hidden rounded-2xl border border-white/60 bg-white/90 p-1 shadow-xl backdrop-blur-xl">
                                <?php foreach ($frontLangs as $lang) { ?>
                                    <?php $ls = (string) $lang->slug; ?>
                                    <?php $fc = \App\Support\Locale::flag($ls, is_string($lang->flag) ? $lang->flag : null); ?>
                                    <?php $lurl = (isset($langUrls) && is_array($langUrls) && isset($langUrls[$ls])) ? $langUrls[$ls] : '/'.$ls; ?>
                                    <a href="<?= $this->e($lurl) ?>" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-sm transition hover:bg-zinc-900/5 <?= $ls === $frontLocale ? 'font-semibold text-zinc-900' : 'text-zinc-600' ?>">
                                        <img src="https://flagcdn.com/24x18/<?= $this->e($fc) ?>.png" srcset="https://flagcdn.com/48x36/<?= $this->e($fc) ?>.png 2x" width="20" height="15" alt="" class="rounded-sm shadow-sm ring-1 ring-black/5">
                                        <span><?= $this->e((string) $lang->name) ?></span>
                                        <?php if ($ls === $frontLocale) { ?><i class="fa-solid fa-check ml-auto text-xs text-violet-500"></i><?php } ?>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- Hamburger (mobile/tablet) -->
                    <button type="button" id="menuToggle" class="flex h-9 w-9 items-center justify-center rounded-full text-zinc-600 transition hover:bg-zinc-900/5 lg:hidden" aria-label="<?= $this->e(\App\Support\Lang::t('nav.menu')) ?>">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div id="mobileMenu" class="mt-2 hidden rounded-2xl border border-white/60 bg-white/90 p-3 shadow-xl backdrop-blur-xl lg:hidden">
                <form action="<?= $this->e(\App\Support\RouteSlugs::to($frontLocale, 'search')) ?>" method="get" class="js-search mb-2 flex items-center gap-2 rounded-xl bg-zinc-900/[0.04] px-3 py-2 ring-1 ring-inset ring-zinc-900/5 focus-within:bg-white focus-within:ring-violet-300">
                    <i class="fa-solid fa-magnifying-glass text-xs text-zinc-400"></i>
                    <input type="text" name="q" placeholder="<?= $this->e(\App\Support\Lang::t('search.placeholder_short')) ?>" class="w-full bg-transparent text-sm text-zinc-800 placeholder-zinc-400 outline-none">
                </form>
                <nav class="flex flex-col">
                    <?php foreach ($navItems as $it) { ?>
                        <a href="<?= $this->e($it[0]) ?>" class="rounded-xl px-3 py-2.5 text-sm font-medium text-zinc-700 transition hover:bg-zinc-900/5"><?= $this->e($it[1]) ?></a>
                    <?php } ?>
                </nav>
            </div>
        </div>
    </header>

    <main class="relative"><?= $this->section('content') ?></main>

    <!-- Footer -->
    <footer class="relative mt-24 overflow-hidden border-t border-zinc-900/5 bg-white">
        <div class="pointer-events-none absolute -bottom-32 left-1/2 h-[400px] w-[700px] -translate-x-1/2 rounded-full bg-gradient-to-tr from-indigo-200/40 via-violet-200/30 to-fuchsia-200/30 blur-3xl"></div>
        <div class="relative mx-auto max-w-6xl px-6 py-16">
            <div class="flex flex-col items-start justify-between gap-8 sm:flex-row sm:items-end">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="h-7 w-7 rounded-lg bg-gradient-to-tr from-indigo-500 via-violet-500 to-fuchsia-500"></span>
                        <span class="text-xl font-bold tracking-tight"><?= $this->e($brand) ?></span>
                    </div>
                    <p class="mt-3 max-w-xs text-sm text-zinc-500"><?= $this->e(\App\Support\Lang::t('layout.footer_tagline')) ?></p>
                </div>
                <nav class="flex flex-wrap gap-x-6 gap-y-2 text-sm">
                    <a href="<?= $this->e(\App\Support\RouteSlugs::to($frontLocale, '')) ?>" class="text-zinc-500 transition hover:text-zinc-900"><?= $this->e(\App\Support\Lang::t('nav.home')) ?></a>
                    <a href="<?= $this->e(\App\Support\RouteSlugs::to($frontLocale, 'products')) ?>" class="text-zinc-500 transition hover:text-zinc-900"><?= $this->e(\App\Support\Lang::t('nav.products')) ?></a>
                    <a href="<?= $this->e(\App\Support\RouteSlugs::to($frontLocale, 'categories')) ?>" class="text-zinc-500 transition hover:text-zinc-900"><?= $this->e(\App\Support\Lang::t('nav.categories')) ?></a>
                    <a href="<?= $this->e(\App\Support\RouteSlugs::to($frontLocale, 'tags')) ?>" class="text-zinc-500 transition hover:text-zinc-900"><?= $this->e(\App\Support\Lang::t('nav.tags')) ?></a>
                </nav>
            </div>
            <div class="mt-12 border-t border-zinc-900/5 pt-6 text-xs text-zinc-400">© <?= (int) date('Y') ?> <?= $this->e($brand) ?> — <?= $this->e(\App\Support\Lang::t('layout.rights')) ?></div>
        </div>
    </footer>

    <?= $this->insert('front/partials/popup', ['popups' => $activePopups]) ?>
</div>

<style nonce="<?= $this->nonce() ?>">
.font-display{font-family:'Fraunces',Georgia,serif}
.line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.line-clamp-3{display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
.text-gradient{background:linear-gradient(135deg,#6366f1,#8b5cf6,#d946ef);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent}
</style>
<script nonce="<?= $this->nonce() ?>">
(function () {
    var t = document.getElementById('langToggle'), p = document.getElementById('langPanel');
    if (t && p) {
        t.addEventListener('click', function (e) { e.stopPropagation(); p.classList.toggle('hidden'); });
    }
    document.addEventListener('click', function (e) {
        var m = document.getElementById('langMenu');
        if (p && m && !m.contains(e.target)) p.classList.add('hidden');
    });
    var mt = document.getElementById('menuToggle'), mm = document.getElementById('mobileMenu');
    if (mt && mm) {
        mt.addEventListener('click', function () {
            mm.classList.toggle('hidden');
            var icon = mt.querySelector('i');
            if (icon) icon.className = mm.classList.contains('hidden') ? 'fa-solid fa-bars' : 'fa-solid fa-xmark';
        });
    }
    // Clean path-based search: /{locale}/search/{query} instead of ?q=…
    // Temiz path-tabanlı arama: ?q=… yerine /{locale}/search/{query}
    document.querySelectorAll('form.js-search').forEach(function (f) {
        f.addEventListener('submit', function (e) {
            e.preventDefault();
            var inp = f.querySelector('input[name="q"]');
            var q = inp ? inp.value.trim() : '';
            var base = f.getAttribute('action') || '/';
            window.location.href = q ? base + '/' + encodeURIComponent(q) : base;
        });
    });
})();
</script>
<?php $this->end() ?>
