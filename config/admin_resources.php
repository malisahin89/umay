<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Popup;
use App\Models\Post;
use App\Models\Product;
use App\Models\Slide;
use App\Models\Tag;

/**
 * Admin resource definitions — drives the generic Admin\ResourceController.
 * Admin kaynak tanımları — generic Admin\ResourceController'ı besler.
 *
 * Each resource:
 *   label        — menu/heading text
 *   model        — base Eloquent model (uses HasTranslations)
 *   title_field  — translatable field shown in the listing
 *   owner_field  — (optional) FK auto-set to the current admin on create
 *   fields       — language-NEUTRAL columns (base table)
 *   translatable — language-SPECIFIC columns (per-locale tabs)
 *
 * field type: text | textarea | number | select | toggle | datetime | image | gallery | richtext | keyvalue | list | json
 *   select   → 'options' => [value => label]  OR  'options_model' => Model::class
 *              (+ 'options_title', 'nullable') for an FK dropdown loaded from another table
 *   image    → single file upload → WebP;  gallery → multiple upload → WebP array
 *   richtext → Summernote WYSIWYG (translatable);  toggle → boolean switch
 *   keyvalue → structured key→value editor (JSON object);  list → string rows (JSON array)
 *
 * Every column from the migrations is exposed here (except auto-managed
 * id/timestamps and the owner FK, which is set automatically).
 * Migration'lardaki her sütun burada var (otomatik yönetilen id/timestamps ve
 * otomatik atanan owner FK hariç).
 */
return [
    'posts' => [
        'label' => 'Yazılar',
        'model' => Post::class,
        'title_field' => 'title',
        'owner_field' => 'user_id',
        'fields' => [
            'status' => ['type' => 'select', 'label' => 'Durum', 'options' => [0 => 'Taslak', 1 => 'Yayında', 2 => 'Arşiv']],
            'order' => ['type' => 'number', 'label' => 'Sıra'],
            'view_count' => ['type' => 'number', 'label' => 'Görüntülenme'],
            'cover_image' => ['type' => 'image', 'label' => 'Kapak görseli'],
            'gallery_images' => ['type' => 'gallery', 'label' => 'Galeri görselleri'],
            'published_at' => ['type' => 'datetime', 'label' => 'Yayın tarihi'],
            'is_featured' => ['type' => 'toggle', 'label' => 'Öne çıkan'],
            'comment_enabled' => ['type' => 'toggle', 'label' => 'Yorumlar açık'],
        ],
        'translatable' => [
            'title' => ['type' => 'text', 'label' => 'Başlık'],
            'slug' => ['type' => 'text', 'label' => 'Slug'],
            'short_description' => ['type' => 'textarea', 'label' => 'Kısa açıklama'],
            'content' => ['type' => 'richtext', 'label' => 'İçerik'],
            'seo_title' => ['type' => 'text', 'label' => 'SEO başlık'],
            'seo_description' => ['type' => 'textarea', 'label' => 'SEO açıklama'],
            'seo_keywords' => ['type' => 'text', 'label' => 'SEO anahtar kelimeler'],
        ],
        // Many-to-many relations (pivot tables). Key = relation method on the model.
        // Çoktan-çoğa ilişkiler (pivot tablolar). Anahtar = modeldeki ilişki metodu.
        'relations' => [
            'categories' => ['label' => 'Kategoriler', 'model' => Category::class, 'title_field' => 'name'],
            'tags' => ['label' => 'Etiketler', 'model' => Tag::class, 'title_field' => 'name'],
        ],
    ],

    'pages' => [
        'label' => 'Sayfalar',
        'model' => Page::class,
        'title_field' => 'title',
        'fields' => [
            'template' => ['type' => 'text', 'label' => 'Şablon'],
            'status' => ['type' => 'select', 'label' => 'Durum', 'options' => [0 => 'Pasif', 1 => 'Aktif']],
            'sort_order' => ['type' => 'number', 'label' => 'Sıra'],
        ],
        'translatable' => [
            'title' => ['type' => 'text', 'label' => 'Başlık'],
            'slug' => ['type' => 'text', 'label' => 'Slug'],
            'content' => ['type' => 'richtext', 'label' => 'İçerik'],
            'blocks' => ['type' => 'keyvalue', 'label' => 'Bloklar (ad → içerik)'],
            'seo_title' => ['type' => 'text', 'label' => 'SEO başlık'],
            'seo_description' => ['type' => 'textarea', 'label' => 'SEO açıklama'],
            'seo_keywords' => ['type' => 'text', 'label' => 'SEO anahtar kelimeler'],
        ],
    ],

    'categories' => [
        'label' => 'Kategoriler',
        'model' => Category::class,
        'title_field' => 'name',
        'fields' => [
            'parent_id' => ['type' => 'select', 'label' => 'Üst kategori', 'options_model' => Category::class, 'options_title' => 'name', 'nullable' => true],
            'level' => ['type' => 'number', 'label' => 'Seviye'],
            'path' => ['type' => 'text', 'label' => 'Yol (örn. /1/5/12)'],
            'color' => ['type' => 'text', 'label' => 'Renk (#hex)'],
            'icon' => ['type' => 'text', 'label' => 'İkon'],
            'status' => ['type' => 'select', 'label' => 'Durum', 'options' => [0 => 'Pasif', 1 => 'Aktif']],
            'nav_order' => ['type' => 'number', 'label' => 'Menü sırası'],
            'sort_order' => ['type' => 'number', 'label' => 'Sıra'],
            'show_in_nav' => ['type' => 'toggle', 'label' => 'Menüde göster'],
        ],
        'translatable' => [
            'name' => ['type' => 'text', 'label' => 'Ad'],
            'slug' => ['type' => 'text', 'label' => 'Slug'],
            'seo_title' => ['type' => 'text', 'label' => 'SEO başlık'],
            'seo_description' => ['type' => 'textarea', 'label' => 'SEO açıklama'],
            'seo_keywords' => ['type' => 'text', 'label' => 'SEO anahtar kelimeler'],
        ],
    ],

    'tags' => [
        'label' => 'Etiketler',
        'model' => Tag::class,
        'title_field' => 'name',
        'fields' => [
            'color' => ['type' => 'text', 'label' => 'Renk (#hex)'],
            'status' => ['type' => 'select', 'label' => 'Durum', 'options' => [0 => 'Pasif', 1 => 'Aktif']],
            'usage_count' => ['type' => 'number', 'label' => 'Kullanım sayısı'],
        ],
        'translatable' => [
            'name' => ['type' => 'text', 'label' => 'Ad'],
            'slug' => ['type' => 'text', 'label' => 'Slug'],
            'description' => ['type' => 'textarea', 'label' => 'Açıklama'],
            'seo_title' => ['type' => 'text', 'label' => 'SEO başlık'],
            'seo_description' => ['type' => 'textarea', 'label' => 'SEO açıklama'],
            'seo_keywords' => ['type' => 'text', 'label' => 'SEO anahtar kelimeler'],
        ],
    ],

    'products' => [
        'label' => 'Ürünler',
        'model' => Product::class,
        'title_field' => 'title',
        'fields' => [
            'order' => ['type' => 'number', 'label' => 'Sıra'],
            'status' => ['type' => 'select', 'label' => 'Durum', 'options' => [0 => 'Taslak', 1 => 'Yayında', 2 => 'Arşiv']],
            'brand' => ['type' => 'text', 'label' => 'Marka'],
            'price' => ['type' => 'number', 'label' => 'Fiyat'],
            'model' => ['type' => 'text', 'label' => 'Model'],
            'type' => ['type' => 'text', 'label' => 'Tip'],
            'fuel_type' => ['type' => 'text', 'label' => 'Yakıt tipi'],
            'heating_type' => ['type' => 'text', 'label' => 'Isıtma tipi'],
            'product_url' => ['type' => 'text', 'label' => 'Ürün URL'],
            'cover_image' => ['type' => 'image', 'label' => 'Kapak görseli'],
            'gallery_images' => ['type' => 'gallery', 'label' => 'Galeri görselleri'],
            'published_at' => ['type' => 'datetime', 'label' => 'Yayın tarihi'],
            'is_featured' => ['type' => 'toggle', 'label' => 'Öne çıkan'],
        ],
        'translatable' => [
            'title' => ['type' => 'text', 'label' => 'Başlık'],
            'slug' => ['type' => 'text', 'label' => 'Slug'],
            'short_description' => ['type' => 'textarea', 'label' => 'Kısa açıklama'],
            'content' => ['type' => 'richtext', 'label' => 'İçerik'],
            'specifications' => ['type' => 'keyvalue', 'label' => 'Teknik özellikler'],
            'features' => ['type' => 'textarea', 'label' => 'Özellikler'],
            'attributes' => ['type' => 'textarea', 'label' => 'Nitelikler'],
            'documents' => ['type' => 'textarea', 'label' => 'Belgeler'],
            'seo_title' => ['type' => 'text', 'label' => 'SEO başlık'],
            'seo_description' => ['type' => 'textarea', 'label' => 'SEO açıklama'],
            'seo_keywords' => ['type' => 'text', 'label' => 'SEO anahtar kelimeler'],
        ],
        'relations' => [
            'categories' => ['label' => 'Kategoriler', 'model' => Category::class, 'title_field' => 'name'],
            'tags' => ['label' => 'Etiketler', 'model' => Tag::class, 'title_field' => 'name'],
        ],
    ],

    'slides' => [
        'label' => 'Slaytlar',
        'model' => Slide::class,
        'title_field' => 'title',
        'fields' => [
            'type' => ['type' => 'select', 'label' => 'Tip', 'options' => ['image' => 'Görsel', 'video' => 'Video']],
            'media_file' => ['type' => 'image', 'label' => 'Görsel (slayt medyası)'],
            'text_position' => ['type' => 'select', 'label' => 'Metin konumu', 'options' => ['left' => 'Sol', 'right' => 'Sağ']],
            'label_size' => ['type' => 'number', 'label' => 'Etiket boyutu'],
            'title_size' => ['type' => 'number', 'label' => 'Başlık boyutu'],
            'subtitle_size' => ['type' => 'number', 'label' => 'Alt başlık boyutu'],
            'order' => ['type' => 'number', 'label' => 'Sıra'],
            'status' => ['type' => 'select', 'label' => 'Durum', 'options' => [0 => 'Pasif', 1 => 'Aktif']],
        ],
        'translatable' => [
            'label' => ['type' => 'text', 'label' => 'Etiket'],
            'title' => ['type' => 'text', 'label' => 'Başlık'],
            'subtitle' => ['type' => 'textarea', 'label' => 'Alt başlık'],
            'button_text' => ['type' => 'text', 'label' => 'Buton metni'],
            'button_url' => ['type' => 'text', 'label' => 'Buton URL'],
        ],
    ],

    'popups' => [
        'label' => 'Popuplar',
        'model' => Popup::class,
        'title_field' => 'title',
        'fields' => [
            'start_date' => ['type' => 'datetime', 'label' => 'Başlangıç tarihi'],
            'end_date' => ['type' => 'datetime', 'label' => 'Bitiş tarihi'],
            'display_frequency' => ['type' => 'select', 'label' => 'Gösterim sıklığı', 'options' => ['once' => 'Bir kez', 'session' => 'Oturum başına', 'always' => 'Her zaman']],
            'target_routes' => ['type' => 'list', 'label' => 'Hedef route\'lar'],
            'is_annual' => ['type' => 'toggle', 'label' => 'Yıllık'],
            'is_active' => ['type' => 'toggle', 'label' => 'Aktif'],
        ],
        'translatable' => [
            'title' => ['type' => 'text', 'label' => 'Başlık'],
            'content' => ['type' => 'richtext', 'label' => 'İçerik'],
            'image' => ['type' => 'text', 'label' => 'Görsel (yol)'],
            'button_text' => ['type' => 'text', 'label' => 'Buton metni'],
            'button_url' => ['type' => 'text', 'label' => 'Buton URL'],
        ],
    ],

    'menu-items' => [
        'label' => 'Menü Öğeleri',
        'model' => MenuItem::class,
        'title_field' => 'label',
        'fields' => [
            'menu_id' => ['type' => 'select', 'label' => 'Menü', 'options_model' => Menu::class, 'options_title' => 'name'],
            'parent_id' => ['type' => 'select', 'label' => 'Üst menü öğesi', 'options_model' => MenuItem::class, 'options_title' => 'label', 'nullable' => true],
            'type' => ['type' => 'select', 'label' => 'Tip', 'options' => ['route' => 'Route', 'url' => 'URL', 'dynamic_categories' => 'Dinamik kategoriler']],
            'route_name' => ['type' => 'text', 'label' => 'Route adı'],
            'route_param' => ['type' => 'text', 'label' => 'Route parametresi'],
            'url' => ['type' => 'text', 'label' => 'URL'],
            'target' => ['type' => 'select', 'label' => 'Hedef', 'options' => ['_self' => 'Aynı sekme', '_blank' => 'Yeni sekme']],
            'order' => ['type' => 'number', 'label' => 'Sıra'],
            'is_active' => ['type' => 'toggle', 'label' => 'Aktif'],
        ],
        'translatable' => [
            'label' => ['type' => 'text', 'label' => 'Etiket'],
        ],
    ],
];
