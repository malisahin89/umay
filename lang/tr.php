<?php

declare(strict_types=1);

/**
 * Turkish UI strings (view chrome + controller-supplied titles/meta).
 * Read via App\Support\Lang::t('key') / Lang::choice('key', $n).
 *
 * Türkçe arayüz metinleri (view kabuğu + controller başlık/meta metinleri).
 * App\Support\Lang::t('key') / Lang::choice('key', $n) ile okunur.
 *
 * Keys must stay identical across every lang/*.php file; only values differ.
 * "singular|plural" values are for Lang::choice; :placeholder tokens are filled
 * at call time. Values echoed raw (e.g. home.hero_title) may contain trusted HTML.
 *
 * Anahtarlar tüm lang/*.php dosyalarında aynı kalmalı; yalnızca değerler değişir.
 * "tekil|çoğul" değerleri Lang::choice içindir; :placeholder belirteçleri çağrı
 * anında doldurulur. Ham basılan değerler (örn. home.hero_title) güvenilir HTML içerebilir.
 */
return [
    // Navigasyon / breadcrumb / menü
    'nav' => [
        'home' => 'Ana Sayfa',
        'posts' => 'Yazılar',
        'products' => 'Ürünler',
        'categories' => 'Kategoriler',
        'tags' => 'Etiketler',
        'menu' => 'Menü',
        'category_one' => 'Kategori',
        'tag_one' => 'Etiket',
    ],

    // Ortak eylemler / etiketler
    'common' => [
        'all' => 'Tümü',
        'read' => 'Oku',
        'post' => 'Yazı',
        'product' => 'Ürün',
        'page' => 'Sayfa',
        'prev' => 'Önceki',
        'next' => 'Sonraki',
        'close' => 'Kapat',
        'copy' => 'Kopyala',
    ],

    // Layout: footer
    'layout' => [
        'footer_tagline' => 'Çok dilli içerik için sade, hızlı ve modern bir arayüz.',
        'rights' => 'Tüm hakları saklıdır.',
    ],

    // Sayımlar (Lang::choice) — tekil|çoğul
    'count' => [
        'posts' => ':count yazı|:count yazı',
        'categories' => ':count kategori|:count kategori',
        'tags' => ':count etiket|:count etiket',
        'products' => ':count ürün|:count ürün',
        'results' => ':count sonuç|:count sonuç',
    ],

    // Meta açıklamalar (controller)
    'meta' => [
        'home_desc' => 'Çok dilli blog — en güncel yazılar, hikâyeler ve içerik.',
        'blog_desc' => 'Tüm yazıları keşfedin.',
        'products_desc' => 'Tüm ürünlerimizi keşfedin.',
        'categories_desc' => 'Tüm kategorileri keşfedin.',
        'tags_desc' => 'Tüm etiketleri keşfedin.',
        'search_desc' => 'Yazılarda ve ürünlerde arama.',
        'search_desc_q' => '“:q” için arama sonuçları',
    ],

    // Arama
    'search' => [
        'title' => 'Arama',
        'title_q' => 'Arama: :q',
        'placeholder_short' => 'Ara…',
        'placeholder_posts' => 'Yazılarda ara…',
        'placeholder_all' => 'Yazı veya ürün ara…',
        'button' => 'Ara',
        'site_search' => 'Site içi arama',
        'heading' => 'Ne aramıştınız?',
        'popular_categories' => 'Popüler kategoriler',
        'browse_tags' => 'Tüm etiketlere göz at',
        'results_for' => '“:q” için :count sonuç',
        'no_results' => '“:q” için sonuç bulunamadı',
        'no_results_hint' => 'Farklı bir kelime deneyin ya da kategorilere göz atın.',
    ],

    // Ana sayfa
    'home' => [
        'hero_title' => 'Fikirler, hikâyeler<br>ve <span class="text-gradient italic">içerik</span>.',
        'hero_subtitle' => 'Çok dilli içeriklerimizi keşfedin — sade, hızlı ve okunması keyifli.',
        'featured_badge' => 'Öne Çıkan',
        'featured_read' => 'Yazıyı oku',
        'latest_eyebrow' => 'En güncel',
        'posts_heading' => 'Yazılar',
        'see_all' => 'Tümünü gör',
        'empty_posts' => 'Henüz yayınlanmış yazı yok.',
    ],

    // Blog listesi
    'blog' => [
        'eyebrow' => 'Blog',
        'heading' => 'Tüm Yazılar',
    ],

    // Arşiv (kategori/etiket yazıları)
    'archive' => [
        'empty' => 'Bu bölümde henüz yazı yok.',
    ],

    // Kategoriler / etiketler dizini
    'categories' => [
        'heading' => 'Kategoriler',
        'empty' => 'Henüz kategori yok.',
    ],
    'tags' => [
        'heading' => 'Etiketler',
        'empty' => 'Henüz etiket yok.',
    ],

    // Ürünler
    'products' => [
        'heading' => 'Ürünler',
        'empty' => 'Henüz ürün yok.',
        'go' => 'Ürüne Git',
        'view' => 'İncele',
        'spec' => [
            'model' => 'Model',
            'type' => 'Tip',
            'fuel_type' => 'Yakıt Tipi',
            'heating_type' => 'Isıtma Tipi',
        ],
    ],

    // Yazı detay
    'post' => [
        'reading_time' => ':count dk okuma',
        'views' => ':count görüntülenme',
        'all_posts' => 'Tüm yazılar',
        'related_eyebrow' => 'Devamı',
        'related_heading' => 'İlgili Yazılar',
    ],

    // Erişilebilirlik / muhtelif
    'aria' => [
        'slide' => 'Slayt :n',
    ],
];
