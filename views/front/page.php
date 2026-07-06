<?php declare(strict_types=1); ?>
<?php $this->layout('layouts/front', ['title' => $title]) ?>

<article class="relative">
    <div class="pointer-events-none absolute inset-x-0 -top-24 -z-10 flex justify-center">
        <div class="h-[380px] w-[800px] rounded-full bg-gradient-to-tr from-indigo-200/40 via-violet-200/30 to-fuchsia-200/30 blur-3xl"></div>
    </div>

    <header class="mx-auto max-w-3xl px-6 pt-20 text-center">
        <h1 class="font-display text-4xl font-semibold leading-[1.1] tracking-tight text-zinc-900 sm:text-5xl" style="text-wrap:balance">
            <?= $this->e((string) $page->title) ?>
        </h1>
    </header>

    <div class="article-body mx-auto mt-10 max-w-2xl px-6 text-[1.0625rem] leading-8 text-zinc-700">
        <?= (string) $page->content ?>
    </div>
</article>

<style nonce="<?= $this->nonce() ?>">
.article-body > * + * { margin-top: 1.25rem; }
.article-body h2{font-family:'Fraunces',serif;font-size:1.6rem;font-weight:600;letter-spacing:-.01em;margin-top:2.5rem;margin-bottom:.75rem;color:#18181b}
.article-body h3{font-family:'Fraunces',serif;font-size:1.3rem;font-weight:600;margin-top:2rem;margin-bottom:.5rem;color:#27272a}
.article-body p{color:#3f3f46}
.article-body a{color:#7c3aed;text-decoration:underline;text-decoration-color:#ddd6fe;text-underline-offset:3px}
.article-body a:hover{text-decoration-color:#7c3aed}
.article-body img{width:100%;border-radius:1rem;box-shadow:0 20px 50px -24px rgba(0,0,0,.35);margin:2rem 0}
.article-body blockquote{border-left:3px solid #8b5cf6;background:linear-gradient(90deg,rgba(139,92,246,.06),transparent);padding:.75rem 1.25rem;margin:1.75rem 0;font-style:italic;color:#52525b;border-radius:0 .5rem .5rem 0}
.article-body ul,.article-body ol{padding-left:1.4rem}
.article-body ul{list-style:disc}.article-body ol{list-style:decimal}
.article-body li{margin-bottom:.4rem;color:#3f3f46}
.article-body strong{font-weight:600;color:#18181b}
</style>
