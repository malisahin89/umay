<?php declare(strict_types=1); ?>
<?php $this->layout('layouts/front', ['title' => $title]) ?>
<?php
$cover = null;
if (is_string($ci = $post->cover_image ?? null) && $ci !== '') {
    $cover = (str_starts_with($ci, 'http') || str_starts_with($ci, '/')) ? $ci : '/storage/'.ltrim($ci, '/');
}
$plain = trim(strip_tags((string) $post->content));
$wordCount = $plain === '' ? 0 : count((array) preg_split('/\s+/u', $plain));
$readMin = max(1, (int) ceil($wordCount / 200));
$categories = $post->categories ?? [];
$tags = $post->tags ?? [];
$author = $post->user ?? null;
$views = is_numeric($vc = $post->view_count ?? 0) ? (int) $vc : 0;
?>

<!-- Reading progress -->
<div class="fixed inset-x-0 top-0 z-[60] h-1 bg-transparent">
    <div id="readProgress" class="h-full bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500" style="width:0%"></div>
</div>

<article id="articleContent" class="relative">
    <!-- aurora -->
    <div class="pointer-events-none absolute inset-x-0 -top-24 -z-10 flex justify-center">
        <div class="h-[420px] w-[900px] rounded-full bg-gradient-to-tr from-indigo-200/40 via-violet-200/30 to-fuchsia-200/30 blur-3xl"></div>
    </div>

    <header class="mx-auto max-w-3xl px-6 pt-16 text-center">
        <?php if (count($categories) > 0) { ?>
            <div class="mb-5 flex flex-wrap justify-center gap-2">
                <?php $ci = 0; foreach ($categories as $cat) { if ($ci++ >= 3) { break; } ?>
                    <a href="/<?= $this->e($locale) ?>/category/<?= $this->e((string) $cat->slug) ?>"
                       class="rounded-full bg-violet-100/70 px-3 py-1 text-xs font-semibold text-violet-700 ring-1 ring-inset ring-violet-200 transition hover:bg-violet-200/70">
                        <?= $this->e((string) $cat->name) ?>
                    </a>
                <?php } ?>
            </div>
        <?php } ?>

        <h1 class="font-display text-4xl font-semibold leading-[1.1] tracking-tight text-zinc-900 sm:text-5xl" style="text-wrap:balance">
            <?= $this->e((string) $post->title) ?>
        </h1>

        <div class="mt-6 flex flex-wrap items-center justify-center gap-x-3 gap-y-1 text-sm text-zinc-500">
            <?php if ($author !== null && is_string($author->name ?? null)) { ?>
                <span class="font-medium text-zinc-700"><?= $this->e((string) $author->name) ?></span>
                <span class="text-zinc-300">·</span>
            <?php } ?>
            <?php if ($post->published_at instanceof \DateTimeInterface) { ?>
                <span><?= $this->e($post->published_at->format('d.m.Y')) ?></span>
                <span class="text-zinc-300">·</span>
            <?php } ?>
            <span><?= (int) $readMin ?> dk okuma</span>
            <span class="text-zinc-300">·</span>
            <span><?= number_format($views) ?> görüntülenme</span>
        </div>
    </header>

    <?php if ($cover !== null) { ?>
        <div class="mx-auto mt-10 max-w-4xl px-6">
            <img src="<?= $this->e($cover) ?>" alt="<?= $this->e((string) $post->title) ?>"
                 class="aspect-[16/9] w-full rounded-3xl object-cover shadow-[0_20px_60px_-24px_rgba(0,0,0,0.35)] ring-1 ring-zinc-900/5">
        </div>
    <?php } ?>

    <?php if ($post->short_description) { ?>
        <p class="mx-auto mt-10 max-w-2xl px-6 text-center text-xl leading-relaxed text-zinc-500" style="text-wrap:balance">
            <?= $this->e((string) $post->short_description) ?>
        </p>
    <?php } ?>

    <!-- Rich-text HTML from the admin editor, rendered raw (admin-authored, trusted). -->
    <div class="article-body mx-auto mt-10 max-w-2xl px-6 text-[1.0625rem] leading-8 text-zinc-700">
        <?= (string) $post->content ?>
    </div>

    <!-- Tags -->
    <?php if (count($tags) > 0) { ?>
        <div class="mx-auto mt-12 flex max-w-2xl flex-wrap gap-2 px-6">
            <?php foreach ($tags as $tag) { ?>
                <a href="/<?= $this->e($locale) ?>/tag/<?= $this->e((string) $tag->slug) ?>"
                   class="rounded-full bg-zinc-900/[0.04] px-3 py-1 text-sm font-medium text-zinc-600 transition hover:bg-zinc-900/10 hover:text-zinc-900">
                    #<?= $this->e((string) $tag->name) ?>
                </a>
            <?php } ?>
        </div>
    <?php } ?>

    <!-- Share + back -->
    <div class="mx-auto mt-10 flex max-w-2xl flex-wrap items-center justify-between gap-4 border-t border-zinc-900/5 px-6 pt-8">
        <a href="/<?= $this->e($locale) ?>" class="inline-flex items-center gap-2 text-sm font-medium text-zinc-500 transition hover:text-violet-700">
            <i class="fa-solid fa-arrow-left text-xs"></i> Tüm yazılar
        </a>
        <div class="flex items-center gap-1.5" id="shareBar">
            <?php
            $btn = 'flex h-9 w-9 items-center justify-center rounded-full text-zinc-500 ring-1 ring-inset ring-zinc-900/10 transition hover:text-white';
            ?>
            <a data-share="x" href="#" target="_blank" rel="noopener" class="<?= $btn ?> hover:bg-zinc-900 hover:ring-zinc-900" aria-label="X"><i class="fa-brands fa-x-twitter text-sm"></i></a>
            <a data-share="facebook" href="#" target="_blank" rel="noopener" class="<?= $btn ?> hover:bg-[#1877f2] hover:ring-[#1877f2]" aria-label="Facebook"><i class="fa-brands fa-facebook-f text-sm"></i></a>
            <a data-share="linkedin" href="#" target="_blank" rel="noopener" class="<?= $btn ?> hover:bg-[#0a66c2] hover:ring-[#0a66c2]" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in text-sm"></i></a>
            <a data-share="whatsapp" href="#" target="_blank" rel="noopener" class="<?= $btn ?> hover:bg-[#25d366] hover:ring-[#25d366]" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp text-sm"></i></a>
            <button type="button" id="copyLinkBtn" class="<?= $btn ?> hover:bg-violet-600 hover:ring-violet-600" aria-label="Kopyala"><i class="fa-regular fa-copy text-sm"></i></button>
        </div>
    </div>
</article>

<!-- İlgili yazılar / Related posts -->
<?php if (count($related ?? []) > 0) { ?>
    <section class="mx-auto mt-20 max-w-6xl px-6">
        <div class="mb-8 flex items-end justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-violet-600">Devamı</p>
                <h2 class="font-display mt-1 text-3xl font-semibold tracking-tight text-zinc-900">İlgili Yazılar</h2>
            </div>
            <a href="/<?= $this->e($locale) ?>" class="hidden items-center gap-1.5 text-sm font-medium text-zinc-500 transition hover:text-violet-700 sm:flex">Tümü <i class="fa-solid fa-arrow-right text-xs"></i></a>
        </div>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($related as $rp) { ?>
                <?= $this->insert('front/partials/post-card', ['post' => $rp, 'locale' => $locale]) ?>
            <?php } ?>
        </div>
    </section>
<?php } ?>

<style nonce="<?= $this->nonce() ?>">
.article-body > * + * { margin-top: 1.25rem; }
.article-body h2{font-family:'Fraunces',serif;font-size:1.6rem;font-weight:600;letter-spacing:-.01em;margin-top:2.5rem;margin-bottom:.75rem;color:#18181b}
.article-body h3{font-family:'Fraunces',serif;font-size:1.3rem;font-weight:600;margin-top:2rem;margin-bottom:.5rem;color:#27272a}
.article-body p{color:#3f3f46}
.article-body a{color:#7c3aed;text-decoration:underline;text-decoration-color:#ddd6fe;text-underline-offset:3px;transition:text-decoration-color .2s}
.article-body a:hover{text-decoration-color:#7c3aed}
.article-body img{width:100%;border-radius:1rem;box-shadow:0 20px 50px -24px rgba(0,0,0,.35);margin:2rem 0}
.article-body blockquote{border-left:3px solid #8b5cf6;background:linear-gradient(90deg,rgba(139,92,246,.06),transparent);padding:.75rem 1.25rem;margin:1.75rem 0;font-style:italic;color:#52525b;border-radius:0 .5rem .5rem 0}
.article-body ul,.article-body ol{padding-left:1.4rem}
.article-body ul{list-style:disc}.article-body ol{list-style:decimal}
.article-body li{margin-bottom:.4rem;color:#3f3f46}
.article-body strong{font-weight:600;color:#18181b}
.article-body table{width:100%;border-collapse:collapse;font-size:.9rem;margin:1.75rem 0}
.article-body th{background:#f4f4f5;padding:.6rem 1rem;text-align:left;font-weight:600;color:#3f3f46}
.article-body td{padding:.6rem 1rem;border-top:1px solid #f4f4f5;color:#52525b}
</style>
<script nonce="<?= $this->nonce() ?>">
(function () {
    var url = encodeURIComponent(window.location.href), title = encodeURIComponent(document.title);
    var map = {
        x: 'https://twitter.com/share?url=' + url + '&text=' + title,
        facebook: 'https://www.facebook.com/sharer/sharer.php?u=' + url,
        linkedin: 'https://www.linkedin.com/shareArticle?mini=true&url=' + url + '&title=' + title,
        whatsapp: 'https://api.whatsapp.com/send?text=' + title + '%20' + url
    };
    document.querySelectorAll('#shareBar [data-share]').forEach(function (el) {
        var k = el.getAttribute('data-share'); if (map[k]) el.setAttribute('href', map[k]);
    });
    var c = document.getElementById('copyLinkBtn');
    if (c) c.addEventListener('click', function () {
        navigator.clipboard.writeText(window.location.href).then(function () {
            var i = c.querySelector('i'); if (i) { i.className = 'fa-solid fa-check text-sm'; c.classList.add('bg-emerald-500','text-white','ring-emerald-500');
                setTimeout(function(){ i.className='fa-regular fa-copy text-sm'; c.classList.remove('bg-emerald-500','text-white','ring-emerald-500'); },1500); }
        });
    });
    var art = document.getElementById('articleContent'), bar = document.getElementById('readProgress');
    if (art && bar) window.addEventListener('scroll', function () {
        var rect = art.getBoundingClientRect(), total = art.offsetHeight - window.innerHeight, scrolled = Math.max(0, -rect.top);
        bar.style.width = (total > 0 ? Math.min(100, (scrolled / total) * 100) : 0) + '%';
    });
})();
</script>
