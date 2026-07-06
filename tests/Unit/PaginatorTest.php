<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Paginator;
use Tests\TestCase;

/**
 * Paginator testleri.
 *
 * Sayfalama mantığı, sayfa hesaplamaları, link üretimi,
 * sınır değer kontrolleri test edilir.
 */
class PaginatorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SERVER['REQUEST_URI'] = '/users';
    }

    // ── Temel hesaplamalar ────────────────────────────────────────────────

    public function test_calculates_last_page_correctly(): void
    {
        $paginator = new Paginator(range(1, 10), 100, 10, 1);
        $this->assertSame(10, $paginator->lastPage());
    }

    public function test_calculates_last_page_with_remainder(): void
    {
        $paginator = new Paginator(range(1, 10), 105, 10, 1);
        $this->assertSame(11, $paginator->lastPage());
    }

    public function test_total_returns_correct_count(): void
    {
        $paginator = new Paginator([], 250, 15, 1);
        $this->assertSame(250, $paginator->total());
    }

    public function test_per_page_returns_correct_value(): void
    {
        $paginator = new Paginator([], 100, 20, 1);
        $this->assertSame(20, $paginator->perPage());
    }

    public function test_current_page_returns_correct_value(): void
    {
        $paginator = new Paginator([], 100, 10, 5);
        $this->assertSame(5, $paginator->currentPage());
    }

    // ── Boundary checks (sınır kontrolleri) ──────────────────────────────

    public function test_current_page_cannot_be_less_than_one(): void
    {
        $paginator = new Paginator([], 100, 10, -5);
        $this->assertSame(1, $paginator->currentPage());
    }

    public function test_per_page_cannot_be_less_than_one(): void
    {
        $paginator = new Paginator([], 100, 0, 1);
        $this->assertSame(1, $paginator->perPage());
    }

    // ── Durum kontrolleri ────────────────────────────────────────────────

    public function test_has_pages_returns_true_when_multiple_pages(): void
    {
        $paginator = new Paginator([], 100, 10, 1);
        $this->assertTrue($paginator->hasPages());
    }

    public function test_has_pages_returns_false_when_single_page(): void
    {
        $paginator = new Paginator(range(1, 5), 5, 10, 1);
        $this->assertFalse($paginator->hasPages());
    }

    public function test_has_more_pages_on_first_page(): void
    {
        $paginator = new Paginator([], 100, 10, 1);
        $this->assertTrue($paginator->hasMorePages());
    }

    public function test_has_no_more_pages_on_last_page(): void
    {
        $paginator = new Paginator([], 100, 10, 10);
        $this->assertFalse($paginator->hasMorePages());
    }

    public function test_on_first_page(): void
    {
        $paginator = new Paginator([], 100, 10, 1);
        $this->assertTrue($paginator->onFirstPage());
    }

    public function test_not_on_first_page(): void
    {
        $paginator = new Paginator([], 100, 10, 3);
        $this->assertFalse($paginator->onFirstPage());
    }

    public function test_on_last_page(): void
    {
        $paginator = new Paginator([], 100, 10, 10);
        $this->assertTrue($paginator->onLastPage());
    }

    // ── Item hesaplamaları ───────────────────────────────────────────────

    public function test_first_item_on_page_one(): void
    {
        $paginator = new Paginator([], 100, 10, 1);
        $this->assertSame(1, $paginator->firstItem());
    }

    public function test_first_item_on_page_three(): void
    {
        $paginator = new Paginator([], 100, 10, 3);
        $this->assertSame(21, $paginator->firstItem());
    }

    public function test_last_item_on_page_one(): void
    {
        $paginator = new Paginator([], 100, 10, 1);
        $this->assertSame(10, $paginator->lastItem());
    }

    public function test_last_item_on_final_page_with_remainder(): void
    {
        $paginator = new Paginator([], 95, 10, 10);
        $this->assertSame(95, $paginator->lastItem()); // son sayfada 5 kayıt
    }

    // ── isEmpty / isNotEmpty ─────────────────────────────────────────────

    public function test_is_empty_with_no_items(): void
    {
        $paginator = new Paginator([], 0, 10, 1);
        $this->assertTrue($paginator->isEmpty());
        $this->assertFalse($paginator->isNotEmpty());
    }

    public function test_is_not_empty_with_items(): void
    {
        $paginator = new Paginator([1, 2, 3], 100, 10, 1);
        $this->assertTrue($paginator->isNotEmpty());
        $this->assertFalse($paginator->isEmpty());
    }

    // ── URL üretimi ─────────────────────────────────────────────────────

    public function test_page_url_generates_correct_url(): void
    {
        $paginator = new Paginator([], 100, 10, 1);
        $url = $paginator->pageUrl(3);
        $this->assertStringContainsString('page=3', $url);
    }

    public function test_previous_page_url_returns_null_on_first_page(): void
    {
        $paginator = new Paginator([], 100, 10, 1);
        $this->assertNull($paginator->previousPageUrl());
    }

    public function test_previous_page_url_returns_url_on_other_pages(): void
    {
        $paginator = new Paginator([], 100, 10, 5);
        $url = $paginator->previousPageUrl();
        $this->assertNotNull($url);
        $this->assertStringContainsString('page=4', $url);
    }

    public function test_next_page_url_returns_url_when_has_more(): void
    {
        $paginator = new Paginator([], 100, 10, 5);
        $url = $paginator->nextPageUrl();
        $this->assertNotNull($url);
        $this->assertStringContainsString('page=6', $url);
    }

    public function test_next_page_url_returns_null_on_last_page(): void
    {
        $paginator = new Paginator([], 100, 10, 10);
        $this->assertNull($paginator->nextPageUrl());
    }

    // ── links() HTML render ─────────────────────────────────────────────

    public function test_links_returns_empty_string_for_single_page(): void
    {
        $paginator = new Paginator([1, 2], 2, 10, 1);
        $this->assertSame('', $paginator->links());
    }

    public function test_links_returns_html_for_multiple_pages(): void
    {
        $paginator = new Paginator(range(1, 10), 100, 10, 1);
        $html = $paginator->links();
        $this->assertStringContainsString('pagination', $html);
        $this->assertStringContainsString('page-item', $html);
    }

    public function test_simple_links_renders_prev_next_only(): void
    {
        $paginator = new Paginator(range(1, 10), 100, 10, 5);
        $html = $paginator->links('simple');
        $this->assertStringContainsString('Önceki', $html);
        $this->assertStringContainsString('Sonraki', $html);
    }

    // ── make factory ────────────────────────────────────────────────────

    public function test_make_creates_paginator_with_defaults(): void
    {
        $_GET['page'] = '3';
        $paginator = Paginator::make([1, 2, 3], 100, 15);
        $this->assertSame(3, $paginator->currentPage());
        $this->assertSame(100, $paginator->total());
        $this->assertSame(15, $paginator->perPage());
    }
}
