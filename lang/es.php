<?php

declare(strict_types=1);

/**
 * Spanish UI strings (view chrome + controller-supplied titles/meta).
 * Read via App\Support\Lang::t('key') / Lang::choice('key', $n).
 *
 * Keys must stay identical across every lang/*.php file; only values differ.
 */
return [
    'nav' => [
        'home' => 'Inicio',
        'posts' => 'Entradas',
        'products' => 'Productos',
        'categories' => 'Categorías',
        'tags' => 'Etiquetas',
        'menu' => 'Menú',
        'category_one' => 'Categoría',
        'tag_one' => 'Etiqueta',
    ],

    'common' => [
        'all' => 'Todo',
        'read' => 'Leer',
        'post' => 'Entrada',
        'product' => 'Producto',
        'page' => 'Página',
        'prev' => 'Anterior',
        'next' => 'Siguiente',
        'close' => 'Cerrar',
        'copy' => 'Copiar',
    ],

    'layout' => [
        'footer_tagline' => 'Una interfaz sencilla, rápida y moderna para contenido multilingüe.',
        'rights' => 'Todos los derechos reservados.',
    ],

    'count' => [
        'posts' => ':count entrada|:count entradas',
        'categories' => ':count categoría|:count categorías',
        'tags' => ':count etiqueta|:count etiquetas',
        'products' => ':count producto|:count productos',
        'results' => ':count resultado|:count resultados',
    ],

    'meta' => [
        'home_desc' => 'Blog multilingüe — las últimas entradas, historias y contenidos.',
        'blog_desc' => 'Descubre todas las entradas.',
        'products_desc' => 'Descubre todos nuestros productos.',
        'categories_desc' => 'Descubre todas las categorías.',
        'tags_desc' => 'Descubre todas las etiquetas.',
        'search_desc' => 'Búsqueda en entradas y productos.',
        'search_desc_q' => 'Resultados de búsqueda para “:q”',
    ],

    'search' => [
        'title' => 'Búsqueda',
        'title_q' => 'Búsqueda: :q',
        'placeholder_short' => 'Buscar…',
        'placeholder_posts' => 'Buscar entradas…',
        'placeholder_all' => 'Buscar entradas o productos…',
        'button' => 'Buscar',
        'site_search' => 'Búsqueda en el sitio',
        'heading' => '¿Qué estás buscando?',
        'popular_categories' => 'Categorías populares',
        'browse_tags' => 'Explorar todas las etiquetas',
        'results_for' => ':count resultados para “:q”',
        'no_results' => 'No se encontraron resultados para “:q”',
        'no_results_hint' => 'Prueba con otra palabra clave o explora las categorías.',
    ],

    'home' => [
        'hero_title' => 'Ideas, historias<br>y <span class="text-gradient italic">contenido</span>.',
        'hero_subtitle' => 'Descubre nuestro contenido multilingüe: sencillo, rápido y agradable de leer.',
        'featured_badge' => 'Destacado',
        'featured_read' => 'Leer artículo',
        'latest_eyebrow' => 'Reciente',
        'posts_heading' => 'Entradas',
        'see_all' => 'Ver todo',
        'empty_posts' => 'Aún no hay entradas publicadas.',
    ],

    'blog' => [
        'eyebrow' => 'Blog',
        'heading' => 'Todas las entradas',
    ],

    'archive' => [
        'empty' => 'Aún no hay entradas en esta sección.',
    ],

    'categories' => [
        'heading' => 'Categorías',
        'empty' => 'Aún no hay categorías.',
    ],
    'tags' => [
        'heading' => 'Etiquetas',
        'empty' => 'Aún no hay etiquetas.',
    ],

    'products' => [
        'heading' => 'Productos',
        'empty' => 'Aún no hay productos.',
        'go' => 'Ir al producto',
        'view' => 'Ver',
        'spec' => [
            'model' => 'Modelo',
            'type' => 'Tipo',
            'fuel_type' => 'Tipo de combustible',
            'heating_type' => 'Tipo de calefacción',
        ],
    ],

    'post' => [
        'reading_time' => ':count min de lectura',
        'views' => ':count visitas',
        'all_posts' => 'Todas las entradas',
        'related_eyebrow' => 'Más',
        'related_heading' => 'Entradas relacionadas',
    ],

    'aria' => [
        'slide' => 'Diapositiva :n',
    ],
];
