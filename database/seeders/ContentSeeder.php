<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Support\RouteSlugs;
use Core\Seeder;

/**
 * Demo content — a small software/web-development blog & shop, fully translated
 * into all 5 active locales (tr/en/de/fr/es). Replaces the placeholder
 * "Ürün 1 / Blog Yazısı 1" seed data with meaningful, per-language names & slugs.
 *
 * Demo içerik — küçük bir yazılım/web geliştirme blog & mağazası, 5 aktif dile
 * (tr/en/de/fr/es) tam çevrili. Placeholder "Ürün 1 / Blog Yazısı 1" verisini
 * anlamlı, dile göre isim & slug'larla değiştirir.
 *
 * Idempotent: truncates and re-inserts the content tables with fixed ids, so
 * running it again always yields the same clean dataset.
 * Idempotent: içerik tablolarını sabit id'lerle temizleyip yeniden ekler; tekrar
 * çalıştırınca hep aynı temiz veri kümesini üretir.
 */
class ContentSeeder extends Seeder
{
    /** @var list<string> */
    private const LOCALES = ['tr', 'en', 'de', 'fr', 'es'];

    /** "Read article" call-to-action per locale, shared by slides & popups. */
    private const READ_ARTICLE = [
        'tr' => 'Yazıyı Oku', 'en' => 'Read Article', 'de' => 'Beitrag lesen',
        'fr' => "Lire l'article", 'es' => 'Leer artículo',
    ];

    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $this->seedCategories($now);
        $this->seedTags($now);
        $this->seedPosts($now);
        $this->seedProducts($now);
        $this->seedPivots();
        $this->seedSlides($now);
        $this->seedPopups($now);
    }

    /**
     * Discover uploaded images under storage/app/public/uploads/{type}, sorted, as
     * DB-relative paths ("uploads/{type}/…") — the front views prefix "/storage/".
     * The files are the source of truth, so this survives truncation and works after
     * a fresh reseed as long as the images exist on disk (empty → no cover).
     *
     * storage/app/public/uploads/{type} altındaki yüklenmiş görselleri sıralı, DB'ye
     * göreli yol ("uploads/{type}/…") olarak keşfeder — view'lar "/storage/" ekler.
     * Dosyalar doğruluk kaynağıdır; truncate'ten etkilenmez, dosyalar diskte olduğu
     * sürece fresh reseed'de de çalışır (boşsa → görsel yok).
     *
     * @return list<string>
     */
    private function discoverImages(string $type): array
    {
        $root = defined('BASE_PATH') ? BASE_PATH : getcwd();
        if (! is_string($root)) {
            $root = '.';
        }
        $baseDir = $root.'/storage/app/public/uploads/'.$type;
        $matches = glob($baseDir.'/*/*.{webp,jpg,jpeg,png,gif}', GLOB_BRACE) ?: [];
        sort($matches);

        return array_map(
            static fn (string $f): string => 'uploads/'.$type.'/'.ltrim(str_replace('\\', '/', substr($f, strlen($baseDir))), '/'),
            $matches
        );
    }

    /**
     * Localized front URL of one of the seeded posts (used as slide/popup links).
     * Seed'lenen postlardan birinin localized front URL'i (slide/popup linki olarak).
     */
    private function postUrl(int $postId, string $loc): string
    {
        $slug = $this->postData()[$postId][$loc][1];

        return '/'.$loc.'/'.RouteSlugs::seg('posts', $loc).'/'.$slug;
    }

    private function seedCategories(string $now): void
    {
        // id => [tr, en, de, fr, es] as [name, slug]
        $items = [
            1 => ['tr' => ['Teknoloji', 'teknoloji'], 'en' => ['Technology', 'technology'], 'de' => ['Technologie', 'technologie'], 'fr' => ['Technologie', 'technologie'], 'es' => ['Tecnología', 'tecnologia']],
            2 => ['tr' => ['Yazılım', 'yazilim'], 'en' => ['Software', 'software'], 'de' => ['Software', 'software'], 'fr' => ['Logiciel', 'logiciel'], 'es' => ['Software', 'software']],
            3 => ['tr' => ['Donanım', 'donanim'], 'en' => ['Hardware', 'hardware'], 'de' => ['Hardware', 'hardware'], 'fr' => ['Matériel', 'materiel'], 'es' => ['Hardware', 'hardware']],
            4 => ['tr' => ['Tasarım', 'tasarim'], 'en' => ['Design', 'design'], 'de' => ['Design', 'design'], 'fr' => ['Design', 'design'], 'es' => ['Diseño', 'diseno']],
            5 => ['tr' => ['Haberler', 'haberler'], 'en' => ['News', 'news'], 'de' => ['Nachrichten', 'nachrichten'], 'fr' => ['Actualités', 'actualites'], 'es' => ['Noticias', 'noticias']],
        ];

        $base = [];
        $trans = [];
        foreach ($items as $id => $byLocale) {
            $base[] = ['id' => $id, 'level' => 0, 'status' => 1, 'show_in_nav' => 1, 'nav_order' => $id, 'sort_order' => $id, 'created_at' => $now, 'updated_at' => $now];
            foreach (self::LOCALES as $loc) {
                [$name, $slug] = $byLocale[$loc];
                $trans[] = ['category_id' => $id, 'language_slug' => $loc, 'name' => $name, 'slug' => $slug, 'created_at' => $now, 'updated_at' => $now];
            }
        }

        $this->truncateAndInsert('categories', $base);
        $this->truncateAndInsert('category_translations', $trans);
    }

    private function seedTags(string $now): void
    {
        // Technology tags — proper nouns, identical across locales.
        $items = [
            1 => 'PHP', 2 => 'JavaScript', 3 => 'CSS', 4 => 'HTML', 5 => 'SQL',
        ];
        $slugs = [1 => 'php', 2 => 'javascript', 3 => 'css', 4 => 'html', 5 => 'sql'];

        $base = [];
        $trans = [];
        foreach ($items as $id => $name) {
            $base[] = ['id' => $id, 'status' => 1, 'usage_count' => 0, 'created_at' => $now, 'updated_at' => $now];
            foreach (self::LOCALES as $loc) {
                $trans[] = ['tag_id' => $id, 'language_slug' => $loc, 'name' => $name, 'slug' => $slugs[$id], 'created_at' => $now, 'updated_at' => $now];
            }
        }

        $this->truncateAndInsert('tags', $base);
        $this->truncateAndInsert('tag_translations', $trans);
    }

    private function seedPosts(string $now): void
    {
        $base = [];
        $trans = [];
        foreach ($this->postData() as $id => $data) {
            $base[] = [
                'id' => $id, 'user_id' => 1, 'order' => $id, 'is_featured' => $id === 1 ? 1 : 0,
                'comment_enabled' => 1, 'status' => 1, 'view_count' => 100 - $id * 7,
                'published_at' => $now, 'created_at' => $now, 'updated_at' => $now,
            ];
            foreach (self::LOCALES as $loc) {
                [$title, $slug, $sd, $body] = $data[$loc];
                $trans[] = [
                    'post_id' => $id, 'language_slug' => $loc, 'title' => $title, 'slug' => $slug,
                    'short_description' => $sd, 'content' => $body, 'created_at' => $now, 'updated_at' => $now,
                ];
            }
        }

        $this->truncateAndInsert('posts', $base);
        $this->truncateAndInsert('post_translations', $trans);
    }

    private function seedProducts(string $now): void
    {
        $prices = [1 => 49.00, 2 => 39.00, 3 => 129.00, 4 => 29.00, 5 => 199.00];
        $images = $this->discoverImages('products');

        $base = [];
        $trans = [];
        foreach ($this->productData() as $id => $data) {
            $base[] = [
                'id' => $id, 'order' => $id, 'is_featured' => $id === 1 ? 1 : 0, 'status' => 1,
                'cover_image' => $images[$id - 1] ?? null,
                'brand' => 'Umay', 'price' => $prices[$id], 'model' => 'v1.0',
                'published_at' => $now, 'created_at' => $now, 'updated_at' => $now,
            ];
            foreach (self::LOCALES as $loc) {
                [$title, $slug, $sd, $body] = $data[$loc];
                $trans[] = [
                    'product_id' => $id, 'language_slug' => $loc, 'title' => $title, 'slug' => $slug,
                    'short_description' => $sd, 'content' => $body, 'created_at' => $now, 'updated_at' => $now,
                ];
            }
        }

        $this->truncateAndInsert('products', $base);
        $this->truncateAndInsert('product_translations', $trans);
    }

    private function seedPivots(): void
    {
        // post => [categories], [tags]
        $postCat = [1 => [2, 1], 2 => [4], 3 => [2], 4 => [2], 5 => [2]];
        $postTag = [1 => [1, 5], 2 => [3, 4], 3 => [2, 4], 4 => [5, 1], 5 => [1, 2]];
        $prodCat = [1 => [4], 2 => [4], 3 => [2], 4 => [2], 5 => [2]];
        $prodTag = [1 => [3, 4], 2 => [3, 4], 3 => [1, 2], 4 => [1, 5], 5 => [1, 2]];

        $this->truncateAndInsert('post_category', $this->pivotRows('post_id', 'category_id', $postCat));
        $this->truncateAndInsert('post_tag', $this->pivotRows('post_id', 'tag_id', $postTag));
        $this->truncateAndInsert('product_category', $this->pivotRows('product_id', 'category_id', $prodCat));
        $this->truncateAndInsert('product_tag', $this->pivotRows('product_id', 'tag_id', $prodTag));
    }

    /**
     * @param  array<int, list<int>>  $map
     * @return list<array<string, int>>
     */
    private function pivotRows(string $leftCol, string $rightCol, array $map): array
    {
        $rows = [];
        foreach ($map as $left => $rights) {
            foreach ($rights as $right) {
                $rows[] = [$leftCol => $left, $rightCol => $right];
            }
        }

        return $rows;
    }

    /**
     * Hero slides — one per post. Reuses each post's title/short_description as the
     * slide headline/subtitle and links its button to that post (localized URL).
     * Existing uploaded images (slides.media_file) are PRESERVED across the reseed.
     *
     * Hero slaytları — post başına bir tane. Her postun başlık/kısa açıklamasını
     * slayt başlığı/alt başlığı olarak kullanır, butonunu o postun (localized) URL'ine
     * bağlar. Mevcut yüklenmiş görseller (slides.media_file) reseed'de KORUNUR.
     */
    private function seedSlides(string $now): void
    {
        $posts = $this->postData();
        $media = $this->discoverImages('slides');

        // Short eyebrow label per slide (tech topic of the linked post).
        $labels = [
            1 => ['tr' => 'PHP', 'en' => 'PHP', 'de' => 'PHP', 'fr' => 'PHP', 'es' => 'PHP'],
            2 => ['tr' => 'CSS', 'en' => 'CSS', 'de' => 'CSS', 'fr' => 'CSS', 'es' => 'CSS'],
            3 => ['tr' => 'JavaScript', 'en' => 'JavaScript', 'de' => 'JavaScript', 'fr' => 'JavaScript', 'es' => 'JavaScript'],
            4 => ['tr' => 'SQL', 'en' => 'SQL', 'de' => 'SQL', 'fr' => 'SQL', 'es' => 'SQL'],
            5 => ['tr' => 'Temiz Kod', 'en' => 'Clean Code', 'de' => 'Sauberer Code', 'fr' => 'Code propre', 'es' => 'Código limpio'],
        ];

        $base = [];
        $trans = [];
        for ($id = 1; $id <= 5; $id++) {
            $base[] = [
                'id' => $id, 'type' => 'image', 'media_file' => is_string($media[$id - 1] ?? null) ? $media[$id - 1] : null,
                'text_position' => $id % 2 === 1 ? 'left' : 'right',
                'label_size' => 12, 'title_size' => 48, 'subtitle_size' => 16,
                'order' => $id, 'status' => 1, 'created_at' => $now, 'updated_at' => $now,
            ];
            foreach (self::LOCALES as $loc) {
                $p = $posts[$id][$loc];
                $trans[] = [
                    'slide_id' => $id, 'language_slug' => $loc, 'label' => $labels[$id][$loc],
                    'title' => $p[0], 'subtitle' => $p[2],
                    'button_text' => self::READ_ARTICLE[$loc], 'button_url' => $this->postUrl($id, $loc),
                    'created_at' => $now, 'updated_at' => $now,
                ];
            }
        }

        $this->truncateAndInsert('slides', $base);
        $this->truncateAndInsert('slide_translations', $trans);
    }

    /**
     * Popups — one per post, each promoting a DIFFERENT blog post with its localized
     * link. Active on every page (session frequency) so the demo shows them.
     * Popup'lar — post başına bir tane; her biri FARKLI bir blog yazısını localized
     * linkiyle tanıtır. Demo görünsün diye her sayfada aktif (session sıklığı).
     */
    private function seedPopups(string $now): void
    {
        $posts = $this->postData();

        $base = [];
        $trans = [];
        for ($id = 1; $id <= 5; $id++) {
            $base[] = [
                'id' => $id, 'start_date' => null, 'end_date' => null, 'is_annual' => 0,
                'display_frequency' => 'session', 'target_routes' => '[]', 'is_active' => 1,
                'created_at' => $now, 'updated_at' => $now,
            ];
            foreach (self::LOCALES as $loc) {
                $p = $posts[$id][$loc];
                $trans[] = [
                    'popup_id' => $id, 'language_slug' => $loc, 'title' => $p[0],
                    'content' => '<p>'.$p[2].'</p>', 'image' => null,
                    'button_text' => self::READ_ARTICLE[$loc], 'button_url' => $this->postUrl($id, $loc),
                    'created_at' => $now, 'updated_at' => $now,
                ];
            }
        }

        $this->truncateAndInsert('popups', $base);
        $this->truncateAndInsert('popup_translations', $trans);
    }

    /**
     * Posts: id => [locale => [title, slug, short_description, content]].
     *
     * @return array<int, array<string, array{0:string,1:string,2:string,3:string}>>
     */
    private function postData(): array
    {
        return [
            1 => [
                'tr' => ['PHP 8.3 ile Gelen Yenilikler', 'php-8-3-ile-gelen-yenilikler', 'PHP 8.3 sürümüyle gelen yeni özellikleri, performans iyileştirmelerini ve dikkat edilmesi gerekenleri inceliyoruz.', '<p>PHP 8.3; tiplendirilmiş sınıf sabitleri, yeni <code>json_validate()</code> fonksiyonu ve derin klonlama iyileştirmeleri getiriyor. Bu yazıda öne çıkan değişiklikleri örneklerle ele alıyor ve projelerinizi güncellerken nelere dikkat etmeniz gerektiğini anlatıyoruz.</p>'],
                'en' => ["What's New in PHP 8.3", 'whats-new-in-php-8-3', 'We look at the new features, performance improvements and gotchas that arrived with PHP 8.3.', '<p>PHP 8.3 introduces typed class constants, the new <code>json_validate()</code> function and improvements to deep cloning. In this article we walk through the highlights with examples and what to watch out for when upgrading your projects.</p>'],
                'de' => ['Was ist neu in PHP 8.3', 'was-ist-neu-in-php-8-3', 'Wir werfen einen Blick auf die neuen Funktionen, Performance-Verbesserungen und Fallstricke von PHP 8.3.', '<p>PHP 8.3 führt typisierte Klassenkonstanten, die neue Funktion <code>json_validate()</code> und Verbesserungen beim tiefen Klonen ein. In diesem Artikel gehen wir die Highlights mit Beispielen durch und zeigen, worauf beim Upgrade zu achten ist.</p>'],
                'fr' => ['Les nouveautés de PHP 8.3', 'les-nouveautes-de-php-8-3', 'Nous passons en revue les nouveautés, les améliorations de performances et les pièges de PHP 8.3.', "<p>PHP 8.3 introduit les constantes de classe typées, la nouvelle fonction <code>json_validate()</code> et des améliorations du clonage profond. Dans cet article, nous parcourons les points forts avec des exemples et ce qu'il faut surveiller lors de la mise à niveau.</p>"],
                'es' => ['Novedades de PHP 8.3', 'novedades-de-php-8-3', 'Repasamos las nuevas funciones, las mejoras de rendimiento y los detalles a tener en cuenta de PHP 8.3.', '<p>PHP 8.3 introduce constantes de clase tipadas, la nueva función <code>json_validate()</code> y mejoras en la clonación profunda. En este artículo recorremos lo más destacado con ejemplos y qué tener en cuenta al actualizar tus proyectos.</p>'],
            ],
            2 => [
                'tr' => ['Modern CSS ile Responsive Tasarım', 'modern-css-ile-responsive-tasarim', 'Grid, Flexbox ve container query gibi modern CSS araçlarıyla her ekrana uyum sağlayan arayüzler kurmayı anlatıyoruz.', '<p>Modern CSS; Grid, Flexbox, <code>clamp()</code> ve container query sayesinde medya sorgularına daha az bağımlı, gerçekten esnek arayüzler kurmayı mümkün kılıyor. Bu yazıda küçük örneklerle responsive bir düzenin nasıl oluşturulacağını adım adım gösteriyoruz.</p>'],
                'en' => ['Responsive Design with Modern CSS', 'responsive-design-with-modern-css', 'We show how to build interfaces that adapt to any screen using modern CSS tools like Grid, Flexbox and container queries.', '<p>Modern CSS makes truly flexible layouts possible with Grid, Flexbox, <code>clamp()</code> and container queries, reducing the need for media queries. In this article we build a responsive layout step by step with small, practical examples.</p>'],
                'de' => ['Responsives Design mit modernem CSS', 'responsives-design-mit-modernem-css', 'Wir zeigen, wie man mit modernen CSS-Werkzeugen wie Grid, Flexbox und Container Queries Oberflächen für jeden Bildschirm baut.', '<p>Modernes CSS ermöglicht mit Grid, Flexbox, <code>clamp()</code> und Container Queries wirklich flexible Layouts und reduziert den Bedarf an Media Queries. In diesem Artikel bauen wir Schritt für Schritt ein responsives Layout mit kleinen, praktischen Beispielen.</p>'],
                'fr' => ['Design responsive avec le CSS moderne', 'design-responsive-avec-le-css-moderne', 'Nous montrons comment créer des interfaces qui s\'adaptent à tous les écrans avec les outils CSS modernes : Grid, Flexbox et container queries.', '<p>Le CSS moderne rend possibles des mises en page vraiment flexibles grâce à Grid, Flexbox, <code>clamp()</code> et aux container queries, en réduisant le recours aux media queries. Dans cet article, nous construisons une mise en page responsive pas à pas avec de petits exemples concrets.</p>'],
                'es' => ['Diseño responsive con CSS moderno', 'diseno-responsive-con-css-moderno', 'Mostramos cómo crear interfaces que se adaptan a cualquier pantalla con herramientas de CSS moderno como Grid, Flexbox y container queries.', '<p>El CSS moderno hace posibles diseños realmente flexibles con Grid, Flexbox, <code>clamp()</code> y las container queries, reduciendo la dependencia de las media queries. En este artículo construimos un diseño responsive paso a paso con ejemplos pequeños y prácticos.</p>'],
            ],
            3 => [
                'tr' => ['JavaScript\'te Asenkron Programlama', 'javascriptte-asenkron-programlama', 'Callback, Promise ve async/await ile JavaScript\'te asenkron akışları nasıl temiz biçimde yöneteceğinizi anlatıyoruz.', '<p>JavaScript tek iş parçacıklıdır ama Promise ve <code>async/await</code> sayesinde ağ istekleri gibi işleri arayüzü kilitlemeden yürütür. Bu yazıda callback\'lerden async/await\'e geçişi ve hataları doğru yönetmeyi örneklerle ele alıyoruz.</p>'],
                'en' => ['Asynchronous Programming in JavaScript', 'asynchronous-programming-in-javascript', 'We explain how to cleanly manage asynchronous flows in JavaScript with callbacks, Promises and async/await.', '<p>JavaScript is single-threaded, yet Promises and <code>async/await</code> let it handle work like network requests without blocking the UI. In this article we cover moving from callbacks to async/await and handling errors correctly, with examples.</p>'],
                'de' => ['Asynchrone Programmierung in JavaScript', 'asynchrone-programmierung-in-javascript', 'Wir erklären, wie man asynchrone Abläufe in JavaScript mit Callbacks, Promises und async/await sauber steuert.', '<p>JavaScript ist single-threaded, doch Promises und <code>async/await</code> ermöglichen es, Aufgaben wie Netzwerkanfragen ohne Blockieren der Oberfläche zu erledigen. In diesem Artikel behandeln wir den Umstieg von Callbacks auf async/await und die richtige Fehlerbehandlung mit Beispielen.</p>'],
                'fr' => ['La programmation asynchrone en JavaScript', 'la-programmation-asynchrone-en-javascript', 'Nous expliquons comment gérer proprement les flux asynchrones en JavaScript avec les callbacks, les Promises et async/await.', '<p>JavaScript est monothread, mais les Promises et <code>async/await</code> lui permettent de gérer des tâches comme les requêtes réseau sans bloquer l\'interface. Dans cet article, nous abordons le passage des callbacks à async/await et la bonne gestion des erreurs, avec des exemples.</p>'],
                'es' => ['Programación asíncrona en JavaScript', 'programacion-asincrona-en-javascript', 'Explicamos cómo gestionar de forma limpia los flujos asíncronos en JavaScript con callbacks, Promises y async/await.', '<p>JavaScript es de un solo hilo, pero las Promises y <code>async/await</code> le permiten gestionar tareas como las peticiones de red sin bloquear la interfaz. En este artículo tratamos el paso de los callbacks a async/await y el manejo correcto de errores, con ejemplos.</p>'],
            ],
            4 => [
                'tr' => ['Veritabanı Optimizasyonu için SQL İpuçları', 'veritabani-optimizasyonu-icin-sql-ipuclari', 'Doğru indeksler, seçici sorgular ve EXPLAIN ile SQL performansını nasıl artıracağınıza dair pratik ipuçları.', '<p>Yavaş sorguların çoğu eksik indeks ya da gereksiz kolon seçiminden kaynaklanır. Bu yazıda <code>EXPLAIN</code> ile sorguları çözümlemeyi, doğru indeksleri seçmeyi ve N+1 problemini önlemeyi somut örneklerle gösteriyoruz.</p>'],
                'en' => ['SQL Tips for Database Optimization', 'sql-tips-for-database-optimization', 'Practical tips on boosting SQL performance with the right indexes, selective queries and EXPLAIN.', '<p>Most slow queries come from missing indexes or selecting unnecessary columns. In this article we show how to analyse queries with <code>EXPLAIN</code>, choose the right indexes and avoid the N+1 problem, with concrete examples.</p>'],
                'de' => ['SQL-Tipps zur Datenbankoptimierung', 'sql-tipps-zur-datenbankoptimierung', 'Praktische Tipps, um die SQL-Performance mit den richtigen Indizes, selektiven Abfragen und EXPLAIN zu steigern.', '<p>Die meisten langsamen Abfragen entstehen durch fehlende Indizes oder das Auswählen unnötiger Spalten. In diesem Artikel zeigen wir, wie man Abfragen mit <code>EXPLAIN</code> analysiert, die richtigen Indizes wählt und das N+1-Problem vermeidet – mit konkreten Beispielen.</p>'],
                'fr' => ['Astuces SQL pour optimiser votre base de données', 'astuces-sql-pour-optimiser-votre-base-de-donnees', 'Des astuces concrètes pour améliorer les performances SQL avec les bons index, des requêtes sélectives et EXPLAIN.', '<p>La plupart des requêtes lentes proviennent d\'index manquants ou de la sélection de colonnes inutiles. Dans cet article, nous montrons comment analyser les requêtes avec <code>EXPLAIN</code>, choisir les bons index et éviter le problème N+1, avec des exemples concrets.</p>'],
                'es' => ['Consejos SQL para optimizar bases de datos', 'consejos-sql-para-optimizar-bases-de-datos', 'Consejos prácticos para mejorar el rendimiento de SQL con los índices adecuados, consultas selectivas y EXPLAIN.', '<p>La mayoría de las consultas lentas se deben a índices ausentes o a seleccionar columnas innecesarias. En este artículo mostramos cómo analizar consultas con <code>EXPLAIN</code>, elegir los índices adecuados y evitar el problema N+1, con ejemplos concretos.</p>'],
            ],
            5 => [
                'tr' => ['Temiz Kod Yazmanın 10 Kuralı', 'temiz-kod-yazmanin-10-kurali', 'Anlamlı isimlendirme, küçük fonksiyonlar ve iyi testlerle sürdürülebilir kod yazmanın 10 temel kuralı.', '<p>Temiz kod; kısa fonksiyonlar, anlamlı isimler, tek sorumluluk ve iyi testlerle başlar. Bu yazıda ekip içinde okunabilirliği ve bakımı kolaylaştıran 10 pratik kuralı örneklerle derledik.</p>'],
                'en' => ['10 Rules for Writing Clean Code', '10-rules-for-writing-clean-code', 'Ten core rules for writing maintainable code with meaningful names, small functions and good tests.', '<p>Clean code starts with short functions, meaningful names, single responsibility and good tests. In this article we gather ten practical rules that make code easier to read and maintain across a team, with examples.</p>'],
                'de' => ['10 Regeln für sauberen Code', '10-regeln-fur-sauberen-code', 'Zehn Kernregeln für wartbaren Code mit aussagekräftigen Namen, kleinen Funktionen und guten Tests.', '<p>Sauberer Code beginnt mit kurzen Funktionen, aussagekräftigen Namen, einer einzigen Verantwortung und guten Tests. In diesem Artikel sammeln wir zehn praktische Regeln, die Code im Team lesbarer und wartbarer machen – mit Beispielen.</p>'],
                'fr' => ['10 règles pour écrire du code propre', '10-regles-pour-ecrire-du-code-propre', 'Dix règles essentielles pour écrire du code maintenable avec des noms parlants, de petites fonctions et de bons tests.', '<p>Un code propre commence par des fonctions courtes, des noms parlants, une responsabilité unique et de bons tests. Dans cet article, nous rassemblons dix règles pratiques qui rendent le code plus lisible et plus facile à maintenir en équipe, avec des exemples.</p>'],
                'es' => ['10 reglas para escribir código limpio', '10-reglas-para-escribir-codigo-limpio', 'Diez reglas esenciales para escribir código mantenible con nombres significativos, funciones pequeñas y buenas pruebas.', '<p>El código limpio empieza con funciones cortas, nombres significativos, una sola responsabilidad y buenas pruebas. En este artículo reunimos diez reglas prácticas que hacen el código más legible y fácil de mantener en equipo, con ejemplos.</p>'],
            ],
        ];
    }

    /**
     * Products: id => [locale => [title, slug, short_description, content]].
     *
     * @return array<int, array<string, array{0:string,1:string,2:string,3:string}>>
     */
    private function productData(): array
    {
        return [
            1 => [
                'tr' => ['Umay Yönetim Teması', 'umay-yonetim-temasi', 'Umay framework için modern, hızlı ve tamamen responsive bir yönetim paneli teması.', '<p>Umay Yönetim Teması; karanlık/aydınlık mod, hazır bileşenler ve temiz bir arayüzle projelerinize profesyonel bir yönetim paneli kazandırır. Kurulumu kolaydır ve tüm modüllerle uyumludur.</p>'],
                'en' => ['Umay Admin Theme', 'umay-admin-theme', 'A modern, fast and fully responsive admin panel theme for the Umay framework.', '<p>The Umay Admin Theme gives your projects a professional dashboard with dark/light mode, ready-made components and a clean interface. It is easy to install and compatible with every module.</p>'],
                'de' => ['Umay Admin-Theme', 'umay-admin-theme', 'Ein modernes, schnelles und vollständig responsives Admin-Panel-Theme für das Umay-Framework.', '<p>Das Umay Admin-Theme verleiht Ihren Projekten ein professionelles Dashboard mit Dunkel-/Hell-Modus, fertigen Komponenten und einer klaren Oberfläche. Es ist einfach zu installieren und mit jedem Modul kompatibel.</p>'],
                'fr' => ['Thème d\'administration Umay', 'theme-d-administration-umay', 'Un thème de panneau d\'administration moderne, rapide et entièrement responsive pour le framework Umay.', '<p>Le thème d\'administration Umay offre à vos projets un tableau de bord professionnel avec mode sombre/clair, des composants prêts à l\'emploi et une interface épurée. Il est facile à installer et compatible avec tous les modules.</p>'],
                'es' => ['Tema de Administración Umay', 'tema-de-administracion-umay', 'Un tema de panel de administración moderno, rápido y totalmente responsive para el framework Umay.', '<p>El Tema de Administración Umay ofrece a tus proyectos un panel profesional con modo oscuro/claro, componentes listos para usar y una interfaz limpia. Es fácil de instalar y compatible con todos los módulos.</p>'],
            ],
            2 => [
                'tr' => ['Umay Blog Şablonu', 'umay-blog-sablonu', 'Editöryel görünümlü, SEO uyumlu ve çok dilli bir blog için hazır arayüz şablonu.', '<p>Umay Blog Şablonu; okunabilir tipografi, öne çıkan yazı alanları ve hızlı sayfa yükleme ile modern bir blog deneyimi sunar. Çok dilli yapıyla kutudan çıktığı gibi uyumludur.</p>'],
                'en' => ['Umay Blog Template', 'umay-blog-template', 'A ready-made, editorial-style, SEO-friendly template for a multilingual blog.', '<p>The Umay Blog Template delivers a modern blogging experience with readable typography, featured-post areas and fast page loads. It works out of the box with the multilingual setup.</p>'],
                'de' => ['Umay Blog-Vorlage', 'umay-blog-vorlage', 'Eine fertige, redaktionell gestaltete und SEO-freundliche Vorlage für einen mehrsprachigen Blog.', '<p>Die Umay Blog-Vorlage bietet mit lesbarer Typografie, Bereichen für hervorgehobene Beiträge und schnellen Ladezeiten ein modernes Blog-Erlebnis. Sie ist von Haus aus mit dem mehrsprachigen Aufbau kompatibel.</p>'],
                'fr' => ['Modèle de blog Umay', 'modele-de-blog-umay', 'Un modèle prêt à l\'emploi, au style éditorial et optimisé pour le SEO, pour un blog multilingue.', '<p>Le modèle de blog Umay offre une expérience de blog moderne avec une typographie lisible, des zones d\'articles à la une et un chargement rapide des pages. Il fonctionne d\'emblée avec la configuration multilingue.</p>'],
                'es' => ['Plantilla de Blog Umay', 'plantilla-de-blog-umay', 'Una plantilla lista para usar, de estilo editorial y optimizada para SEO, para un blog multilingüe.', '<p>La Plantilla de Blog Umay ofrece una experiencia de blog moderna con tipografía legible, áreas de entradas destacadas y carga rápida de páginas. Funciona desde el primer momento con la configuración multilingüe.</p>'],
            ],
            3 => [
                'tr' => ['E-Ticaret Başlangıç Kiti', 'e-ticaret-baslangic-kiti', 'Ürün kataloğu, sepet ve ödeme akışıyla hızlı başlangıç için hazır e-ticaret modülü.', '<p>E-Ticaret Başlangıç Kiti; ürün listeleme, filtreleme, sepet ve ödeme adımlarını içeren hazır bir temel sunar. Umay üzerinde çalışır ve kendi tasarımınıza kolayca uyarlanır.</p>'],
                'en' => ['E-Commerce Starter Kit', 'e-commerce-starter-kit', 'A ready e-commerce module for a fast start with a product catalog, cart and checkout flow.', '<p>The E-Commerce Starter Kit gives you a ready foundation with product listing, filtering, cart and checkout steps. It runs on Umay and is easy to adapt to your own design.</p>'],
                'de' => ['E-Commerce-Starterkit', 'e-commerce-starterkit', 'Ein fertiges E-Commerce-Modul für den schnellen Start mit Produktkatalog, Warenkorb und Checkout-Flow.', '<p>Das E-Commerce-Starterkit bietet eine fertige Basis mit Produktliste, Filterung, Warenkorb und Checkout-Schritten. Es läuft auf Umay und lässt sich leicht an Ihr eigenes Design anpassen.</p>'],
                'fr' => ['Kit de démarrage e-commerce', 'kit-de-demarrage-e-commerce', 'Un module e-commerce prêt à l\'emploi pour démarrer vite avec catalogue produits, panier et tunnel de paiement.', '<p>Le kit de démarrage e-commerce vous offre une base prête avec listing produits, filtres, panier et étapes de paiement. Il fonctionne sur Umay et s\'adapte facilement à votre propre design.</p>'],
                'es' => ['Kit de inicio para e-commerce', 'kit-de-inicio-para-e-commerce', 'Un módulo de e-commerce listo para empezar rápido con catálogo de productos, carrito y flujo de pago.', '<p>El Kit de inicio para e-commerce te ofrece una base lista con listado de productos, filtros, carrito y pasos de pago. Funciona sobre Umay y se adapta fácilmente a tu propio diseño.</p>'],
            ],
            4 => [
                'tr' => ['SEO Optimizasyon Eklentisi', 'seo-optimizasyon-eklentisi', 'Meta etiketleri, site haritası ve zengin snippet\'lerle arama motoru görünürlüğünü artıran eklenti.', '<p>SEO Optimizasyon Eklentisi; otomatik meta etiketleri, XML site haritası ve yapılandırılmış veri desteğiyle sitenizin arama motorlarındaki görünürlüğünü artırır. Çok dilli içerikle tam uyumludur.</p>'],
                'en' => ['SEO Optimization Plugin', 'seo-optimization-plugin', 'A plugin that improves search engine visibility with meta tags, a sitemap and rich snippets.', '<p>The SEO Optimization Plugin boosts your site\'s search visibility with automatic meta tags, an XML sitemap and structured-data support. It is fully compatible with multilingual content.</p>'],
                'de' => ['SEO-Optimierungs-Plugin', 'seo-optimierungs-plugin', 'Ein Plugin, das die Sichtbarkeit in Suchmaschinen mit Meta-Tags, Sitemap und Rich Snippets verbessert.', '<p>Das SEO-Optimierungs-Plugin steigert die Sichtbarkeit Ihrer Website mit automatischen Meta-Tags, einer XML-Sitemap und Unterstützung für strukturierte Daten. Es ist voll mit mehrsprachigen Inhalten kompatibel.</p>'],
                'fr' => ['Extension d\'optimisation SEO', 'extension-d-optimisation-seo', 'Une extension qui améliore la visibilité sur les moteurs de recherche avec balises meta, sitemap et rich snippets.', '<p>L\'extension d\'optimisation SEO améliore la visibilité de votre site grâce à des balises meta automatiques, un sitemap XML et la prise en charge des données structurées. Elle est entièrement compatible avec le contenu multilingue.</p>'],
                'es' => ['Complemento de optimización SEO', 'complemento-de-optimizacion-seo', 'Un complemento que mejora la visibilidad en buscadores con metaetiquetas, sitemap y rich snippets.', '<p>El Complemento de optimización SEO mejora la visibilidad de tu sitio con metaetiquetas automáticas, un sitemap XML y soporte de datos estructurados. Es totalmente compatible con el contenido multilingüe.</p>'],
            ],
            5 => [
                'tr' => ['Çok Dilli Site Paketi', 'cok-dilli-site-paketi', 'Sınırsız dil, otomatik yönlendirme ve dile çevrili URL\'lerle tam çok dilli site kurulumu.', '<p>Çok Dilli Site Paketi; sınırsız dil desteği, dile göre çevrili URL\'ler ve kolay çeviri yönetimiyle sitenizi tamamen çok dilli hale getirir. Umay\'ın çeviri altyapısıyla sorunsuz çalışır.</p>'],
                'en' => ['Multilingual Site Bundle', 'multilingual-site-bundle', 'A complete multilingual site setup with unlimited languages, automatic redirects and localized URLs.', '<p>The Multilingual Site Bundle makes your site fully multilingual with unlimited language support, localized URLs and easy translation management. It works seamlessly with Umay\'s translation layer.</p>'],
                'de' => ['Mehrsprachiges Website-Paket', 'mehrsprachiges-website-paket', 'Ein komplettes mehrsprachiges Setup mit unbegrenzten Sprachen, automatischen Weiterleitungen und lokalisierten URLs.', '<p>Das mehrsprachige Website-Paket macht Ihre Website mit unbegrenzter Sprachunterstützung, lokalisierten URLs und einfacher Übersetzungsverwaltung vollständig mehrsprachig. Es arbeitet nahtlos mit der Übersetzungsebene von Umay zusammen.</p>'],
                'fr' => ['Pack de site multilingue', 'pack-de-site-multilingue', 'Une configuration multilingue complète avec langues illimitées, redirections automatiques et URLs localisées.', '<p>Le pack de site multilingue rend votre site entièrement multilingue avec un support de langues illimité, des URLs localisées et une gestion des traductions simple. Il fonctionne parfaitement avec la couche de traduction d\'Umay.</p>'],
                'es' => ['Paquete de sitio multilingüe', 'paquete-de-sitio-multilingue', 'Una configuración multilingüe completa con idiomas ilimitados, redirecciones automáticas y URLs localizadas.', '<p>El Paquete de sitio multilingüe hace que tu sitio sea totalmente multilingüe con soporte de idiomas ilimitado, URLs localizadas y una gestión de traducciones sencilla. Funciona a la perfección con la capa de traducción de Umay.</p>'],
            ],
        ];
    }
}
