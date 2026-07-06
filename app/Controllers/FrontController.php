<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Category;
use App\Models\Language;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Slide;
use App\Models\Tag;
use App\Support\Lang;
use App\Support\Locale;
use App\Support\RouteSlugs;
use Core\Facades\View;
use Core\Request;
use Illuminate\Database\Eloquent\Builder;

/**
 * Public, locale-aware front end. Reached under the /{locale} prefix, so the
 * LocaleMiddleware has already set the active locale before these run and every
 * $model->title / ->slug resolves in that language (falling back to default).
 *
 * Herkese açık, dile duyarlı ön yüz. /{locale} prefix'i altında çalışır; bu yüzden
 * LocaleMiddleware aktif dili çoktan set etmiştir ve her $model->title / ->slug o
 * dilde çözülür (varsayılana düşerek).
 */
class FrontController
{
    private const PER_PAGE = 9;

    public function home(Request $request, string $page = '1'): void
    {
        $locale = Locale::get();

        // Newest featured post is highlighted on page 1 and excluded from the grid.
        // Its card shows no category overlay, so skip the categories eager-load here.
        // En yeni öne çıkan yazı 1. sayfada vurgulanır ve grid'den çıkarılır. Kartı kategori
        // rozeti göstermediği için burada kategorileri eager-load ETME.
        /** @var Post|null $featured */
        $featured = Post::query()->where('status', 1)->where('is_featured', 1)
            ->with('translations')->orderByDesc('id')->first();

        $grid = $this->basePostQuery();
        if ($featured !== null) {
            $grid->whereKeyNot($featured->getKey());
        }
        $paged = $this->paginate($grid, $this->pageNum($page));

        $slides = Slide::query()->where('status', 1)->with('translations')->orderBy('order')->get();

        View::render('front/home', [
            'title' => Lang::t('nav.home'),
            'description' => Lang::t('meta.home_desc'),
            'locale' => $locale,
            'slides' => $slides,
            'featured' => $featured,
            'posts' => $paged['items'],
            'page' => $paged['page'],
            'lastPage' => $paged['lastPage'],
            'total' => $paged['total'],
            'baseUrl' => '/'.$locale,
            'categories' => $this->activeCategories(),        ]);
    }

    /**
     * Dedicated blog/posts listing (paginated) — a real page, not the home #anchor.
     * Ayrı blog/yazılar listesi (sayfalı) — anasayfa #çapası değil, gerçek sayfa.
     */
    public function blog(Request $request, string $page = '1'): void
    {
        $locale = Locale::get();
        $paged = $this->paginate($this->basePostQuery(), $this->pageNum($page));

        View::render('front/blog', [
            'title' => Lang::t('nav.posts'),
            'description' => Lang::t('meta.blog_desc'),
            'locale' => $locale,
            'posts' => $paged['items'],
            'page' => $paged['page'],
            'lastPage' => $paged['lastPage'],
            'total' => $paged['total'],
            'baseUrl' => RouteSlugs::to($locale, 'blog'),
            'categories' => $this->activeCategories(),        ]);
    }

    /**
     * Product listing (archive) with pagination.
     * Ürün listesi (arşiv), sayfalamalı.
     */
    public function products(Request $request, string $page = '1'): void
    {
        $locale = Locale::get();
        $query = Product::query()->where('status', 1)->with(['translations', 'categories.translations']);
        $query->orderByDesc('id');
        $paged = $this->paginate($query, $this->pageNum($page));

        View::render('front/products/index', [
            'title' => Lang::t('nav.products'),
            'description' => Lang::t('meta.products_desc'),
            'locale' => $locale,
            'products' => $paged['items'],
            'page' => $paged['page'],
            'lastPage' => $paged['lastPage'],
            'total' => $paged['total'],
            'baseUrl' => RouteSlugs::to($locale, 'products'),
        ]);
    }

    public function product(Request $request, string $slug): void
    {
        $locale = Locale::get();
        /** @var Product|null $product */
        $product = Product::whereTranslation('slug', $slug)
            ->where('status', 1)
            ->with(['translations', 'categories.translations', 'tags.translations'])
            ->first();

        if ($product === null) {
            abort(404);
        }

        $sd = $product->getAttribute('short_description');
        $desc = is_string($sd) && $sd !== '' ? $sd : (is_string($product->title) ? $product->title : '');

        View::render('front/products/show', [
            'title' => is_string($product->title) ? $product->title : Lang::t('common.product'),
            'description' => $desc,
            'locale' => $locale,
            'product' => $product,
            'langUrls' => $this->localeUrls($product->getRelationValue('translations'), 'products'),
        ]);
    }

    public function post(Request $request, string $slug): void
    {
        $locale = Locale::get();
        /** @var Post|null $post */
        $post = Post::whereTranslation('slug', $slug)
            ->where('status', 1)
            ->with(['translations', 'categories.translations', 'tags.translations', 'user'])
            ->first();

        if ($post === null) {
            abort(404);
        }

        // Bump the view counter atomically, and mirror the +1 in memory for display.
        // Görüntülenme sayacını atomik artır ve gösterim için +1'i bellekte de yansıt.
        Post::query()->whereKey($post->getKey())->increment('view_count');
        $current = $post->getAttribute('view_count');
        $post->setAttribute('view_count', (is_numeric($current) ? (int) $current : 0) + 1);

        // Related posts sharing at least one category (excluding this one).
        // En az bir kategoriyi paylaşan ilgili yazılar (bunun hariç).
        $catIds = [];
        $cats = $post->getRelationValue('categories');
        if (is_iterable($cats)) {
            foreach ($cats as $cat) {
                if ($cat instanceof \Illuminate\Database\Eloquent\Model && is_numeric($k = $cat->getKey())) {
                    $catIds[] = (int) $k;
                }
            }
        }
        $related = [];
        if ($catIds !== []) {
            $related = $this->basePostQuery()
                ->whereKeyNot($post->getKey())
                ->whereHas('categories', fn (Builder $q) => $q->whereIn('categories.id', $catIds))
                ->limit(3)
                ->get();
        }

        $sd = $post->getAttribute('short_description');
        $desc = is_string($sd) && $sd !== '' ? $sd : (is_string($post->title) ? $post->title : '');

        View::render('front/post', [
            'title' => is_string($post->title) ? $post->title : Lang::t('common.post'),
            'description' => $desc,
            'locale' => $locale,
            'post' => $post,
            'related' => $related,
            'langUrls' => $this->localeUrls($post->getRelationValue('translations'), 'posts'),
        ]);
    }

    /**
     * Posts in a category (archive). / Bir kategorideki yazılar (arşiv).
     */
    public function category(Request $request, string $slug, string $page = '1'): void
    {
        $locale = Locale::get();
        /** @var Category|null $category */
        $category = Category::whereTranslation('slug', $slug)->with('translations')->first();
        if ($category === null) {
            abort(404);
        }

        $query = $this->basePostQuery()->whereHas('categories', fn (Builder $q) => $q->where('categories.id', $category->getKey()));
        $langUrls = $this->localeUrls($category->getRelationValue('translations'), 'category');
        $baseUrl = '/'.$locale.'/'.RouteSlugs::seg('category', $locale).'/'.$slug;
        $this->renderArchive($locale, Lang::t('nav.category_one'), (string) $category->name, $baseUrl, $query, $this->pageNum($page), $langUrls);
    }

    /**
     * Posts with a tag (archive). / Bir etiketli yazılar (arşiv).
     */
    public function tag(Request $request, string $slug, string $page = '1'): void
    {
        $locale = Locale::get();
        /** @var Tag|null $tag */
        $tag = Tag::whereTranslation('slug', $slug)->with('translations')->first();
        if ($tag === null) {
            abort(404);
        }

        $query = $this->basePostQuery()->whereHas('tags', fn (Builder $q) => $q->where('tags.id', $tag->getKey()));
        $langUrls = $this->localeUrls($tag->getRelationValue('translations'), 'tag');
        $baseUrl = '/'.$locale.'/'.RouteSlugs::seg('tag', $locale).'/'.$slug;
        $this->renderArchive($locale, Lang::t('nav.tag_one'), (string) $tag->name, $baseUrl, $query, $this->pageNum($page), $langUrls);
    }

    /**
     * All categories with their published-post counts (taxonomy index).
     * Yayınlanmış gönderi sayılarıyla tüm kategoriler (taksonomi listesi).
     */
    public function categoriesIndex(Request $request): void
    {
        $locale = Locale::get();
        $categories = Category::query()
            ->where('status', 1)
            ->withCount(['posts' => fn (Builder $q) => $q->where('status', 1)])
            ->with('translations')
            ->orderBy('sort_order')
            ->get();

        View::render('front/categories', [
            'title' => Lang::t('nav.categories'),
            'description' => Lang::t('meta.categories_desc'),
            'locale' => $locale,
            'categories' => $categories,        ]);
    }

    /**
     * All tags with their published-post counts (taxonomy index).
     * Yayınlanmış gönderi sayılarıyla tüm etiketler (taksonomi listesi).
     */
    public function tagsIndex(Request $request): void
    {
        $locale = Locale::get();
        $tags = Tag::query()
            ->where('status', 1)
            ->withCount(['posts' => fn (Builder $q) => $q->where('status', 1)])
            ->with('translations')
            ->get();

        View::render('front/tags', [
            'title' => Lang::t('nav.tags'),
            'description' => Lang::t('meta.tags_desc'),
            'locale' => $locale,
            'tags' => $tags,        ]);
    }

    /**
     * Full-text-ish search over post translations (title + short description) in the
     * active locale, paginated. Empty query renders the bare search page.
     * Aktif dildeki yazı çevirilerinde (başlık + kısa açıklama) sayfalı arama.
     */
    public function search(Request $request, string $query = ''): void
    {
        $locale = Locale::get();
        // Prefer the clean path segment (/search/{query}); fall back to ?q= (no-JS form).
        // Temiz path segmentini tercih et (/search/{query}); ?q='e düş (JS'siz form).
        $raw = $query !== '' ? rawurldecode($query) : (is_string($qq = $request->get('q', '')) ? $qq : '');
        $q = trim($raw);

        $posts = [];
        $products = [];
        if ($q !== '') {
            $like = static function (Builder $b) use ($q, $locale): void {
                $b->where('language_slug', $locale)
                    ->where(function (Builder $w) use ($q): void {
                        $w->where('title', 'like', '%'.$q.'%')->orWhere('short_description', 'like', '%'.$q.'%');
                    });
            };

            $postQuery = $this->basePostQuery()->whereHas('translations', $like);
            $postQuery->limit(24);
            $posts = $postQuery->get();

            $productQuery = Product::query()->where('status', 1)->with(['translations', 'categories.translations']);
            $productQuery->whereHas('translations', $like);
            $productQuery->orderByDesc('id')->limit(24);
            $products = $productQuery->get();
        }

        View::render('front/search', [
            'title' => $q !== '' ? Lang::t('search.title_q', ['q' => $q]) : Lang::t('search.title'),
            'description' => $q !== '' ? Lang::t('meta.search_desc_q', ['q' => $q]) : Lang::t('meta.search_desc'),
            'locale' => $locale,
            'q' => $q,
            'posts' => $posts,
            'products' => $products,
            'categories' => $this->activeCategories(),        ]);
    }

    public function page(Request $request, string $slug): void
    {
        $locale = Locale::get();
        /** @var Page|null $page */
        $page = Page::whereTranslation('slug', $slug)->with('translations')->first();
        if ($page === null) {
            abort(404);
        }

        View::render('front/page', [
            'title' => is_string($page->title) ? $page->title : Lang::t('common.page'),
            'locale' => $locale,
            'page' => $page,
            'langUrls' => $this->localeUrls($page->getRelationValue('translations'), 'pages'),
        ]);
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    /** Base query for published posts with the relations the cards need. */
    private function basePostQuery(): Builder
    {
        $query = Post::query()->where('status', 1)->with(['translations', 'categories.translations']);
        $query->orderByDesc('id');

        return $query;
    }

    /** Active categories (for the home filter chips). */
    private function activeCategories(): mixed
    {
        return Category::query()->where('status', 1)->with('translations')->orderBy('sort_order')->get();
    }

    /**
     * Active locale slugs (default first).
     *
     * @return array<int, string>
     */
    private function activeLocales(): array
    {
        /** @var array<int, string> $slugs */
        $slugs = Language::active()->pluck('slug')->all();

        return $slugs;
    }

    /**
     * Language-switcher URL map (locale → URL for the SAME content in that language).
     * For an entity, links to its translated slug per language; when a translation is
     * missing (or no entity), links to that locale's home instead of losing context.
     * Dil değiştirici URL haritası (locale → aynı içeriğin o dildeki URL'i). Bir varlık
     * için dil başına çeviri slug'ına gider; çeviri yoksa (ya da varlık yoksa) bağlamı
     * kaybetmemek için o dilin ana sayfasına.
     *
     * @return array<string, string>
     */
    private function localeUrls(mixed $translations, string $segment): array
    {
        $bySlug = [];
        if (is_iterable($translations)) {
            foreach ($translations as $t) {
                if (! $t instanceof \Illuminate\Database\Eloquent\Model) {
                    continue;
                }
                $ls = $t->getAttribute('language_slug');
                if (is_string($ls)) {
                    $s = $t->getAttribute('slug');
                    $bySlug[$ls] = is_string($s) ? $s : null;
                }
            }
        }

        $urls = [];
        foreach ($this->activeLocales() as $loc) {
            $slug = $bySlug[$loc] ?? null;
            $urls[$loc] = (is_string($slug) && $slug !== '')
                ? '/'.$loc.'/'.RouteSlugs::seg($segment, $loc).'/'.rawurlencode($slug)
                : '/'.$loc;
        }

        return $urls;
    }

    /**
     * Offset pagination for the given page number (from a clean /page/{n} segment).
     *
     * @return array{items: mixed, page: int, lastPage: int, total: int}
     */
    private function paginate(Builder $query, int $page): array
    {
        $total = (clone $query)->count();
        $lastPage = max(1, (int) ceil($total / self::PER_PAGE));
        $page = min(max(1, $page), $lastPage);

        $items = $query->offset(($page - 1) * self::PER_PAGE)->limit(self::PER_PAGE)->get();

        return ['items' => $items, 'page' => $page, 'lastPage' => $lastPage, 'total' => $total];
    }

    /** Parse a page route segment to a positive int. */
    private function pageNum(string $page): int
    {
        return max(1, is_numeric($page) ? (int) $page : 1);
    }

    /**
     * @param  array<string, string>  $langUrls
     */
    private function renderArchive(string $locale, string $kind, string $name, string $baseUrl, Builder $query, int $page, array $langUrls = []): void
    {
        $paged = $this->paginate($query, $page);

        View::render('front/archive', [
            'title' => $name,
            'description' => $kind.': '.$name,
            'locale' => $locale,
            'kind' => $kind,
            'name' => $name,
            'posts' => $paged['items'],
            'page' => $paged['page'],
            'lastPage' => $paged['lastPage'],
            'total' => $paged['total'],
            'baseUrl' => $baseUrl,
            'langUrls' => $langUrls,
        ]);
    }
}
