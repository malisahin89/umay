<?php declare(strict_types=1); ?>
<?php
/**
 * One post card — modern: rounded-3xl, soft shadow, glassy category chip, serif title,
 * hover lift with accent ring. Shared by the home and search grids.
 * Tek yazı kartı — modern. Ana sayfa ve arama grid'i paylaşır.
 *
 * @var \App\Models\Post $post
 * @var string $locale
 */
$cover = null;
if (is_string($ci = $post->cover_image ?? null) && $ci !== '') {
    $cover = (str_starts_with($ci, 'http') || str_starts_with($ci, '/')) ? $ci : '/storage/'.ltrim($ci, '/');
}
$cats = $post->categories ?? [];
$firstCat = count($cats) > 0 ? $cats[0] : null;
?>
<a href="/<?= $this->e($locale) ?>/<?= $this->e(\App\Support\RouteSlugs::seg('posts', $locale)) ?>/<?= $this->e((string) $post->slug) ?>"
   class="group flex flex-col overflow-hidden rounded-3xl bg-white shadow-[0_1px_3px_rgba(0,0,0,0.04),0_16px_40px_-24px_rgba(0,0,0,0.25)] ring-1 ring-zinc-900/5 transition duration-300 hover:-translate-y-1.5 hover:shadow-[0_1px_3px_rgba(0,0,0,0.04),0_28px_50px_-24px_rgba(99,102,241,0.4)] hover:ring-violet-300/60">
    <div class="relative aspect-[16/10] overflow-hidden">
        <?php if ($cover !== null) { ?>
            <img src="<?= $this->e($cover) ?>" alt="<?= $this->e((string) $post->title) ?>"
                 class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.04]" loading="lazy" decoding="async">
        <?php } else { ?>
            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-50 via-violet-50 to-fuchsia-50">
                <i class="fa-regular fa-newspaper text-4xl text-violet-200"></i>
            </div>
        <?php } ?>
        <?php if ($firstCat !== null) { ?>
            <span class="absolute left-3 top-3 rounded-full bg-white/80 px-2.5 py-1 text-[11px] font-semibold text-zinc-700 shadow-sm ring-1 ring-white/60 backdrop-blur-md">
                <?= $this->e((string) $firstCat->name) ?>
            </span>
        <?php } ?>
    </div>
    <div class="flex flex-1 flex-col p-6">
        <h3 class="font-display text-xl font-semibold leading-snug tracking-tight text-zinc-900 line-clamp-2 transition group-hover:text-violet-700">
            <?= $this->e((string) $post->title) ?>
        </h3>
        <?php if ($post->short_description) { ?>
            <p class="mt-2 flex-1 text-sm leading-relaxed text-zinc-500 line-clamp-2"><?= $this->e((string) $post->short_description) ?></p>
        <?php } ?>
        <div class="mt-5 flex items-center gap-2 text-xs font-medium text-zinc-400">
            <?php if ($post->published_at instanceof \DateTimeInterface) { ?>
                <span><?= $this->e($post->published_at->format('d.m.Y')) ?></span>
                <span class="text-zinc-300">·</span>
            <?php } ?>
            <span class="inline-flex items-center gap-1 text-violet-600 transition group-hover:gap-2"><?= $this->e(\App\Support\Lang::t('read')) ?> <i class="fa-solid fa-arrow-right text-[10px]"></i></span>
        </div>
    </div>
</a>
