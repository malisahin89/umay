<?php declare(strict_types=1); ?>
<?php $this->layout('layouts/front', ['title' => $title]) ?>
<?php
$asset = static fn ($p): ?string => (is_string($p) && $p !== '') ? ((str_starts_with($p, 'http') || str_starts_with($p, '/')) ? $p : '/storage/'.ltrim($p, '/')) : null;
$cover = $asset($product->cover_image ?? null);
$gallery = is_array($g = $product->gallery_images ?? null) ? array_values(array_filter($g, 'is_string')) : [];
$images = [];
if ($cover !== null) {
    $images[] = $cover;
}
foreach ($gallery as $gi) {
    $images[] = $asset($gi);
}
$images = array_values(array_filter($images));
$main = $images[0] ?? null;

$brand = is_string($product->brand ?? null) ? $product->brand : '';
$price = $product->price ?? null;
$categories = $product->categories ?? [];
$tags = $product->tags ?? [];

// Spec rows from language-neutral columns.
$specs = [];
foreach (['model', 'type', 'fuel_type', 'heating_type'] as $col) {
    $v = $product->getAttribute($col);
    if (is_string($v) && $v !== '') {
        $specs[\App\Support\Lang::t('products.spec.'.$col)] = $v;
    }
}
$productUrl = is_string($pu = $product->product_url ?? null) && $pu !== '' ? $pu : null;
?>

<section class="mx-auto max-w-6xl px-6 pt-10">
    <nav class="flex items-center gap-1.5 text-xs font-medium text-zinc-400">
        <a href="/<?= $this->e($locale) ?>" class="transition hover:text-violet-600"><?= $this->e(\App\Support\Lang::t('nav.home')) ?></a>
        <i class="fa-solid fa-angle-right text-[9px]"></i>
        <a href="<?= $this->e(\App\Support\RouteSlugs::to($locale, 'products')) ?>" class="transition hover:text-violet-600"><?= $this->e(\App\Support\Lang::t('nav.products')) ?></a>
        <i class="fa-solid fa-angle-right text-[9px]"></i>
        <span class="truncate text-zinc-500"><?= $this->e((string) $product->title) ?></span>
    </nav>

    <div class="mt-8 grid gap-10 lg:grid-cols-2">
        <!-- Gallery -->
        <div>
            <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-zinc-900/5">
                <?php if ($main !== null) { ?>
                    <img id="pdMain" src="<?= $this->e($main) ?>" alt="<?= $this->e((string) $product->title) ?>" class="aspect-square w-full object-cover">
                <?php } else { ?>
                    <div class="flex aspect-square w-full items-center justify-center bg-gradient-to-br from-indigo-50 via-violet-50 to-fuchsia-50"><i class="fa-solid fa-box text-6xl text-violet-200"></i></div>
                <?php } ?>
            </div>
            <?php if (count($images) > 1) { ?>
                <div class="mt-4 grid grid-cols-5 gap-3">
                    <?php foreach ($images as $im) { ?>
                        <button type="button" class="pd-thumb overflow-hidden rounded-xl ring-1 ring-zinc-900/5 transition hover:ring-violet-300" data-src="<?= $this->e((string) $im) ?>">
                            <img src="<?= $this->e((string) $im) ?>" alt="" class="aspect-square w-full object-cover">
                        </button>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

        <!-- Info -->
        <div class="lg:pt-2">
            <?php if ($brand !== '') { ?>
                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-violet-600"><?= $this->e($brand) ?></p>
            <?php } ?>
            <h1 class="font-display mt-2 text-3xl font-semibold leading-tight tracking-tight text-zinc-900 sm:text-4xl"><?= $this->e((string) $product->title) ?></h1>

            <?php if (is_numeric($price) && (float) $price > 0) { ?>
                <p class="mt-4 text-2xl font-bold text-zinc-900"><?= $this->e(number_format((float) $price, 2, ',', '.')) ?> ₺</p>
            <?php } ?>

            <?php if ($product->short_description) { ?>
                <p class="mt-4 leading-relaxed text-zinc-500"><?= $this->e((string) $product->short_description) ?></p>
            <?php } ?>

            <?php
            // Merge the neutral spec columns with the translatable key-value specifications.
            // Neutral spec kolonlarını, çevrilebilir key-value teknik özelliklerle birleştir.
            $extra = $product->specifications ?? null;
            if (is_array($extra)) {
                foreach ($extra as $k => $v) {
                    if (is_scalar($v) && (string) $v !== '') {
                        $specs[(string) $k] = (string) $v;
                    }
                }
            }
            ?>
            <?php if ($specs !== []) { ?>
                <dl class="mt-6 divide-y divide-zinc-100 overflow-hidden rounded-2xl ring-1 ring-zinc-900/5">
                    <?php foreach ($specs as $label => $v) { ?>
                        <div class="flex justify-between gap-4 bg-white px-4 py-3 text-sm">
                            <dt class="font-medium text-zinc-500"><?= $this->e((string) $label) ?></dt>
                            <dd class="font-semibold text-zinc-900"><?= $this->e((string) $v) ?></dd>
                        </div>
                    <?php } ?>
                </dl>
            <?php } ?>

            <?php if (count($categories) > 0 || count($tags) > 0) { ?>
                <div class="mt-6 flex flex-wrap gap-2">
                    <?php foreach ($categories as $cat) { ?>
                        <a href="/<?= $this->e($locale) ?>/<?= $this->e(\App\Support\RouteSlugs::seg('category', $locale)) ?>/<?= $this->e((string) $cat->slug) ?>" class="rounded-full bg-violet-100/70 px-3 py-1 text-xs font-semibold text-violet-700 ring-1 ring-inset ring-violet-200 transition hover:bg-violet-200/70"><?= $this->e((string) $cat->name) ?></a>
                    <?php } ?>
                    <?php foreach ($tags as $tag) { ?>
                        <a href="/<?= $this->e($locale) ?>/<?= $this->e(\App\Support\RouteSlugs::seg('tag', $locale)) ?>/<?= $this->e((string) $tag->slug) ?>" class="rounded-full bg-zinc-900/[0.04] px-3 py-1 text-xs font-medium text-zinc-600 transition hover:bg-zinc-900/10">#<?= $this->e((string) $tag->name) ?></a>
                    <?php } ?>
                </div>
            <?php } ?>

            <?php if ($productUrl !== null) { ?>
                <a href="<?= $this->e($productUrl) ?>" target="_blank" rel="noopener" class="mt-8 inline-flex items-center gap-2 rounded-full bg-gradient-to-tr from-indigo-500 via-violet-500 to-fuchsia-500 px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-violet-500/20 transition hover:brightness-110">
                    <?= $this->e(\App\Support\Lang::t('products.go')) ?> <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                </a>
            <?php } ?>
        </div>
    </div>

    <?php if ($product->content) { ?>
        <div class="article-body mx-auto mt-14 max-w-3xl text-[1.0625rem] leading-8 text-zinc-700">
            <?= (string) $product->content ?>
        </div>
    <?php } ?>
</section>

<style nonce="<?= $this->nonce() ?>">
.article-body > * + * { margin-top: 1.25rem; }
.article-body h2{font-family:'Fraunces',serif;font-size:1.6rem;font-weight:600;letter-spacing:-.01em;margin-top:2.5rem;margin-bottom:.75rem;color:#18181b}
.article-body h3{font-family:'Fraunces',serif;font-size:1.3rem;font-weight:600;margin-top:2rem;margin-bottom:.5rem;color:#27272a}
.article-body p{color:#3f3f46}
.article-body a{color:#7c3aed;text-decoration:underline;text-decoration-color:#ddd6fe;text-underline-offset:3px}
.article-body img{width:100%;border-radius:1rem;box-shadow:0 20px 50px -24px rgba(0,0,0,.35);margin:2rem 0}
.article-body ul,.article-body ol{padding-left:1.4rem}.article-body ul{list-style:disc}.article-body ol{list-style:decimal}
.article-body li{margin-bottom:.4rem;color:#3f3f46}.article-body strong{font-weight:600;color:#18181b}
.article-body table{width:100%;border-collapse:collapse;font-size:.9rem;margin:1.75rem 0}
.article-body th{background:#f4f4f5;padding:.6rem 1rem;text-align:left;font-weight:600;color:#3f3f46}
.article-body td{padding:.6rem 1rem;border-top:1px solid #f4f4f5;color:#52525b}
</style>
<?php if (count($images) > 1) { ?>
<script nonce="<?= $this->nonce() ?>">
(function () {
    var main = document.getElementById('pdMain');
    document.querySelectorAll('.pd-thumb').forEach(function (t) {
        t.addEventListener('click', function () {
            if (main) main.src = t.getAttribute('data-src');
            document.querySelectorAll('.pd-thumb').forEach(function (x) { x.classList.remove('ring-violet-400'); });
            t.classList.add('ring-violet-400');
        });
    });
})();
</script>
<?php } ?>
