<?php

declare(strict_types=1);

/**
 * French UI strings (view chrome + controller-supplied titles/meta).
 * Read via App\Support\Lang::t('key') / Lang::choice('key', $n).
 *
 * Keys must stay identical across every lang/*.php file; only values differ.
 */
return [
    'nav' => [
        'home' => 'Accueil',
        'posts' => 'Articles',
        'products' => 'Produits',
        'categories' => 'Catégories',
        'tags' => 'Étiquettes',
        'menu' => 'Menu',
        'category_one' => 'Catégorie',
        'tag_one' => 'Étiquette',
    ],

    'common' => [
        'all' => 'Tout',
        'read' => 'Lire',
        'post' => 'Article',
        'product' => 'Produit',
        'page' => 'Page',
        'prev' => 'Précédent',
        'next' => 'Suivant',
        'close' => 'Fermer',
        'copy' => 'Copier',
    ],

    'layout' => [
        'footer_tagline' => 'Une interface simple, rapide et moderne pour du contenu multilingue.',
        'rights' => 'Tous droits réservés.',
    ],

    'count' => [
        'posts' => ':count article|:count articles',
        'categories' => ':count catégorie|:count catégories',
        'tags' => ':count étiquette|:count étiquettes',
        'products' => ':count produit|:count produits',
        'results' => ':count résultat|:count résultats',
    ],

    'meta' => [
        'home_desc' => 'Blog multilingue — les derniers articles, histoires et contenus.',
        'blog_desc' => 'Découvrez tous les articles.',
        'products_desc' => 'Découvrez tous nos produits.',
        'categories_desc' => 'Découvrez toutes les catégories.',
        'tags_desc' => 'Découvrez toutes les étiquettes.',
        'search_desc' => 'Recherche dans les articles et les produits.',
        'search_desc_q' => 'Résultats de recherche pour “:q”',
    ],

    'search' => [
        'title' => 'Recherche',
        'title_q' => 'Recherche : :q',
        'placeholder_short' => 'Rechercher…',
        'placeholder_posts' => 'Rechercher des articles…',
        'placeholder_all' => 'Rechercher articles ou produits…',
        'button' => 'Rechercher',
        'site_search' => 'Recherche sur le site',
        'heading' => 'Que recherchez-vous ?',
        'popular_categories' => 'Catégories populaires',
        'browse_tags' => 'Parcourir toutes les étiquettes',
        'results_for' => ':count résultats pour “:q”',
        'no_results' => 'Aucun résultat pour “:q”',
        'no_results_hint' => 'Essayez un autre mot-clé ou parcourez les catégories.',
    ],

    'home' => [
        'hero_title' => 'Idées, histoires<br>et <span class="text-gradient italic">contenu</span>.',
        'hero_subtitle' => 'Découvrez nos contenus multilingues — simples, rapides et agréables à lire.',
        'featured_badge' => 'À la une',
        'featured_read' => 'Lire l’article',
        'latest_eyebrow' => 'Récent',
        'posts_heading' => 'Articles',
        'see_all' => 'Voir tout',
        'empty_posts' => 'Aucun article publié pour le moment.',
    ],

    'blog' => [
        'eyebrow' => 'Blog',
        'heading' => 'Tous les articles',
    ],

    'archive' => [
        'empty' => 'Aucun article dans cette section pour le moment.',
    ],

    'categories' => [
        'heading' => 'Catégories',
        'empty' => 'Aucune catégorie pour le moment.',
    ],
    'tags' => [
        'heading' => 'Étiquettes',
        'empty' => 'Aucune étiquette pour le moment.',
    ],

    'products' => [
        'heading' => 'Produits',
        'empty' => 'Aucun produit pour le moment.',
        'go' => 'Voir le produit',
        'view' => 'Voir',
        'spec' => [
            'model' => 'Modèle',
            'type' => 'Type',
            'fuel_type' => 'Type de carburant',
            'heating_type' => 'Type de chauffage',
        ],
    ],

    'post' => [
        'reading_time' => ':count min de lecture',
        'views' => ':count vues',
        'all_posts' => 'Tous les articles',
        'related_eyebrow' => 'Plus',
        'related_heading' => 'Articles similaires',
    ],

    'aria' => [
        'slide' => 'Diapositive :n',
    ],
];
