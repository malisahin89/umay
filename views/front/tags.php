<?php declare(strict_types=1); ?>
<?php $this->layout('layouts/front', ['title' => $title]) ?>

<section class="relative">
    <div class="pointer-events-none absolute inset-x-0 -top-24 -z-10 flex justify-center">
        <div class="h-[440px] w-[900px] rounded-full bg-gradient-to-tr from-indigo-300/40 via-violet-300/30 to-fuchsia-300/30 blur-3xl"></div>
    </div>
    <div class="mx-auto max-w-4xl px-6 pb-4 pt-20 text-center sm:pt-24">
        <nav class="flex items-center justify-center gap-1.5 text-xs font-medium text-zinc-400">
            <a href="/<?= $this->e($locale) ?>" class="transition hover:text-violet-600">Ana Sayfa</a>
            <i class="fa-solid fa-angle-right text-[9px]"></i><span class="text-zinc-500">Etiketler</span>
        </nav>
        <h1 class="font-display mt-6 text-4xl font-semibold tracking-tight text-zinc-900 sm:text-5xl">Etiketler</h1>
        <p class="mt-3 text-sm text-zinc-500"><?= (int) count($tags) ?> etiket</p>
    </div>
</section>

<section class="mx-auto max-w-4xl px-6 py-12">
    <?php if (count($tags) === 0) { ?>
        <div class="rounded-3xl border border-dashed border-zinc-300 bg-white/50 py-20 text-center text-zinc-400">
            <i class="fa-solid fa-tags mb-3 text-4xl text-zinc-300"></i>
            <p class="text-sm font-medium">Henüz etiket yok.</p>
        </div>
    <?php } else { ?>
        <div class="flex flex-wrap justify-center gap-3">
            <?php foreach ($tags as $tag) { ?>
                <?php $count = (int) ($tag->posts_count ?? 0); ?>
                <a href="/<?= $this->e($locale) ?>/tag/<?= $this->e((string) $tag->slug) ?>"
                   class="group inline-flex items-center gap-2 rounded-full bg-white px-5 py-2.5 text-sm font-medium text-zinc-700 shadow-sm ring-1 ring-zinc-900/5 transition hover:-translate-y-0.5 hover:text-violet-700 hover:ring-violet-300">
                    <span class="text-violet-400 transition group-hover:text-violet-600">#</span><?= $this->e((string) $tag->name) ?>
                    <span class="rounded-full bg-zinc-900/5 px-2 py-0.5 text-xs font-semibold text-zinc-500 transition group-hover:bg-violet-100 group-hover:text-violet-700"><?= $count ?></span>
                </a>
            <?php } ?>
        </div>
    <?php } ?>
</section>
