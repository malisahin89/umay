# Dosya Raporu: tests/Unit/PaginatorTest.php

## Amaç
Sayfalandırıcı (paginator) için birim (unit) testler.

## Genel Bakış
`Core\Paginator`'ı doğrular: son sayfa hesaplaması (kalanlı ve kalansız), alt sınır sınırlamalı erişimciler (`total`, `perPage`, `currentPage`), sayfa durumu belirteçleri (`hasPages`, `hasMorePages`, `onFirstPage`, `onLastPage`), öğe aralığı hesaplaması, boşluk kontrolleri, URL oluşturma (`pageUrl`, önceki/sonraki), HTML oluşturma (`links`, `simpleLinks`) ve `make` fabrika varsayılanları.

## Dosya Konumu
`tests/Unit/PaginatorTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class PaginatorTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\Paginator`

## Test Metotları
- `test_calculates_last_page_correctly` — `:26`
- `test_calculates_last_page_with_remainder` — `:32`
- `test_total_returns_correct_count` — `:38`
- `test_per_page_returns_correct_value` — `:44`
- `test_current_page_returns_correct_value` — `:50`
- `test_current_page_cannot_be_less_than_one` — `:58`
- `test_per_page_cannot_be_less_than_one` — `:64`
- `test_has_pages_returns_true_when_multiple_pages` — `:72`
- `test_has_pages_returns_false_when_single_page` — `:78`
- `test_has_more_pages_on_first_page` — `:84`
- `test_has_no_more_pages_on_last_page` — `:90`
- `test_on_first_page` — `:96`
- `test_not_on_first_page` — `:102`
- `test_on_last_page` — `:108`
- `test_first_item_on_page_one` — `:116`
- `test_first_item_on_page_three` — `:122`
- `test_last_item_on_page_one` — `:128`
- `test_last_item_on_final_page_with_remainder` — `:134`
- `test_is_empty_with_no_items` — `:142`
- `test_is_not_empty_with_items` — `:149`
- `test_page_url_generates_correct_url` — `:158`
- `test_previous_page_url_returns_null_on_first_page` — `:165`
- `test_previous_page_url_returns_url_on_other_pages` — `:171`
- `test_next_page_url_returns_url_when_has_more` — `:179`
- `test_next_page_url_returns_null_on_last_page` — `:187`
- `test_links_returns_empty_string_for_single_page` — `:195`
- `test_links_returns_html_for_multiple_pages` — `:201`
- `test_simple_links_renders_prev_next_only` — `:209`
- `test_make_creates_paginator_with_defaults` — `:219`

## Çapraz Referanslar
- **Test Eder:** `Core\Paginator` (bkz. `DOCS/core/Paginator.md`)

## Kaynak Referansları
- `tests/Unit/PaginatorTest.php:1-227`
