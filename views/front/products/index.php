<?php declare(strict_types=1); ?>
<?php $this->layout('layouts/front', ['title' => $title]) ?>

<section class="relative">
    <div class="pointer-events-none absolute inset-x-0 -top-24 -z-10 flex justify-center">
        <div class="h-[440px] w-[900px] rounded-full bg-gradient-to-tr from-indigo-300/40 via-violet-300/30 to-fuchsia-300/30 blur-3xl"></div>
    </div>
    <div class="mx-auto max-w-4xl px-6 pb-4 pt-20 text-center sm:pt-24">
        <nav class="flex items-center justify-center gap-1.5 text-xs font-medium text-zinc-400">
            <a href="/<?= $this->e($locale) ?>" class="transition hover:text-violet-600"><?= $this->e(\App\Support\Lang::t('nav.home')) ?></a>
            <i class="fa-solid fa-angle-right text-[9px]"></i><span class="text-zinc-500"><?= $this->e(\App\Support\Lang::t('nav.products')) ?></span>
        </nav>
        <h1 class="font-display mt-6 text-4xl font-semibold tracking-tight text-zinc-900 sm:text-5xl"><?= $this->e(\App\Support\Lang::t('products.heading')) ?></h1>
        <p class="mt-3 text-sm text-zinc-500"><?= $this->e(\App\Support\Lang::choice('count.products', (int) $total)) ?></p>
    </div>
</section>

<section class="mx-auto max-w-6xl px-6 py-12">
    <?php if (count($products) === 0) { ?>
        <div class="rounded-3xl border border-dashed border-zinc-300 bg-white/50 py-20 text-center text-zinc-400">
            <i class="fa-solid fa-box-open mb-3 text-4xl text-zinc-300"></i>
            <p class="text-sm font-medium"><?= $this->e(\App\Support\Lang::t('products.empty')) ?></p>
        </div>
    <?php } else { ?>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <?php foreach ($products as $product) { ?>
                <?= $this->insert('front/partials/product-card', ['product' => $product, 'locale' => $locale]) ?>
            <?php } ?>
        </div>
        <?= $this->insert('front/partials/pagination', ['page' => $page, 'lastPage' => $lastPage, 'baseUrl' => $baseUrl]) ?>
    <?php } ?>
</section>
