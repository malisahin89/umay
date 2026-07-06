<?php declare(strict_types=1); ?>
<?php $this->layout('layouts/front', ['title' => $title]) ?>

<section class="relative">
    <div class="pointer-events-none absolute inset-x-0 -top-24 -z-10 flex justify-center">
        <div class="h-[440px] w-[900px] rounded-full bg-gradient-to-tr from-indigo-300/40 via-violet-300/30 to-fuchsia-300/30 blur-3xl"></div>
    </div>
    <div class="mx-auto max-w-4xl px-6 pb-4 pt-20 text-center sm:pt-24">
        <nav class="flex items-center justify-center gap-1.5 text-xs font-medium text-zinc-400">
            <a href="/<?= $this->e($locale) ?>" class="transition hover:text-violet-600"><?= $this->e(\App\Support\Lang::t('nav.home')) ?></a>
            <i class="fa-solid fa-angle-right text-[9px]"></i><span class="text-zinc-500"><?= $this->e(\App\Support\Lang::t('nav.categories')) ?></span>
        </nav>
        <h1 class="font-display mt-6 text-4xl font-semibold tracking-tight text-zinc-900 sm:text-5xl"><?= $this->e(\App\Support\Lang::t('categories.heading')) ?></h1>
        <p class="mt-3 text-sm text-zinc-500"><?= $this->e(\App\Support\Lang::choice('count.categories', (int) count($categories))) ?></p>
    </div>
</section>

<section class="mx-auto max-w-6xl px-6 py-12">
    <?php if (count($categories) === 0) { ?>
        <div class="rounded-3xl border border-dashed border-zinc-300 bg-white/50 py-20 text-center text-zinc-400">
            <i class="fa-solid fa-layer-group mb-3 text-4xl text-zinc-300"></i>
            <p class="text-sm font-medium"><?= $this->e(\App\Support\Lang::t('categories.empty')) ?></p>
        </div>
    <?php } else { ?>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($categories as $cat) { ?>
                <?php $count = (int) ($cat->posts_count ?? 0); ?>
                <a href="/<?= $this->e($locale) ?>/<?= $this->e(\App\Support\RouteSlugs::seg('category', $locale)) ?>/<?= $this->e((string) $cat->slug) ?>"
                   class="group flex items-center justify-between rounded-3xl bg-white p-6 shadow-[0_1px_3px_rgba(0,0,0,0.04),0_16px_40px_-24px_rgba(0,0,0,0.2)] ring-1 ring-zinc-900/5 transition hover:-translate-y-1 hover:ring-violet-300/60">
                    <div class="flex items-center gap-4">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-500 via-violet-500 to-fuchsia-500 text-white shadow-lg shadow-violet-500/20">
                            <i class="fa-solid fa-layer-group"></i>
                        </span>
                        <div>
                            <div class="font-display text-lg font-semibold tracking-tight text-zinc-900 transition group-hover:text-violet-700"><?= $this->e((string) $cat->name) ?></div>
                            <div class="text-xs font-medium text-zinc-400"><?= $this->e(\App\Support\Lang::choice('count.posts', $count)) ?></div>
                        </div>
                    </div>
                    <i class="fa-solid fa-arrow-right text-sm text-zinc-300 transition group-hover:translate-x-1 group-hover:text-violet-500"></i>
                </a>
            <?php } ?>
        </div>
    <?php } ?>
</section>
