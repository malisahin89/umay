<?php

declare(strict_types=1);

/**
 * German UI strings (view chrome + controller-supplied titles/meta).
 * Read via App\Support\Lang::t('key') / Lang::choice('key', $n).
 *
 * Keys must stay identical across every lang/*.php file; only values differ.
 */
return [
    'nav' => [
        'home' => 'Startseite',
        'posts' => 'Beiträge',
        'products' => 'Produkte',
        'categories' => 'Kategorien',
        'tags' => 'Schlagwörter',
        'menu' => 'Menü',
        'category_one' => 'Kategorie',
        'tag_one' => 'Schlagwort',
    ],

    'common' => [
        'all' => 'Alle',
        'read' => 'Lesen',
        'post' => 'Beitrag',
        'product' => 'Produkt',
        'page' => 'Seite',
        'prev' => 'Zurück',
        'next' => 'Weiter',
        'close' => 'Schließen',
        'copy' => 'Kopieren',
    ],

    'layout' => [
        'footer_tagline' => 'Eine schlichte, schnelle und moderne Oberfläche für mehrsprachige Inhalte.',
        'rights' => 'Alle Rechte vorbehalten.',
    ],

    'count' => [
        'posts' => ':count Beitrag|:count Beiträge',
        'categories' => ':count Kategorie|:count Kategorien',
        'tags' => ':count Schlagwort|:count Schlagwörter',
        'products' => ':count Produkt|:count Produkte',
        'results' => ':count Ergebnis|:count Ergebnisse',
    ],

    'meta' => [
        'home_desc' => 'Mehrsprachiger Blog — die neuesten Beiträge, Geschichten und Inhalte.',
        'blog_desc' => 'Entdecken Sie alle Beiträge.',
        'products_desc' => 'Entdecken Sie alle unsere Produkte.',
        'categories_desc' => 'Entdecken Sie alle Kategorien.',
        'tags_desc' => 'Entdecken Sie alle Schlagwörter.',
        'search_desc' => 'Suche in Beiträgen und Produkten.',
        'search_desc_q' => 'Suchergebnisse für “:q”',
    ],

    'search' => [
        'title' => 'Suche',
        'title_q' => 'Suche: :q',
        'placeholder_short' => 'Suchen…',
        'placeholder_posts' => 'Beiträge durchsuchen…',
        'placeholder_all' => 'Beiträge oder Produkte suchen…',
        'button' => 'Suchen',
        'site_search' => 'Suche auf der Seite',
        'heading' => 'Wonach suchen Sie?',
        'popular_categories' => 'Beliebte Kategorien',
        'browse_tags' => 'Alle Schlagwörter durchsuchen',
        'results_for' => ':count Ergebnisse für “:q”',
        'no_results' => 'Keine Ergebnisse für “:q” gefunden',
        'no_results_hint' => 'Versuchen Sie ein anderes Stichwort oder stöbern Sie in den Kategorien.',
    ],

    'home' => [
        'hero_title' => 'Ideen, Geschichten<br>und <span class="text-gradient italic">Inhalte</span>.',
        'hero_subtitle' => 'Entdecken Sie unsere mehrsprachigen Inhalte — schlicht, schnell und angenehm zu lesen.',
        'featured_badge' => 'Empfohlen',
        'featured_read' => 'Beitrag lesen',
        'latest_eyebrow' => 'Aktuell',
        'posts_heading' => 'Beiträge',
        'see_all' => 'Alle ansehen',
        'empty_posts' => 'Noch keine veröffentlichten Beiträge.',
    ],

    'blog' => [
        'eyebrow' => 'Blog',
        'heading' => 'Alle Beiträge',
    ],

    'archive' => [
        'empty' => 'Noch keine Beiträge in diesem Bereich.',
    ],

    'categories' => [
        'heading' => 'Kategorien',
        'empty' => 'Noch keine Kategorien.',
    ],
    'tags' => [
        'heading' => 'Schlagwörter',
        'empty' => 'Noch keine Schlagwörter.',
    ],

    'products' => [
        'heading' => 'Produkte',
        'empty' => 'Noch keine Produkte.',
        'go' => 'Zum Produkt',
        'view' => 'Ansehen',
        'spec' => [
            'model' => 'Modell',
            'type' => 'Typ',
            'fuel_type' => 'Kraftstoffart',
            'heating_type' => 'Heizungsart',
        ],
    ],

    'post' => [
        'reading_time' => ':count Min. Lesezeit',
        'views' => ':count Aufrufe',
        'all_posts' => 'Alle Beiträge',
        'related_eyebrow' => 'Mehr',
        'related_heading' => 'Ähnliche Beiträge',
    ],

    'aria' => [
        'slide' => 'Folie :n',
    ],
];
