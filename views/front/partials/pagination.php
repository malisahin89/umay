<?php declare(strict_types=1); ?>
<?php
/**
 * Offset pagination control.
 * @var int $page  @var int $lastPage  @var string $baseUrl
 */
if (($lastPage ?? 1) <= 1) {
    return;
}
// Clean path pagination: page 1 → base, page N → base/{page}/N. The "page" segment
// is localized per the locale in the base URL's first segment (/es/… → seite/sayfa/…).
// Temiz path sayfalama: 1. sayfa → base, N. sayfa → base/{page}/N. "page" segmenti,
// base URL'in ilk parçasındaki dile göre localize edilir.
$base = rtrim($baseUrl, '/');
$baseLocale = explode('/', trim($baseUrl, '/'))[0] ?? '';
$pageSeg = \App\Support\RouteSlugs::seg('page', $baseLocale);
$url = static fn (int $n): string => $n <= 1 ? ($base === '' ? '/' : $base) : $base.'/'.$pageSeg.'/'.$n;

// Windowed page range around the current page.
$from = max(1, $page - 2);
$to = min($lastPage, $page + 2);

$base = 'inline-flex h-9 min-w-9 items-center justify-center rounded-xl px-3 text-sm font-medium transition';
$idle = 'text-zinc-600 ring-1 ring-inset ring-zinc-200 hover:bg-white hover:ring-zinc-300';
$active = 'bg-gradient-to-tr from-indigo-500 via-violet-500 to-fuchsia-500 text-white shadow';
$disabled = 'text-zinc-300 ring-1 ring-inset ring-zinc-100';
?>
<nav class="mt-14 flex flex-wrap items-center justify-center gap-1.5">
    <?php if ($page > 1) { ?>
        <a href="<?= $this->e($url($page - 1)) ?>" class="<?= $base ?> <?= $idle ?>" aria-label="<?= $this->e(\App\Support\Lang::t('common.prev')) ?>"><i class="fa-solid fa-angle-left text-xs"></i></a>
    <?php } else { ?>
        <span class="<?= $base ?> <?= $disabled ?>"><i class="fa-solid fa-angle-left text-xs"></i></span>
    <?php } ?>

    <?php if ($from > 1) { ?>
        <a href="<?= $this->e($url(1)) ?>" class="<?= $base ?> <?= $idle ?>">1</a>
        <?php if ($from > 2) { ?><span class="px-1 text-zinc-400">…</span><?php } ?>
    <?php } ?>

    <?php for ($i = $from; $i <= $to; $i++) { ?>
        <?php if ($i === $page) { ?>
            <span class="<?= $base ?> <?= $active ?>"><?= (int) $i ?></span>
        <?php } else { ?>
            <a href="<?= $this->e($url($i)) ?>" class="<?= $base ?> <?= $idle ?>"><?= (int) $i ?></a>
        <?php } ?>
    <?php } ?>

    <?php if ($to < $lastPage) { ?>
        <?php if ($to < $lastPage - 1) { ?><span class="px-1 text-zinc-400">…</span><?php } ?>
        <a href="<?= $this->e($url($lastPage)) ?>" class="<?= $base ?> <?= $idle ?>"><?= (int) $lastPage ?></a>
    <?php } ?>

    <?php if ($page < $lastPage) { ?>
        <a href="<?= $this->e($url($page + 1)) ?>" class="<?= $base ?> <?= $idle ?>" aria-label="<?= $this->e(\App\Support\Lang::t('common.next')) ?>"><i class="fa-solid fa-angle-right text-xs"></i></a>
    <?php } else { ?>
        <span class="<?= $base ?> <?= $disabled ?>"><i class="fa-solid fa-angle-right text-xs"></i></span>
    <?php } ?>
</nav>
