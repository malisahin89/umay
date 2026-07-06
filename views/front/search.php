<?php declare(strict_types=1); ?>
<?php $this->layout('layouts/front', ['title' => $title]) ?>
<?php
$nPosts = count($posts);
$nProducts = count($products);
$total = $nPosts + $nProducts;
?>

<!-- HERO -->
<section class="relative">
    <div class="pointer-events-none absolute inset-x-0 -top-24 -z-10 flex justify-center">
        <div class="h-[440px] w-[900px] rounded-full bg-gradient-to-tr from-indigo-300/40 via-violet-300/30 to-fuchsia-300/30 blur-3xl"></div>
    </div>
    <div class="mx-auto max-w-2xl px-6 pb-6 pt-20 text-center sm:pt-24">
        <span class="inline-flex items-center gap-2 rounded-full border border-zinc-900/10 bg-white/70 px-3.5 py-1.5 text-xs font-medium text-zinc-600 shadow-sm backdrop-blur">
            <i class="fa-solid fa-magnifying-glass text-[10px] text-violet-500"></i> Site içi arama
        </span>
        <h1 class="font-display mt-5 text-4xl font-semibold tracking-tight text-zinc-900 sm:text-5xl">Ne aramıştınız?</h1>
        <form action="/<?= $this->e($locale) ?>/search" method="get" class="js-search mx-auto mt-8 flex items-center gap-2 rounded-full border border-zinc-900/10 bg-white p-2 pl-5 shadow-lg shadow-zinc-900/5 focus-within:border-violet-300">
            <i class="fa-solid fa-magnifying-glass text-zinc-400"></i>
            <input type="text" name="q" value="<?= $this->e($q) ?>" autofocus placeholder="Yazı veya ürün ara…" class="w-full bg-transparent text-base text-zinc-800 placeholder-zinc-400 outline-none">
            <button class="shrink-0 rounded-full bg-gradient-to-tr from-indigo-500 via-violet-500 to-fuchsia-500 px-6 py-2.5 text-sm font-semibold text-white transition hover:brightness-110">Ara</button>
        </form>
        <?php if ($q !== '') { ?>
            <p class="mt-4 text-sm text-zinc-500"><span class="font-semibold text-zinc-800">“<?= $this->e($q) ?>”</span> için <span class="font-semibold text-zinc-800"><?= (int) $total ?></span> sonuç</p>
        <?php } ?>
    </div>
</section>

<div class="mx-auto max-w-6xl px-6 py-10">
    <?php if ($q === '') { ?>
        <!-- Discovery -->
        <?php if (count($categories) > 0) { ?>
            <div class="text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-violet-600">Popüler kategoriler</p>
                <div class="mt-5 flex flex-wrap justify-center gap-2.5">
                    <?php foreach ($categories as $cat) { ?>
                        <a href="/<?= $this->e($locale) ?>/category/<?= $this->e((string) $cat->slug) ?>"
                           class="rounded-full border border-zinc-900/10 bg-white px-4 py-2 text-sm font-medium text-zinc-600 shadow-sm transition hover:-translate-y-0.5 hover:border-violet-300 hover:text-violet-700"><?= $this->e((string) $cat->name) ?></a>
                    <?php } ?>
                </div>
                <a href="/<?= $this->e($locale) ?>/tags" class="mt-6 inline-flex items-center gap-1.5 text-sm font-medium text-zinc-500 transition hover:text-violet-700">Tüm etiketlere göz at <i class="fa-solid fa-arrow-right text-xs"></i></a>
            </div>
        <?php } ?>

    <?php } elseif ($total === 0) { ?>
        <!-- No results -->
        <div class="mx-auto max-w-lg rounded-3xl border border-dashed border-zinc-300 bg-white/50 px-6 py-16 text-center">
            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-zinc-100 text-2xl text-zinc-300"><i class="fa-solid fa-magnifying-glass"></i></span>
            <p class="mt-5 font-semibold text-zinc-700">“<?= $this->e($q) ?>” için sonuç bulunamadı</p>
            <p class="mt-1 text-sm text-zinc-500">Farklı bir kelime deneyin ya da kategorilere göz atın.</p>
            <?php if (count($categories) > 0) { ?>
                <div class="mt-6 flex flex-wrap justify-center gap-2">
                    <?php foreach ($categories as $cat) { ?>
                        <a href="/<?= $this->e($locale) ?>/category/<?= $this->e((string) $cat->slug) ?>" class="rounded-full bg-zinc-900/5 px-3 py-1.5 text-xs font-medium text-zinc-600 transition hover:bg-violet-100 hover:text-violet-700"><?= $this->e((string) $cat->name) ?></a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

    <?php } else { ?>
        <!-- Posts -->
        <?php if ($nPosts > 0) { ?>
            <section class="mb-14">
                <div class="mb-6 flex items-end justify-between">
                    <h2 class="font-display text-2xl font-semibold tracking-tight text-zinc-900">Yazılar</h2>
                    <span class="rounded-full bg-zinc-900/5 px-3 py-1 text-sm font-medium text-zinc-500"><?= (int) $nPosts ?></span>
                </div>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <?php foreach ($posts as $post) { ?>
                        <?= $this->insert('front/partials/post-card', ['post' => $post, 'locale' => $locale]) ?>
                    <?php } ?>
                </div>
            </section>
        <?php } ?>

        <!-- Products -->
        <?php if ($nProducts > 0) { ?>
            <section>
                <div class="mb-6 flex items-end justify-between">
                    <h2 class="font-display text-2xl font-semibold tracking-tight text-zinc-900">Ürünler</h2>
                    <span class="rounded-full bg-zinc-900/5 px-3 py-1 text-sm font-medium text-zinc-500"><?= (int) $nProducts ?></span>
                </div>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <?php foreach ($products as $product) { ?>
                        <?= $this->insert('front/partials/product-card', ['product' => $product, 'locale' => $locale]) ?>
                    <?php } ?>
                </div>
            </section>
        <?php } ?>
    <?php } ?>
</div>
