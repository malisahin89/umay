<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Localized URL path segments for the public front end.
 * Herkese açık ön yüz için dile çevrili URL yol segmentleri.
 *
 * The router keeps ONE canonical segment vocabulary (blog, products, category…);
 * this maps each canonical word to its per-locale form so both the route
 * registration (routes/web.php) and outbound link building (views/controllers)
 * speak the same localized URLs: /es/productos, /de/kategorie/{slug}, /tr/ara/…
 *
 * Router tek bir kanonik segment sözlüğü tutar (blog, products, category…); bu
 * sınıf her kanonik kelimeyi dile göre karşılığına eşler; böylece hem route kaydı
 * (routes/web.php) hem outbound link üretimi (view/controller) aynı localized
 * URL'leri konuşur.
 *
 * Values within a locale MUST stay distinct (the router matches them as literal
 * segments; two canonicals sharing one localized word would be ambiguous).
 * Bir dil içindeki değerler AYIRT EDİLEBİLİR olmalı (router bunları literal
 * segment olarak eşler; tek localized kelimeyi paylaşan iki kanonik belirsiz olur).
 */
final class RouteSlugs
{
    /** @var array<string, array<string, string>> canonical => [locale => localized] */
    private const MAP = [
        'blog' => ['tr' => 'blog', 'en' => 'blog', 'de' => 'blog', 'fr' => 'blog', 'es' => 'blog'],
        'products' => ['tr' => 'urunler', 'en' => 'products', 'de' => 'produkte', 'fr' => 'produits', 'es' => 'productos'],
        'categories' => ['tr' => 'kategoriler', 'en' => 'categories', 'de' => 'kategorien', 'fr' => 'categories', 'es' => 'categorias'],
        'tags' => ['tr' => 'etiketler', 'en' => 'tags', 'de' => 'schlagwoerter', 'fr' => 'tags', 'es' => 'etiquetas'],
        'category' => ['tr' => 'kategori', 'en' => 'category', 'de' => 'kategorie', 'fr' => 'categorie', 'es' => 'categoria'],
        'tag' => ['tr' => 'etiket', 'en' => 'tag', 'de' => 'schlagwort', 'fr' => 'tag', 'es' => 'etiqueta'],
        'search' => ['tr' => 'ara', 'en' => 'search', 'de' => 'suche', 'fr' => 'recherche', 'es' => 'buscar'],
        'posts' => ['tr' => 'yazi', 'en' => 'posts', 'de' => 'beitrag', 'fr' => 'article', 'es' => 'entrada'],
        'pages' => ['tr' => 'sayfalar', 'en' => 'pages', 'de' => 'seiten', 'fr' => 'pages', 'es' => 'paginas'],
        'page' => ['tr' => 'sayfa', 'en' => 'page', 'de' => 'seite', 'fr' => 'page', 'es' => 'page'],
    ];

    /**
     * Locales that have a localized URL vocabulary (union of all MAP locale keys).
     * Used by routes/web.php to register one localized route group per locale.
     * Localized URL sözlüğü olan diller (tüm MAP locale anahtarlarının birleşimi).
     * routes/web.php her dil için birer localized route grubu kaydederken kullanır.
     *
     * @return list<string>
     */
    public static function locales(): array
    {
        $locales = [];
        foreach (self::MAP as $byLocale) {
            foreach (array_keys($byLocale) as $loc) {
                $locales[$loc] = true;
            }
        }

        return array_keys($locales);
    }

    /**
     * Localized form of one canonical route word; the canonical word itself when the
     * locale has no mapping (so an unmapped locale degrades to canonical segments).
     * Bir kanonik route kelimesinin dile çevrili hâli; dilin eşlemesi yoksa kanonik
     * kelimenin kendisi (eşlemesiz dil kanonik segmentlere iner).
     */
    public static function seg(string $canonical, string $locale): string
    {
        return self::MAP[$canonical][$locale] ?? $canonical;
    }

    /**
     * Build a localized front URL from a canonical path. Only segments that ARE
     * canonical route words get translated; anything else (content slugs, page
     * numbers) passes through untouched.
     * Kanonik bir yoldan localized front URL kur. Yalnızca kanonik route kelimesi
     * OLAN segmentler çevrilir; gerisi (içerik slug'ları, sayfa numaraları) olduğu
     * gibi geçer.
     *
     * to('es', 'products')          → /es/productos
     * to('de', 'blog/page/2')       → /de/blog/seite/2
     *
     * For content slugs prefer seg() + manual concat so a slug that happens to equal
     * a route word is never rewritten: '/'.$loc.'/'.seg('category',$loc).'/'.$slug.
     * İçerik slug'ları için seg() + elle birleştirmeyi tercih et; böylece route
     * kelimesine denk gelen bir slug asla yeniden yazılmaz.
     */
    public static function to(string $locale, string $path = ''): string
    {
        $segments = array_values(array_filter(
            explode('/', trim($path, '/')),
            static fn (string $s): bool => $s !== ''
        ));

        $localized = array_map(
            static fn (string $s): string => isset(self::MAP[$s]) ? self::seg($s, $locale) : $s,
            $segments
        );

        return '/'.$locale.($localized === [] ? '' : '/'.implode('/', $localized));
    }
}
