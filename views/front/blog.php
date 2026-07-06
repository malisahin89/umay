<?php declare(strict_types=1); ?>
<?php $this->layout('layouts/front', ['title' => $title]) ?>

<section class="relative">
    <div class="pointer-events-none absolute inset-x-0 -top-24 -z-10 flex justify-center">
        <div class="h-[440px] w-[900px] rounded-full bg-gradient-to-tr from-indigo-300/40 via-violet-300/30 to-fuchsia-300/30 blur-3xl"></div>
    </div>
    <div class="mx-auto max-w-4xl px-6 pb-4 pt-20 text-center sm:pt-24">
        <nav class="flex items-center justify-center gap-1.5 text-xs font-medium text-zinc-400">
            <a href="/<?= $this->e($locale) ?>" class="transition hover:text-violet-600"><?= $this->e(\App\Support\Lang::t('nav.home')) ?></a>
            <i class="fa-solid fa-angle-right text-[9px]"></i><span class="text-zinc-500"><?= $this->e(\App\Support\Lang::t('nav.posts')) ?></span>
        </nav>
        <p class="mt-6 text-xs font-semibold uppercase tracking-[0.2em] text-violet-600"><?= $this->e(\App\Support\Lang::t('blog.eyebrow')) ?></p>
        <h1 class="font-display mt-1 text-4xl font-semibold tracking-tight text-zinc-900 sm:text-5xl"><?= $this->e(\App\Support\Lang::t('blog.heading')) ?></h1>
        <p class="mt-3 text-sm text-zinc-500"><?= $this->e(\App\Support\Lang::choice('count.posts', (int) $total)) ?></p>
    </div>

    <?php if (count($categories) > 0) { ?>
        <div class="mx-auto max-w-4xl px-6">
            <div class="flex flex-wrap justify-center gap-2">
                <a href="<?= $this->e(\App\Support\RouteSlugs::to($locale, 'blog')) ?>" class="rounded-full bg-zinc-900 px-4 py-1.5 text-sm font-semibold text-white"><?= $this->e(\App\Support\Lang::t('common.all')) ?></a>
                <?php foreach ($categories as $cat) { ?>
                    <a href="/<?= $this->e($locale) ?>/<?= $this->e(\App\Support\RouteSlugs::seg('category', $locale)) ?>/<?= $this->e((string) $cat->slug) ?>"
                       class="rounded-full border border-zinc-900/10 bg-white px-4 py-1.5 text-sm font-medium text-zinc-600 shadow-sm transition hover:border-violet-300 hover:text-violet-700"><?= $this->e((string) $cat->name) ?></a>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</section>

<section class="mx-auto max-w-6xl px-6 py-12">
    <?php if (count($posts) === 0) { ?>
        <div class="rounded-3xl border border-dashed border-zinc-300 bg-white/50 py-20 text-center text-zinc-400">
            <i class="fa-regular fa-newspaper mb-3 text-4xl text-zinc-300"></i>
            <p class="text-sm font-medium"><?= $this->e(\App\Support\Lang::t('home.empty_posts')) ?></p>
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
