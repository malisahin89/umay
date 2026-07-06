<?php declare(strict_types=1); ?>
<?php $this->layout('layouts/front', ['title' => $title]) ?>
<?php
$assetUrl = static function ($p): ?string {
    if (! is_string($p) || $p === '') {
        return null;
    }

    return (str_starts_with($p, 'http') || str_starts_with($p, '/')) ? $p : '/storage/'.ltrim($p, '/');
};
$coverOf = static fn ($obj): ?string => $assetUrl($obj->cover_image ?? null);
$onPageOne = ($page ?? 1) <= 1;
?>

<?php if (count($slides) > 0) { ?>
    <!-- HERO SLIDER -->
    <section class="mx-auto max-w-6xl px-6 pt-6">
        <div id="heroSlider" class="group relative aspect-[4/5] overflow-hidden rounded-3xl shadow-[0_30px_80px_-30px_rgba(0,0,0,0.5)] ring-1 ring-black/5 sm:aspect-[16/9] lg:aspect-[16/8]">
            <?php foreach ($slides as $i => $slide) { ?>
                <?php
                $media = $assetUrl($slide->media_file ?? null);
                $right = ($slide->text_position ?? 'left') === 'right';
                ?>
                <div class="slider-slide absolute inset-0 transition-opacity duration-700 ease-out <?= $i > 0 ? 'pointer-events-none opacity-0' : '' ?>" data-index="<?= (int) $i ?>">
                    <?php if (($slide->type ?? 'image') === 'video' && $media !== null) { ?>
                        <video autoplay muted loop playsinline class="absolute inset-0 h-full w-full object-cover"><source src="<?= $this->e($media) ?>" type="video/mp4"></video>
                    <?php } elseif ($media !== null) { ?>
                        <img src="<?= $this->e($media) ?>" alt="<?= $this->e((string) $slide->title) ?>" class="absolute inset-0 h-full w-full object-cover">
                    <?php } else { ?>
                        <div class="absolute inset-0 bg-gradient-to-tr from-indigo-600 via-violet-600 to-fuchsia-600"></div>
                    <?php } ?>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/25 to-transparent"></div>
                    <div class="absolute inset-0 flex items-end p-8 sm:p-12 md:p-16 <?= $right ? 'justify-end text-right' : '' ?>">
                        <div class="max-w-xl">
                            <?php if ($slide->label) { ?>
                                <p class="mb-3 text-xs font-semibold uppercase tracking-[0.25em] text-white/70"><?= $this->e((string) $slide->label) ?></p>
                            <?php } ?>
                            <h2 class="font-display text-3xl font-semibold leading-tight tracking-tight text-white drop-shadow sm:text-4xl md:text-5xl"><?= $this->e((string) $slide->title) ?></h2>
                            <?php if ($slide->subtitle) { ?>
                                <p class="mt-3 max-w-md text-sm leading-relaxed text-white/85 <?= $right ? 'ml-auto' : '' ?>"><?= $this->e((string) $slide->subtitle) ?></p>
                            <?php } ?>
                            <?php if ($slide->button_text && $slide->button_url) { ?>
                                <a href="<?= $this->e((string) $slide->button_url) ?>" class="mt-6 inline-flex rounded-full bg-white/95 px-6 py-2.5 text-sm font-semibold text-zinc-900 shadow-lg backdrop-blur transition hover:bg-white"><?= $this->e((string) $slide->button_text) ?></a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php if (count($slides) > 1) { ?>
                <button type="button" id="sliderPrev" aria-label="Önceki" class="absolute left-4 top-1/2 z-20 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full border border-white/30 bg-black/20 text-white opacity-0 backdrop-blur transition hover:bg-white hover:text-black group-hover:opacity-100"><i class="fa-solid fa-angle-left"></i></button>
                <button type="button" id="sliderNext" aria-label="Sonraki" class="absolute right-4 top-1/2 z-20 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full border border-white/30 bg-black/20 text-white opacity-0 backdrop-blur transition hover:bg-white hover:text-black group-hover:opacity-100"><i class="fa-solid fa-angle-right"></i></button>
                <div id="sliderDots" class="absolute bottom-5 left-1/2 z-20 flex -translate-x-1/2 gap-2">
                    <?php foreach ($slides as $i => $slide) { ?>
                        <button type="button" data-dot="<?= (int) $i ?>" class="slider-dot h-1.5 rounded-full bg-white/50 transition-all <?= $i === 0 ? 'w-6 bg-white' : 'w-3' ?>" aria-label="Slayt <?= (int) $i + 1 ?>"></button>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </section>
<?php } else { ?>
    <!-- HERO -->
    <section class="relative">
        <div class="pointer-events-none absolute inset-x-0 -top-24 -z-10 flex justify-center">
            <div class="h-[520px] w-[1100px] rounded-full bg-gradient-to-tr from-indigo-300/40 via-violet-300/30 to-fuchsia-300/30 blur-3xl"></div>
        </div>
        <div class="mx-auto max-w-4xl px-6 pb-6 pt-20 text-center sm:pt-28">
            <span class="inline-flex items-center gap-2 rounded-full border border-zinc-900/10 bg-white/70 px-3.5 py-1.5 text-xs font-medium text-zinc-600 shadow-sm backdrop-blur">
                <span class="h-1.5 w-1.5 rounded-full bg-gradient-to-tr from-indigo-500 to-fuchsia-500"></span><?= $this->e(strtoupper($locale)) ?> · Blog
            </span>
            <h1 class="font-display mt-6 text-4xl font-semibold leading-[1.05] tracking-tight text-zinc-900 sm:text-6xl md:text-7xl" style="text-wrap:balance">
                Fikirler, hikâyeler<br>ve <span class="text-gradient italic">içerik</span>.
            </h1>
            <p class="mx-auto mt-6 max-w-xl text-lg leading-relaxed text-zinc-500" style="text-wrap:balance">Çok dilli içeriklerimizi keşfedin — sade, hızlı ve okunması keyifli.</p>
            <form action="/<?= $this->e($locale) ?>/search" method="get" class="js-search mx-auto mt-8 flex max-w-md items-center gap-2 rounded-full border border-zinc-900/10 bg-white p-1.5 pl-4 shadow-lg shadow-zinc-900/5 focus-within:border-violet-300">
                <i class="fa-solid fa-magnifying-glass text-zinc-400"></i>
                <input type="text" name="q" placeholder="Yazılarda ara…" class="w-full bg-transparent text-sm text-zinc-800 placeholder-zinc-400 outline-none">
                <button class="rounded-full bg-gradient-to-tr from-indigo-500 via-violet-500 to-fuchsia-500 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:brightness-110">Ara</button>
            </form>
        </div>
    </section>
<?php } ?>

<!-- Category filter chips -->
<?php if (count($categories) > 0) { ?>
    <div class="mx-auto mt-12 max-w-6xl px-6">
        <div class="flex flex-wrap justify-center gap-2">
            <?php foreach ($categories as $cat) { ?>
                <a href="/<?= $this->e($locale) ?>/category/<?= $this->e((string) $cat->slug) ?>"
                   class="rounded-full border border-zinc-900/10 bg-white px-4 py-1.5 text-sm font-medium text-zinc-600 shadow-sm transition hover:border-violet-300 hover:text-violet-700">
                    <?= $this->e((string) $cat->name) ?>
                </a>
            <?php } ?>
        </div>
    </div>
<?php } ?>

<!-- Featured (page 1 only) -->
<?php if ($onPageOne && ($featured ?? null) !== null) { ?>
    <?php $fc = $coverOf($featured); ?>
    <section class="mx-auto mt-14 max-w-6xl px-6">
        <a href="/<?= $this->e($locale) ?>/posts/<?= $this->e((string) $featured->slug) ?>"
           class="group grid overflow-hidden rounded-3xl bg-white shadow-[0_1px_3px_rgba(0,0,0,0.04),0_24px_60px_-28px_rgba(0,0,0,0.3)] ring-1 ring-zinc-900/5 transition hover:-translate-y-1 hover:ring-violet-300/60 md:grid-cols-2">
            <div class="relative aspect-[16/10] overflow-hidden md:aspect-auto">
                <?php if ($fc !== null) { ?>
                    <img src="<?= $this->e($fc) ?>" alt="<?= $this->e((string) $featured->title) ?>" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">
                <?php } else { ?>
                    <div class="flex h-full min-h-[240px] w-full items-center justify-center bg-gradient-to-br from-indigo-100 via-violet-100 to-fuchsia-100"><i class="fa-regular fa-newspaper text-5xl text-violet-200"></i></div>
                <?php } ?>
                <span class="absolute left-4 top-4 rounded-full bg-gradient-to-tr from-indigo-500 via-violet-500 to-fuchsia-500 px-3 py-1 text-xs font-semibold text-white shadow">★ Öne Çıkan</span>
            </div>
            <div class="flex flex-col justify-center p-8 sm:p-10">
                <?php if ($featured->published_at instanceof \DateTimeInterface) { ?>
                    <time class="text-xs font-medium uppercase tracking-wide text-zinc-400"><?= $this->e($featured->published_at->format('d.m.Y')) ?></time>
                <?php } ?>
                <h2 class="font-display mt-2 text-3xl font-semibold leading-tight tracking-tight text-zinc-900 transition group-hover:text-violet-700"><?= $this->e((string) $featured->title) ?></h2>
                <?php if ($featured->short_description) { ?>
                    <p class="mt-3 text-zinc-500 line-clamp-3"><?= $this->e((string) $featured->short_description) ?></p>
                <?php } ?>
                <span class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-violet-600">Yazıyı oku <i class="fa-solid fa-arrow-right text-xs transition group-hover:translate-x-1"></i></span>
            </div>
        </a>
    </section>
<?php } ?>

<!-- POSTS -->
<section id="yazilar" class="mx-auto max-w-6xl px-6 py-16">
    <div class="mb-8 flex items-end justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-violet-600">En güncel</p>
            <h2 class="font-display mt-1 text-3xl font-semibold tracking-tight text-zinc-900">Yazılar</h2>
        </div>
        <a href="/<?= $this->e($locale) ?>/blog" class="inline-flex items-center gap-1.5 text-sm font-semibold text-violet-600 transition hover:gap-2">
            Tümünü gör <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
    </div>

    <?php if (count($posts) === 0) { ?>
        <div class="rounded-3xl border border-dashed border-zinc-300 bg-white/50 py-24 text-center text-zinc-400">
            <i class="fa-regular fa-newspaper mb-3 text-4xl text-zinc-300"></i>
            <p class="text-sm font-medium">Henüz yayınlanmış yazı yok.</p>
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

<?php if (count($slides) > 1) { ?>
<script nonce="<?= $this->nonce() ?>">
(function () {
    var slides = Array.prototype.slice.call(document.querySelectorAll('#heroSlider .slider-slide'));
    var dots = Array.prototype.slice.call(document.querySelectorAll('#heroSlider .slider-dot'));
    if (slides.length < 2) return;
    var cur = 0, timer;
    function show(n) {
        cur = (n + slides.length) % slides.length;
        slides.forEach(function (s, i) {
            var on = i === cur;
            s.classList.toggle('opacity-0', !on);
            s.classList.toggle('pointer-events-none', !on);
        });
        dots.forEach(function (d, i) {
            var on = i === cur;
            d.classList.toggle('w-6', on); d.classList.toggle('bg-white', on);
            d.classList.toggle('w-3', !on);
        });
    }
    function move(step) { show(cur + step); reset(); }
    function reset() { clearInterval(timer); timer = setInterval(function () { show(cur + 1); }, 6000); }
    var p = document.getElementById('sliderPrev'), n = document.getElementById('sliderNext');
    if (p) p.addEventListener('click', function () { move(-1); });
    if (n) n.addEventListener('click', function () { move(1); });
    dots.forEach(function (d, i) { d.addEventListener('click', function () { show(i); reset(); }); });
    reset();
})();
</script>
<?php } ?>
