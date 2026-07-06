<?php declare(strict_types=1); ?>
<?php $this->layout('layouts/front', ['title' => $title]) ?>

<!-- HERO -->
<section class="relative">
    <div class="pointer-events-none absolute inset-x-0 -top-24 -z-10 flex justify-center">
        <div class="h-[440px] w-[900px] rounded-full bg-gradient-to-tr from-indigo-300/40 via-violet-300/30 to-fuchsia-300/30 blur-3xl"></div>
    </div>
    <div class="mx-auto max-w-4xl px-6 pb-4 pt-20 text-center sm:pt-24">
        <nav class="flex items-center justify-center gap-1.5 text-xs font-medium text-zinc-400">
            <a href="/<?= $this->e($locale) ?>" class="transition hover:text-violet-600">Ana Sayfa</a>
            <i class="fa-solid fa-angle-right text-[9px]"></i>
            <span class="text-zinc-500"><?= $this->e($kind) ?></span>
        </nav>
        <p class="mt-6 text-xs font-semibold uppercase tracking-[0.2em] text-violet-600"><?= $this->e($kind) ?></p>
        <h1 class="font-display mt-1 text-4xl font-semibold tracking-tight text-zinc-900 sm:text-5xl"><?= $this->e($name) ?></h1>
        <p class="mt-3 text-sm text-zinc-500"><?= (int) $total ?> yazı</p>
    </div>
</section>

<section class="mx-auto max-w-6xl px-6 py-12">
    <?php if (count($posts) === 0) { ?>
        <div class="rounded-3xl border border-dashed border-zinc-300 bg-white/50 py-20 text-center text-zinc-400">
            <i class="fa-regular fa-folder-open mb-3 text-4xl text-zinc-300"></i>
            <p class="text-sm font-medium">Bu <?= $this->e(mb_strtolower($kind)) ?>de henüz yazı yok.</p>
        </div>
    <?php } else { ?>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($posts as $post) { ?>
                <?= $this->insert('front/partials/post-card', ['post' => $post, 'locale' => $locale]) ?>
            <?php } ?>
        </div>
        <?= $this->insert('front/partials/pagination', ['page' => $page, 'lastPage' => $lastPage, 'baseUrl' => $baseUrl]) ?>
    <?php } ?>
</section>
