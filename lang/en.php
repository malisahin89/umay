<?php

declare(strict_types=1);

/**
 * English UI strings (view chrome + controller-supplied titles/meta).
 * Read via App\Support\Lang::t('key') / Lang::choice('key', $n).
 *
 * İngilizce arayüz metinleri (view kabuğu + controller başlık/meta metinleri).
 * App\Support\Lang::t('key') / Lang::choice('key', $n) ile okunur.
 *
 * Keys must stay identical across every lang/*.php file; only values differ.
 * "singular|plural" values are for Lang::choice; :placeholder tokens are filled
 * at call time. Values echoed raw (e.g. home.hero_title) may contain trusted HTML.
 *
 * Anahtarlar tüm lang/*.php dosyalarında aynı kalmalı; yalnızca değerler değişir.
 */
return [
    // Navigation / breadcrumb / menu
    'nav' => [
        'home' => 'Home',
        'posts' => 'Posts',
        'products' => 'Products',
        'categories' => 'Categories',
        'tags' => 'Tags',
        'menu' => 'Menu',
        'category_one' => 'Category',
        'tag_one' => 'Tag',
    ],

    // Common actions / labels
    'common' => [
        'all' => 'All',
        'read' => 'Read',
        'post' => 'Post',
        'product' => 'Product',
        'page' => 'Page',
        'prev' => 'Previous',
        'next' => 'Next',
        'close' => 'Close',
        'copy' => 'Copy',
    ],

    // Layout: footer
    'layout' => [
        'footer_tagline' => 'A clean, fast, modern interface for multilingual content.',
        'rights' => 'All rights reserved.',
    ],

    // Counts (Lang::choice) — singular|plural
    'count' => [
        'posts' => ':count post|:count posts',
        'categories' => ':count category|:count categories',
        'tags' => ':count tag|:count tags',
        'products' => ':count product|:count products',
        'results' => ':count result|:count results',
    ],

    // Meta descriptions (controller)
    'meta' => [
        'home_desc' => 'Multilingual blog — the latest posts, stories and content.',
        'blog_desc' => 'Explore all posts.',
        'products_desc' => 'Explore all our products.',
        'categories_desc' => 'Explore all categories.',
        'tags_desc' => 'Explore all tags.',
        'search_desc' => 'Search across posts and products.',
        'search_desc_q' => 'Search results for “:q”',
    ],

    // Search
    'search' => [
        'title' => 'Search',
        'title_q' => 'Search: :q',
        'placeholder_short' => 'Search…',
        'placeholder_posts' => 'Search posts…',
        'placeholder_all' => 'Search posts or products…',
        'button' => 'Search',
        'site_search' => 'Site search',
        'heading' => 'What are you looking for?',
        'popular_categories' => 'Popular categories',
        'browse_tags' => 'Browse all tags',
        'results_for' => ':count results for “:q”',
        'no_results' => 'No results found for “:q”',
        'no_results_hint' => 'Try a different keyword or browse the categories.',
    ],

    // Home page
    'home' => [
        'hero_title' => 'Ideas, stories<br>and <span class="text-gradient italic">content</span>.',
        'hero_subtitle' => 'Discover our multilingual content — clean, fast and a pleasure to read.',
        'featured_badge' => 'Featured',
        'featured_read' => 'Read article',
        'latest_eyebrow' => 'Latest',
        'posts_heading' => 'Posts',
        'see_all' => 'See all',
        'empty_posts' => 'No published posts yet.',
    ],

    // Blog listing
    'blog' => [
        'eyebrow' => 'Blog',
        'heading' => 'All Posts',
    ],

    // Archive (category/tag posts)
    'archive' => [
        'empty' => 'No posts in this section yet.',
    ],

    // Category / tag indexes
    'categories' => [
        'heading' => 'Categories',
        'empty' => 'No categories yet.',
    ],
    'tags' => [
        'heading' => 'Tags',
        'empty' => 'No tags yet.',
    ],

    // Products
    'products' => [
        'heading' => 'Products',
        'empty' => 'No products yet.',
        'go' => 'Go to product',
        'view' => 'View',
        'spec' => [
            'model' => 'Model',
            'type' => 'Type',
            'fuel_type' => 'Fuel Type',
            'heating_type' => 'Heating Type',
        ],
    ],

    // Post detail
    'post' => [
        'reading_time' => ':count min read',
        'views' => ':count views',
        'all_posts' => 'All posts',
        'related_eyebrow' => 'More',
        'related_heading' => 'Related Posts',
    ],

    // Accessibility / misc
    'aria' => [
        'slide' => 'Slide :n',
    ],
];
