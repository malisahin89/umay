<?php

declare(strict_types=1);

namespace Core;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Sayfalama bileşeni — Eloquent paginate() sonucunu sarar.
 *
 * // Controller:
 * $users = User::paginate(15);                // Eloquent LengthAwarePaginator döner
 * View::render('users/index', ['users' => $users]);
 *
 * // View:
 * <?= paginator($users)->links() ?>           // Bootstrap 5 HTML
 * <?= paginator($users)->links('simple') ?>   // Sadece Önceki / Sonraki
 *
 * // Veya doğrudan manuel paginator:
 * $paginator = Paginator::make($items, $total, $perPage, $currentPage);
 *
 * @phpstan-consistent-constructor
 */
class Paginator
{
    private int $currentPage;

    private int $lastPage;

    private int $perPage;

    private int $total;

    private mixed $items;

    private string $path;

    private array $queryParams;

    public function __construct(mixed $items, int $total, int $perPage, int $currentPage)
    {
        $this->items = $items;
        $this->total = $total;
        $this->perPage = max(1, $perPage);
        $this->currentPage = max(1, $currentPage);
        $this->lastPage = (int) ceil($total / $this->perPage);

        // Mevcut URL path + query string (page hariç)
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        // HTML attribute context — escape path (query params already URL-encoded via http_build_query)
        // HTML attribute bağlamı — path'i escape et (query param'lar http_build_query ile zaten URL-encoded)
        $this->path = htmlspecialchars(rtrim($uri, '/') ?: '/', ENT_QUOTES, 'UTF-8');
        $query = [];
        parse_str(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_QUERY) ?? '', $query);
        unset($query['page']);
        $this->queryParams = $query;
    }

    /**
     * Eloquent LengthAwarePaginator'dan Paginator oluştur.
     */
    public static function fromEloquent(LengthAwarePaginator $paginator): static
    {
        return new static(
            $paginator->items(),
            $paginator->total(),
            $paginator->perPage(),
            $paginator->currentPage()
        );
    }

    /**
     * Manuel oluşturucu.
     */
    public static function make(mixed $items, int $total, int $perPage = 15, ?int $currentPage = null): static
    {
        $page = $currentPage ?? (int) ($_GET['page'] ?? 1);

        return new static($items, $total, $perPage, $page);
    }

    // ── Veri erişimi ──────────────────────────────────────────────────────────

    public function items(): mixed
    {
        return $this->items;
    }

    public function total(): int
    {
        return $this->total;
    }

    public function perPage(): int
    {
        return $this->perPage;
    }

    public function currentPage(): int
    {
        return $this->currentPage;
    }

    public function lastPage(): int
    {
        return $this->lastPage;
    }

    public function hasPages(): bool
    {
        return $this->lastPage > 1;
    }

    public function hasMorePages(): bool
    {
        return $this->currentPage < $this->lastPage;
    }

    public function onFirstPage(): bool
    {
        return $this->currentPage <= 1;
    }

    public function onLastPage(): bool
    {
        return $this->currentPage >= $this->lastPage;
    }

    public function isEmpty(): bool
    {
        // $items mixed — array/Countable dışı bir değerde (null, iterator…) count()
        // TypeError verirdi. $items is mixed — count() would TypeError on a
        // non-countable value (null, iterator…).
        if (is_countable($this->items)) {
            return count($this->items) === 0;
        }

        return empty($this->items);
    }

    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    public function firstItem(): int
    {
        return ($this->currentPage - 1) * $this->perPage + 1;
    }

    public function lastItem(): int
    {
        return min($this->currentPage * $this->perPage, $this->total);
    }

    public function previousPageUrl(): ?string
    {
        return $this->currentPage > 1 ? $this->pageUrl($this->currentPage - 1) : null;
    }

    public function nextPageUrl(): ?string
    {
        return $this->hasMorePages() ? $this->pageUrl($this->currentPage + 1) : null;
    }

    public function pageUrl(int $page): string
    {
        $query = $this->queryParams;
        $query['page'] = $page;

        return $this->path.'?'.http_build_query($query);
    }

    // ── HTML render ───────────────────────────────────────────────────────────

    /**
     * Sayfalama HTML'i render et.
     *
     * @param  string  $style  'bootstrap' (varsayılan) veya 'simple'
     */
    public function links(string $style = 'bootstrap'): string
    {
        if (! $this->hasPages()) {
            return '';
        }

        return $style === 'simple'
            ? $this->renderSimple()
            : $this->renderBootstrap();
    }

    /**
     * Bootstrap 5 sayfalama bileşeni.
     * Orta sayfalar için sliding window (max 7 buton görünür).
     */
    private function renderBootstrap(): string
    {
        $html = '<nav aria-label="Sayfalama"><ul class="pagination justify-content-center flex-wrap">';

        // Önceki
        if ($this->onFirstPage()) {
            $html .= '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
        } else {
            $html .= '<li class="page-item"><a class="page-link" href="'.$this->previousPageUrl().'">&laquo;</a></li>';
        }

        // Sayfa numaraları — sliding window
        foreach ($this->getPageRange() as $page) {
            if ($page === '...') {
                $html .= '<li class="page-item disabled"><span class="page-link">&hellip;</span></li>';
            } elseif ($page === $this->currentPage) {
                $html .= '<li class="page-item active" aria-current="page"><span class="page-link">'.$page.'</span></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link" href="'.$this->pageUrl($page).'">'.$page.'</a></li>';
            }
        }

        // Sonraki
        if ($this->onLastPage()) {
            $html .= '<li class="page-item disabled"><span class="page-link">&raquo;</span></li>';
        } else {
            $html .= '<li class="page-item"><a class="page-link" href="'.$this->nextPageUrl().'">&raquo;</a></li>';
        }

        $html .= '</ul></nav>';

        // Bilgi satırı
        $html .= '<p class="text-center text-muted small mt-1">';
        $html .= $this->total.' kayıttan '.$this->firstItem().'-'.$this->lastItem().' arası gösteriliyor';
        $html .= '</p>';

        return $html;
    }

    /** Sadece Önceki / Sonraki butonları */
    private function renderSimple(): string
    {
        $html = '<nav aria-label="Sayfalama"><ul class="pagination justify-content-center">';

        if ($this->onFirstPage()) {
            $html .= '<li class="page-item disabled"><span class="page-link">&laquo; Önceki</span></li>';
        } else {
            $html .= '<li class="page-item"><a class="page-link" href="'.$this->previousPageUrl().'">&laquo; Önceki</a></li>';
        }

        if ($this->onLastPage()) {
            $html .= '<li class="page-item disabled"><span class="page-link">Sonraki &raquo;</span></li>';
        } else {
            $html .= '<li class="page-item"><a class="page-link" href="'.$this->nextPageUrl().'">Sonraki &raquo;</a></li>';
        }

        $html .= '</ul></nav>';

        return $html;
    }

    /**
     * Sliding window sayfa aralığı hesapla.
     * Her zaman ilk, son ve mevcut ±2 sayfayı gösterir, araya '...' koyar.
     */
    private function getPageRange(): array
    {
        $last = $this->lastPage;
        $current = $this->currentPage;
        $pages = [];

        if ($last <= 7) {
            return range(1, $last);
        }

        // Her zaman 1 göster
        $pages[] = 1;

        $start = max(2, $current - 2);
        $end = min($last - 1, $current + 2);

        if ($start > 2) {
            $pages[] = '...';
        }

        for ($i = $start; $i <= $end; $i++) {
            $pages[] = $i;
        }

        if ($end < $last - 1) {
            $pages[] = '...';
        }

        // Her zaman last göster
        $pages[] = $last;

        return $pages;
    }
}
