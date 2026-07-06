<?php declare(strict_types=1); ?>
<?php
/**
 * One product card — modern: cover, brand, title, price, hover lift.
 * @var \App\Models\Product $product
 * @var string $locale
 */
$cover = null;
if (is_string($ci = $product->cover_image ?? null) && $ci !== '') {
    $cover = (str_starts_with($ci, 'http') || str_starts_with($ci, '/')) ? $ci : '/storage/'.ltrim($ci, '/');
}
$brand = $product->brand ?? null;
$price = $product->price ?? null;
?>
<a href="/<?= $this->e($locale) ?>/<?= $this->e(\App\Support\RouteSlugs::seg('products', $locale)) ?>/<?= $this->e((string) $product->slug) ?>"
   class="group flex flex-col overflow-hidden rounded-3xl bg-white shadow-[0_1px_3px_rgba(0,0,0,0.04),0_16px_40px_-24px_rgba(0,0,0,0.25)] ring-1 ring-zinc-900/5 transition duration-300 hover:-translate-y-1.5 hover:shadow-[0_1px_3px_rgba(0,0,0,0.04),0_28px_50px_-24px_rgba(99,102,241,0.4)] hover:ring-violet-300/60">
    <div class="relative aspect-square overflow-hidden bg-zinc-50">
        <?php if ($cover !== null) { ?>
            <img src="<?= $this->e($cover) ?>" alt="<?= $this->e((string) $product->title) ?>" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.04]" loading="lazy">
        <?php } else { ?>
            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-50 via-violet-50 to-fuchsia-50"><i class="fa-solid fa-box text-4xl text-violet-200"></i></div>
        <?php } ?>
    </div>
    <div class="flex flex-1 flex-col p-6">
        <?php if (is_string($brand) && $brand !== '') { ?>
            <p class="text-xs font-semibold uppercase tracking-wide text-violet-600"><?= $this->e($brand) ?></p>
        <?php } ?>
        <h3 class="font-display mt-1 text-lg font-semibold leading-snug tracking-tight text-zinc-900 line-clamp-2 transition group-hover:text-violet-700"><?= $this->e((string) $product->title) ?></h3>
        <?php if ($product->short_description) { ?>
            <p class="mt-2 flex-1 text-sm leading-relaxed text-zinc-500 line-clamp-2"><?= $this->e((string) $product->short_description) ?></p>
        <?php } ?>
        <div class="mt-5 flex items-center justify-between">
            <?php if (is_numeric($price) && (float) $price > 0) { ?>
                <span class="text-base font-bold text-zinc-900"><?= $this->e(number_format((float) $price, 2, ',', '.')) ?> ₺</span>
            <?php } else { ?>
                <span></span>
            <?php } ?>
            <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-violet-600 transition group-hover:gap-2"><?= $this->e(\App\Support\Lang::t('products.view')) ?> <i class="fa-solid fa-arrow-right text-[10px]"></i></span>
        </div>
    </div>
</a>
