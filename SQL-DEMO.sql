-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 06 Tem 2026, 16:42:40
-- Sunucu sürümü: 8.0.30
-- PHP Sürümü: 8.3.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `umay`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `level` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `path` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `show_in_nav` tinyint(1) NOT NULL DEFAULT '0',
  `nav_order` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `level`, `path`, `color`, `icon`, `status`, `show_in_nav`, `nav_order`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, NULL, 0, NULL, NULL, NULL, 1, 1, 1, 1, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(2, NULL, 0, NULL, NULL, NULL, 1, 1, 2, 2, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(3, NULL, 0, NULL, NULL, NULL, 1, 1, 3, 3, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(4, NULL, 0, NULL, NULL, NULL, 1, 1, 4, 4, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(5, NULL, 0, NULL, NULL, NULL, 1, 1, 5, 5, '2026-07-06 07:46:35', '2026-07-06 07:46:35');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `category_translations`
--

CREATE TABLE `category_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `language_slug` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seo_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_keywords` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `category_translations`
--

INSERT INTO `category_translations` (`id`, `category_id`, `language_slug`, `name`, `slug`, `seo_title`, `seo_description`, `seo_keywords`, `created_at`, `updated_at`) VALUES
(1, 1, 'tr', 'Teknoloji', 'teknoloji', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(2, 1, 'en', 'Technology', 'technology', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(3, 1, 'de', 'Technologie', 'technologie', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(4, 1, 'fr', 'Technologie', 'technologie', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(5, 1, 'es', 'Tecnología', 'tecnologia', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(6, 2, 'tr', 'Yazılım', 'yazilim', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(7, 2, 'en', 'Software', 'software', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(8, 2, 'de', 'Software', 'software', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(9, 2, 'fr', 'Logiciel', 'logiciel', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(10, 2, 'es', 'Software', 'software', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(11, 3, 'tr', 'Donanım', 'donanim', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(12, 3, 'en', 'Hardware', 'hardware', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(13, 3, 'de', 'Hardware', 'hardware', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(14, 3, 'fr', 'Matériel', 'materiel', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(15, 3, 'es', 'Hardware', 'hardware', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(16, 4, 'tr', 'Tasarım', 'tasarim', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(17, 4, 'en', 'Design', 'design', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(18, 4, 'de', 'Design', 'design', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(19, 4, 'fr', 'Design', 'design', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(20, 4, 'es', 'Diseño', 'diseno', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(21, 5, 'tr', 'Haberler', 'haberler', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(22, 5, 'en', 'News', 'news', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(23, 5, 'de', 'Nachrichten', 'nachrichten', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(24, 5, 'fr', 'Actualités', 'actualites', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(25, 5, 'es', 'Noticias', 'noticias', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `languages`
--

CREATE TABLE `languages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `flag` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_default_flag` tinyint GENERATED ALWAYS AS ((case when (`is_default` = 1) then 1 else NULL end)) VIRTUAL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `languages`
--

INSERT INTO `languages` (`id`, `name`, `slug`, `flag`, `status`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'Türkçe', 'tr', '🇹🇷', 1, 1, NULL, NULL),
(2, 'English', 'en', '🇬🇧', 1, 0, NULL, NULL),
(3, 'Deutsch', 'de', 'de', 1, 0, '2026-07-04 12:13:02', '2026-07-04 12:16:35'),
(4, 'Français', 'fr', 'fr', 1, 0, '2026-07-04 12:13:02', '2026-07-04 12:16:35'),
(5, 'Español', 'es', 'es', 1, 0, '2026-07-04 12:13:02', '2026-07-04 12:16:36');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `menus`
--

CREATE TABLE `menus` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `menus`
--

INSERT INTO `menus` (`id`, `key`, `name`, `created_at`, `updated_at`) VALUES
(1, 'header', 'Header', '2026-07-04 12:12:39', '2026-07-04 12:12:39');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `menu_items`
--

CREATE TABLE `menu_items` (
  `id` bigint UNSIGNED NOT NULL,
  `menu_id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'route',
  `route_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `route_param` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `menu_items`
--

INSERT INTO `menu_items` (`id`, `menu_id`, `parent_id`, `type`, `route_name`, `route_param`, `url`, `target`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'url', NULL, NULL, 'C:/laragon/bin/git/tr/sayfa-1', '_self', 1, 1, '2026-07-04 12:14:51', '2026-07-04 12:14:51'),
(2, 1, NULL, 'url', NULL, NULL, 'C:/laragon/bin/git/tr/sayfa-2', '_self', 2, 1, '2026-07-04 12:14:51', '2026-07-04 12:14:51'),
(3, 1, NULL, 'url', NULL, NULL, 'C:/laragon/bin/git/tr/sayfa-3', '_self', 3, 1, '2026-07-04 12:14:52', '2026-07-04 12:14:52'),
(4, 1, NULL, 'url', NULL, NULL, 'C:/laragon/bin/git/tr/sayfa-4', '_self', 4, 1, '2026-07-04 12:14:52', '2026-07-04 12:14:52'),
(5, 1, NULL, 'url', NULL, NULL, 'C:/laragon/bin/git/tr/sayfa-5', '_self', 5, 1, '2026-07-04 12:14:52', '2026-07-04 12:14:52');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `menu_item_translations`
--

CREATE TABLE `menu_item_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `menu_item_id` bigint UNSIGNED NOT NULL,
  `language_slug` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `menu_item_translations`
--

INSERT INTO `menu_item_translations` (`id`, `menu_item_id`, `language_slug`, `label`, `created_at`, `updated_at`) VALUES
(1, 1, 'tr', 'Menu 1', '2026-07-04 12:14:51', '2026-07-04 12:14:51'),
(2, 2, 'tr', 'Menu 2', '2026-07-04 12:14:51', '2026-07-04 12:14:51'),
(3, 3, 'tr', 'Menu 3', '2026-07-04 12:14:52', '2026-07-04 12:14:52'),
(4, 4, 'tr', 'Menu 4', '2026-07-04 12:14:52', '2026-07-04 12:14:52'),
(5, 5, 'tr', 'Menu 5', '2026-07-04 12:14:52', '2026-07-04 12:14:52'),
(6, 5, 'en', 'Menu 5 (EN)', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(7, 4, 'en', 'Menu 4 (EN)', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(8, 3, 'en', 'Menu 3 (EN)', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(9, 2, 'en', 'Menu 2 (EN)', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(10, 1, 'en', 'Menu 1 (EN)', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(11, 5, 'de', 'Menu 5 (DE)', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(12, 4, 'de', 'Menu 4 (DE)', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(13, 3, 'de', 'Menu 3 (DE)', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(14, 2, 'de', 'Menu 2 (DE)', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(15, 1, 'de', 'Menu 1 (DE)', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(16, 5, 'fr', 'Menu 5 (FR)', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(17, 4, 'fr', 'Menu 4 (FR)', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(18, 3, 'fr', 'Menu 3 (FR)', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(19, 2, 'fr', 'Menu 2 (FR)', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(20, 1, 'fr', 'Menu 1 (FR)', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(21, 5, 'es', 'Menu 5 (ES)', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(22, 4, 'es', 'Menu 4 (ES)', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(23, 3, 'es', 'Menu 3 (ES)', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(24, 2, 'es', 'Menu 2 (ES)', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(25, 1, 'es', 'Menu 1 (ES)', '2026-07-04 12:39:59', '2026-07-04 12:39:59');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  `executed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`, `executed_at`) VALUES
(1, '2026_02_28_000001_create_users_table', 1, '2026-07-02 23:38:24'),
(2, '2026_06_30_000001_create_personal_access_tokens_table', 1, '2026-07-02 23:38:24'),
(3, '2026_07_03_000002_create_permissions_tables', 1, '2026-07-02 23:38:25'),
(4, '2026_07_10_000001_create_languages_table', 1, '2026-07-02 23:38:25'),
(5, '2026_07_10_000002_create_categories_table', 1, '2026-07-02 23:38:25'),
(6, '2026_07_10_000003_create_posts_table', 1, '2026-07-02 23:38:25'),
(7, '2026_07_10_000004_create_post_translations_table', 1, '2026-07-02 23:38:25'),
(8, '2026_07_10_000005_create_category_translations_table', 1, '2026-07-02 23:38:25'),
(9, '2026_07_10_000006_create_tags_table', 1, '2026-07-02 23:38:26'),
(10, '2026_07_10_000007_create_tag_translations_table', 1, '2026-07-02 23:38:26'),
(11, '2026_07_10_000008_create_pages_table', 1, '2026-07-02 23:38:26'),
(12, '2026_07_10_000009_create_page_translations_table', 1, '2026-07-02 23:38:26'),
(13, '2026_07_10_000010_create_products_table', 1, '2026-07-02 23:38:26'),
(14, '2026_07_10_000011_create_product_translations_table', 1, '2026-07-02 23:38:26'),
(15, '2026_07_10_000012_create_slides_table', 1, '2026-07-02 23:38:26'),
(16, '2026_07_10_000013_create_slide_translations_table', 1, '2026-07-02 23:38:27'),
(17, '2026_07_10_000014_create_popups_table', 1, '2026-07-02 23:38:27'),
(18, '2026_07_10_000015_create_popup_translations_table', 1, '2026-07-02 23:38:27'),
(19, '2026_07_10_000016_create_menus_table', 1, '2026-07-02 23:38:27'),
(20, '2026_07_10_000017_create_menu_items_table', 1, '2026-07-02 23:38:27'),
(21, '2026_07_10_000018_create_menu_item_translations_table', 1, '2026-07-02 23:38:27'),
(22, '2026_07_10_000019_create_post_category_table', 2, '2026-07-04 01:01:32'),
(23, '2026_07_10_000020_create_post_tag_table', 2, '2026-07-04 01:01:32'),
(24, '2026_07_10_000021_create_product_category_table', 2, '2026-07-04 01:01:32'),
(25, '2026_07_10_000022_create_product_tag_table', 2, '2026-07-04 01:01:32');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pages`
--

CREATE TABLE `pages` (
  `id` bigint UNSIGNED NOT NULL,
  `template` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `pages`
--

INSERT INTO `pages` (`id`, `template`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'default', 1, 1, '2026-07-04 12:14:48', '2026-07-04 12:14:48'),
(2, 'default', 1, 2, '2026-07-04 12:14:48', '2026-07-04 12:14:48'),
(3, 'default', 1, 3, '2026-07-04 12:14:48', '2026-07-04 12:14:48'),
(4, 'default', 1, 4, '2026-07-04 12:14:49', '2026-07-04 12:14:49'),
(5, 'default', 1, 5, '2026-07-04 12:14:49', '2026-07-04 12:14:49');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `page_translations`
--

CREATE TABLE `page_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `page_id` bigint UNSIGNED NOT NULL,
  `language_slug` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `blocks` json DEFAULT NULL,
  `seo_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_keywords` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `page_translations`
--

INSERT INTO `page_translations` (`id`, `page_id`, `language_slug`, `title`, `slug`, `content`, `blocks`, `seo_title`, `seo_description`, `seo_keywords`, `created_at`, `updated_at`) VALUES
(1, 1, 'tr', 'Sayfa 1', 'sayfa-1', '<p>Sayfa 1 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:14:48', '2026-07-04 12:14:48'),
(2, 2, 'tr', 'Sayfa 2', 'sayfa-2', '<p>Sayfa 2 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:14:48', '2026-07-04 12:14:48'),
(3, 3, 'tr', 'Sayfa 3', 'sayfa-3', '<p>Sayfa 3 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:14:48', '2026-07-04 12:14:48'),
(4, 4, 'tr', 'Sayfa 4', 'sayfa-4', '<p>Sayfa 4 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:14:49', '2026-07-04 12:14:49'),
(5, 5, 'tr', 'Sayfa 5', 'sayfa-5', '<p>Sayfa 5 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:14:49', '2026-07-04 12:14:49'),
(6, 5, 'en', 'Sayfa 5 (EN)', 'sayfa-5-en', '<p>Sayfa 5 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(7, 4, 'en', 'Sayfa 4 (EN)', 'sayfa-4-en', '<p>Sayfa 4 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(8, 3, 'en', 'Sayfa 3 (EN)', 'sayfa-3-en', '<p>Sayfa 3 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(9, 2, 'en', 'Sayfa 2 (EN)', 'sayfa-2-en', '<p>Sayfa 2 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(10, 1, 'en', 'Sayfa 1 (EN)', 'sayfa-1-en', '<p>Sayfa 1 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(11, 5, 'de', 'Sayfa 5 (DE)', 'sayfa-5-de', '<p>Sayfa 5 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(12, 4, 'de', 'Sayfa 4 (DE)', 'sayfa-4-de', '<p>Sayfa 4 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(13, 3, 'de', 'Sayfa 3 (DE)', 'sayfa-3-de', '<p>Sayfa 3 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(14, 2, 'de', 'Sayfa 2 (DE)', 'sayfa-2-de', '<p>Sayfa 2 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(15, 1, 'de', 'Sayfa 1 (DE)', 'sayfa-1-de', '<p>Sayfa 1 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(16, 5, 'fr', 'Sayfa 5 (FR)', 'sayfa-5-fr', '<p>Sayfa 5 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(17, 4, 'fr', 'Sayfa 4 (FR)', 'sayfa-4-fr', '<p>Sayfa 4 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(18, 3, 'fr', 'Sayfa 3 (FR)', 'sayfa-3-fr', '<p>Sayfa 3 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(19, 2, 'fr', 'Sayfa 2 (FR)', 'sayfa-2-fr', '<p>Sayfa 2 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(20, 1, 'fr', 'Sayfa 1 (FR)', 'sayfa-1-fr', '<p>Sayfa 1 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(21, 5, 'es', 'Sayfa 5 (ES)', 'sayfa-5-es', '<p>Sayfa 5 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(22, 4, 'es', 'Sayfa 4 (ES)', 'sayfa-4-es', '<p>Sayfa 4 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(23, 3, 'es', 'Sayfa 3 (ES)', 'sayfa-3-es', '<p>Sayfa 3 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(24, 2, 'es', 'Sayfa 2 (ES)', 'sayfa-2-es', '<p>Sayfa 2 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(25, 1, 'es', 'Sayfa 1 (ES)', 'sayfa-1-es', '<p>Sayfa 1 icerigi.</p>', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `permissions`
--

CREATE TABLE `permissions` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `label`, `created_at`) VALUES
(1, 'posts.view', 'View posts // Postları görüntüle', NULL),
(2, 'posts.create', 'Create post // Post oluştur', NULL),
(3, 'posts.edit', 'Edit post // Post düzenle', NULL),
(4, 'posts.delete', 'Delete post // Post sil', NULL),
(5, 'posts.publish', 'Publish post // Post yayınla', NULL),
(6, 'users.view', 'View users // Kullanıcıları görüntüle', NULL),
(7, 'users.create', 'Create user // Kullanıcı oluştur', NULL),
(8, 'users.edit', 'Edit user // Kullanıcı düzenle', NULL),
(9, 'users.delete', 'Delete user // Kullanıcı sil', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` int UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `popups`
--

CREATE TABLE `popups` (
  `id` bigint UNSIGNED NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `is_annual` tinyint(1) NOT NULL DEFAULT '0',
  `display_frequency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'show_always',
  `target_routes` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `popups`
--

INSERT INTO `popups` (`id`, `start_date`, `end_date`, `is_annual`, `display_frequency`, `target_routes`, `is_active`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 0, 'session', '[]', 1, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(2, NULL, NULL, 0, 'session', '[]', 1, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(3, NULL, NULL, 0, 'session', '[]', 1, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(4, NULL, NULL, 0, 'session', '[]', 1, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(5, NULL, NULL, 0, 'session', '[]', 1, '2026-07-06 07:46:35', '2026-07-06 07:46:35');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `popup_translations`
--

CREATE TABLE `popup_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `popup_id` bigint UNSIGNED NOT NULL,
  `language_slug` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `popup_translations`
--

INSERT INTO `popup_translations` (`id`, `popup_id`, `language_slug`, `title`, `content`, `image`, `button_text`, `button_url`, `created_at`, `updated_at`) VALUES
(1, 1, 'tr', 'PHP 8.3 ile Gelen Yenilikler', '<p>PHP 8.3 sürümüyle gelen yeni özellikleri, performans iyileştirmelerini ve dikkat edilmesi gerekenleri inceliyoruz.</p>', NULL, 'Yazıyı Oku', '/tr/yazi/php-8-3-ile-gelen-yenilikler', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(2, 1, 'en', 'What\'s New in PHP 8.3', '<p>We look at the new features, performance improvements and gotchas that arrived with PHP 8.3.</p>', NULL, 'Read Article', '/en/posts/whats-new-in-php-8-3', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(3, 1, 'de', 'Was ist neu in PHP 8.3', '<p>Wir werfen einen Blick auf die neuen Funktionen, Performance-Verbesserungen und Fallstricke von PHP 8.3.</p>', NULL, 'Beitrag lesen', '/de/beitrag/was-ist-neu-in-php-8-3', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(4, 1, 'fr', 'Les nouveautés de PHP 8.3', '<p>Nous passons en revue les nouveautés, les améliorations de performances et les pièges de PHP 8.3.</p>', NULL, 'Lire l\'article', '/fr/article/les-nouveautes-de-php-8-3', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(5, 1, 'es', 'Novedades de PHP 8.3', '<p>Repasamos las nuevas funciones, las mejoras de rendimiento y los detalles a tener en cuenta de PHP 8.3.</p>', NULL, 'Leer artículo', '/es/entrada/novedades-de-php-8-3', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(6, 2, 'tr', 'Modern CSS ile Responsive Tasarım', '<p>Grid, Flexbox ve container query gibi modern CSS araçlarıyla her ekrana uyum sağlayan arayüzler kurmayı anlatıyoruz.</p>', NULL, 'Yazıyı Oku', '/tr/yazi/modern-css-ile-responsive-tasarim', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(7, 2, 'en', 'Responsive Design with Modern CSS', '<p>We show how to build interfaces that adapt to any screen using modern CSS tools like Grid, Flexbox and container queries.</p>', NULL, 'Read Article', '/en/posts/responsive-design-with-modern-css', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(8, 2, 'de', 'Responsives Design mit modernem CSS', '<p>Wir zeigen, wie man mit modernen CSS-Werkzeugen wie Grid, Flexbox und Container Queries Oberflächen für jeden Bildschirm baut.</p>', NULL, 'Beitrag lesen', '/de/beitrag/responsives-design-mit-modernem-css', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(9, 2, 'fr', 'Design responsive avec le CSS moderne', '<p>Nous montrons comment créer des interfaces qui s\'adaptent à tous les écrans avec les outils CSS modernes : Grid, Flexbox et container queries.</p>', NULL, 'Lire l\'article', '/fr/article/design-responsive-avec-le-css-moderne', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(10, 2, 'es', 'Diseño responsive con CSS moderno', '<p>Mostramos cómo crear interfaces que se adaptan a cualquier pantalla con herramientas de CSS moderno como Grid, Flexbox y container queries.</p>', NULL, 'Leer artículo', '/es/entrada/diseno-responsive-con-css-moderno', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(11, 3, 'tr', 'JavaScript\'te Asenkron Programlama', '<p>Callback, Promise ve async/await ile JavaScript\'te asenkron akışları nasıl temiz biçimde yöneteceğinizi anlatıyoruz.</p>', NULL, 'Yazıyı Oku', '/tr/yazi/javascriptte-asenkron-programlama', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(12, 3, 'en', 'Asynchronous Programming in JavaScript', '<p>We explain how to cleanly manage asynchronous flows in JavaScript with callbacks, Promises and async/await.</p>', NULL, 'Read Article', '/en/posts/asynchronous-programming-in-javascript', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(13, 3, 'de', 'Asynchrone Programmierung in JavaScript', '<p>Wir erklären, wie man asynchrone Abläufe in JavaScript mit Callbacks, Promises und async/await sauber steuert.</p>', NULL, 'Beitrag lesen', '/de/beitrag/asynchrone-programmierung-in-javascript', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(14, 3, 'fr', 'La programmation asynchrone en JavaScript', '<p>Nous expliquons comment gérer proprement les flux asynchrones en JavaScript avec les callbacks, les Promises et async/await.</p>', NULL, 'Lire l\'article', '/fr/article/la-programmation-asynchrone-en-javascript', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(15, 3, 'es', 'Programación asíncrona en JavaScript', '<p>Explicamos cómo gestionar de forma limpia los flujos asíncronos en JavaScript con callbacks, Promises y async/await.</p>', NULL, 'Leer artículo', '/es/entrada/programacion-asincrona-en-javascript', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(16, 4, 'tr', 'Veritabanı Optimizasyonu için SQL İpuçları', '<p>Doğru indeksler, seçici sorgular ve EXPLAIN ile SQL performansını nasıl artıracağınıza dair pratik ipuçları.</p>', NULL, 'Yazıyı Oku', '/tr/yazi/veritabani-optimizasyonu-icin-sql-ipuclari', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(17, 4, 'en', 'SQL Tips for Database Optimization', '<p>Practical tips on boosting SQL performance with the right indexes, selective queries and EXPLAIN.</p>', NULL, 'Read Article', '/en/posts/sql-tips-for-database-optimization', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(18, 4, 'de', 'SQL-Tipps zur Datenbankoptimierung', '<p>Praktische Tipps, um die SQL-Performance mit den richtigen Indizes, selektiven Abfragen und EXPLAIN zu steigern.</p>', NULL, 'Beitrag lesen', '/de/beitrag/sql-tipps-zur-datenbankoptimierung', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(19, 4, 'fr', 'Astuces SQL pour optimiser votre base de données', '<p>Des astuces concrètes pour améliorer les performances SQL avec les bons index, des requêtes sélectives et EXPLAIN.</p>', NULL, 'Lire l\'article', '/fr/article/astuces-sql-pour-optimiser-votre-base-de-donnees', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(20, 4, 'es', 'Consejos SQL para optimizar bases de datos', '<p>Consejos prácticos para mejorar el rendimiento de SQL con los índices adecuados, consultas selectivas y EXPLAIN.</p>', NULL, 'Leer artículo', '/es/entrada/consejos-sql-para-optimizar-bases-de-datos', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(21, 5, 'tr', 'Temiz Kod Yazmanın 10 Kuralı', '<p>Anlamlı isimlendirme, küçük fonksiyonlar ve iyi testlerle sürdürülebilir kod yazmanın 10 temel kuralı.</p>', NULL, 'Yazıyı Oku', '/tr/yazi/temiz-kod-yazmanin-10-kurali', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(22, 5, 'en', '10 Rules for Writing Clean Code', '<p>Ten core rules for writing maintainable code with meaningful names, small functions and good tests.</p>', NULL, 'Read Article', '/en/posts/10-rules-for-writing-clean-code', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(23, 5, 'de', '10 Regeln für sauberen Code', '<p>Zehn Kernregeln für wartbaren Code mit aussagekräftigen Namen, kleinen Funktionen und guten Tests.</p>', NULL, 'Beitrag lesen', '/de/beitrag/10-regeln-fur-sauberen-code', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(24, 5, 'fr', '10 règles pour écrire du code propre', '<p>Dix règles essentielles pour écrire du code maintenable avec des noms parlants, de petites fonctions et de bons tests.</p>', NULL, 'Lire l\'article', '/fr/article/10-regles-pour-ecrire-du-code-propre', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(25, 5, 'es', '10 reglas para escribir código limpio', '<p>Diez reglas esenciales para escribir código mantenible con nombres significativos, funciones pequeñas y buenas pruebas.</p>', NULL, 'Leer artículo', '/es/entrada/10-reglas-para-escribir-codigo-limpio', '2026-07-06 07:46:35', '2026-07-06 07:46:35');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `posts`
--

CREATE TABLE `posts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gallery_images` json DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `comment_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `view_count` int NOT NULL DEFAULT '0',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `order`, `cover_image`, `gallery_images`, `is_featured`, `comment_enabled`, `status`, `view_count`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL, 1, 1, 1, 93, '2026-07-06 07:46:35', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(2, 1, 2, NULL, NULL, 0, 1, 1, 86, '2026-07-06 07:46:35', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(3, 1, 3, NULL, NULL, 0, 1, 1, 79, '2026-07-06 07:46:35', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(4, 1, 4, NULL, NULL, 0, 1, 1, 72, '2026-07-06 07:46:35', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(5, 1, 5, NULL, NULL, 0, 1, 1, 66, '2026-07-06 07:46:35', '2026-07-06 07:46:35', '2026-07-06 10:50:53');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `post_category`
--

CREATE TABLE `post_category` (
  `post_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `post_category`
--

INSERT INTO `post_category` (`post_id`, `category_id`) VALUES
(1, 1),
(1, 2),
(3, 2),
(4, 2),
(5, 2),
(2, 4);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `post_tag`
--

CREATE TABLE `post_tag` (
  `post_id` bigint UNSIGNED NOT NULL,
  `tag_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `post_tag`
--

INSERT INTO `post_tag` (`post_id`, `tag_id`) VALUES
(1, 1),
(4, 1),
(5, 1),
(3, 2),
(5, 2),
(2, 3),
(2, 4),
(3, 4),
(1, 5),
(4, 5);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `post_translations`
--

CREATE TABLE `post_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `post_id` bigint UNSIGNED NOT NULL,
  `language_slug` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `seo_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_keywords` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `post_translations`
--

INSERT INTO `post_translations` (`id`, `post_id`, `language_slug`, `title`, `slug`, `short_description`, `content`, `seo_title`, `seo_description`, `seo_keywords`, `created_at`, `updated_at`) VALUES
(1, 1, 'tr', 'PHP 8.3 ile Gelen Yenilikler', 'php-8-3-ile-gelen-yenilikler', 'PHP 8.3 sürümüyle gelen yeni özellikleri, performans iyileştirmelerini ve dikkat edilmesi gerekenleri inceliyoruz.', '<p>PHP 8.3; tiplendirilmiş sınıf sabitleri, yeni <code>json_validate()</code> fonksiyonu ve derin klonlama iyileştirmeleri getiriyor. Bu yazıda öne çıkan değişiklikleri örneklerle ele alıyor ve projelerinizi güncellerken nelere dikkat etmeniz gerektiğini anlatıyoruz.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(2, 1, 'en', 'What\'s New in PHP 8.3', 'whats-new-in-php-8-3', 'We look at the new features, performance improvements and gotchas that arrived with PHP 8.3.', '<p>PHP 8.3 introduces typed class constants, the new <code>json_validate()</code> function and improvements to deep cloning. In this article we walk through the highlights with examples and what to watch out for when upgrading your projects.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(3, 1, 'de', 'Was ist neu in PHP 8.3', 'was-ist-neu-in-php-8-3', 'Wir werfen einen Blick auf die neuen Funktionen, Performance-Verbesserungen und Fallstricke von PHP 8.3.', '<p>PHP 8.3 führt typisierte Klassenkonstanten, die neue Funktion <code>json_validate()</code> und Verbesserungen beim tiefen Klonen ein. In diesem Artikel gehen wir die Highlights mit Beispielen durch und zeigen, worauf beim Upgrade zu achten ist.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(4, 1, 'fr', 'Les nouveautés de PHP 8.3', 'les-nouveautes-de-php-8-3', 'Nous passons en revue les nouveautés, les améliorations de performances et les pièges de PHP 8.3.', '<p>PHP 8.3 introduit les constantes de classe typées, la nouvelle fonction <code>json_validate()</code> et des améliorations du clonage profond. Dans cet article, nous parcourons les points forts avec des exemples et ce qu\'il faut surveiller lors de la mise à niveau.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(5, 1, 'es', 'Novedades de PHP 8.3', 'novedades-de-php-8-3', 'Repasamos las nuevas funciones, las mejoras de rendimiento y los detalles a tener en cuenta de PHP 8.3.', '<p>PHP 8.3 introduce constantes de clase tipadas, la nueva función <code>json_validate()</code> y mejoras en la clonación profunda. En este artículo recorremos lo más destacado con ejemplos y qué tener en cuenta al actualizar tus proyectos.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(6, 2, 'tr', 'Modern CSS ile Responsive Tasarım', 'modern-css-ile-responsive-tasarim', 'Grid, Flexbox ve container query gibi modern CSS araçlarıyla her ekrana uyum sağlayan arayüzler kurmayı anlatıyoruz.', '<p>Modern CSS; Grid, Flexbox, <code>clamp()</code> ve container query sayesinde medya sorgularına daha az bağımlı, gerçekten esnek arayüzler kurmayı mümkün kılıyor. Bu yazıda küçük örneklerle responsive bir düzenin nasıl oluşturulacağını adım adım gösteriyoruz.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(7, 2, 'en', 'Responsive Design with Modern CSS', 'responsive-design-with-modern-css', 'We show how to build interfaces that adapt to any screen using modern CSS tools like Grid, Flexbox and container queries.', '<p>Modern CSS makes truly flexible layouts possible with Grid, Flexbox, <code>clamp()</code> and container queries, reducing the need for media queries. In this article we build a responsive layout step by step with small, practical examples.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(8, 2, 'de', 'Responsives Design mit modernem CSS', 'responsives-design-mit-modernem-css', 'Wir zeigen, wie man mit modernen CSS-Werkzeugen wie Grid, Flexbox und Container Queries Oberflächen für jeden Bildschirm baut.', '<p>Modernes CSS ermöglicht mit Grid, Flexbox, <code>clamp()</code> und Container Queries wirklich flexible Layouts und reduziert den Bedarf an Media Queries. In diesem Artikel bauen wir Schritt für Schritt ein responsives Layout mit kleinen, praktischen Beispielen.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(9, 2, 'fr', 'Design responsive avec le CSS moderne', 'design-responsive-avec-le-css-moderne', 'Nous montrons comment créer des interfaces qui s\'adaptent à tous les écrans avec les outils CSS modernes : Grid, Flexbox et container queries.', '<p>Le CSS moderne rend possibles des mises en page vraiment flexibles grâce à Grid, Flexbox, <code>clamp()</code> et aux container queries, en réduisant le recours aux media queries. Dans cet article, nous construisons une mise en page responsive pas à pas avec de petits exemples concrets.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(10, 2, 'es', 'Diseño responsive con CSS moderno', 'diseno-responsive-con-css-moderno', 'Mostramos cómo crear interfaces que se adaptan a cualquier pantalla con herramientas de CSS moderno como Grid, Flexbox y container queries.', '<p>El CSS moderno hace posibles diseños realmente flexibles con Grid, Flexbox, <code>clamp()</code> y las container queries, reduciendo la dependencia de las media queries. En este artículo construimos un diseño responsive paso a paso con ejemplos pequeños y prácticos.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(11, 3, 'tr', 'JavaScript\'te Asenkron Programlama', 'javascriptte-asenkron-programlama', 'Callback, Promise ve async/await ile JavaScript\'te asenkron akışları nasıl temiz biçimde yöneteceğinizi anlatıyoruz.', '<p>JavaScript tek iş parçacıklıdır ama Promise ve <code>async/await</code> sayesinde ağ istekleri gibi işleri arayüzü kilitlemeden yürütür. Bu yazıda callback\'lerden async/await\'e geçişi ve hataları doğru yönetmeyi örneklerle ele alıyoruz.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(12, 3, 'en', 'Asynchronous Programming in JavaScript', 'asynchronous-programming-in-javascript', 'We explain how to cleanly manage asynchronous flows in JavaScript with callbacks, Promises and async/await.', '<p>JavaScript is single-threaded, yet Promises and <code>async/await</code> let it handle work like network requests without blocking the UI. In this article we cover moving from callbacks to async/await and handling errors correctly, with examples.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(13, 3, 'de', 'Asynchrone Programmierung in JavaScript', 'asynchrone-programmierung-in-javascript', 'Wir erklären, wie man asynchrone Abläufe in JavaScript mit Callbacks, Promises und async/await sauber steuert.', '<p>JavaScript ist single-threaded, doch Promises und <code>async/await</code> ermöglichen es, Aufgaben wie Netzwerkanfragen ohne Blockieren der Oberfläche zu erledigen. In diesem Artikel behandeln wir den Umstieg von Callbacks auf async/await und die richtige Fehlerbehandlung mit Beispielen.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(14, 3, 'fr', 'La programmation asynchrone en JavaScript', 'la-programmation-asynchrone-en-javascript', 'Nous expliquons comment gérer proprement les flux asynchrones en JavaScript avec les callbacks, les Promises et async/await.', '<p>JavaScript est monothread, mais les Promises et <code>async/await</code> lui permettent de gérer des tâches comme les requêtes réseau sans bloquer l\'interface. Dans cet article, nous abordons le passage des callbacks à async/await et la bonne gestion des erreurs, avec des exemples.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(15, 3, 'es', 'Programación asíncrona en JavaScript', 'programacion-asincrona-en-javascript', 'Explicamos cómo gestionar de forma limpia los flujos asíncronos en JavaScript con callbacks, Promises y async/await.', '<p>JavaScript es de un solo hilo, pero las Promises y <code>async/await</code> le permiten gestionar tareas como las peticiones de red sin bloquear la interfaz. En este artículo tratamos el paso de los callbacks a async/await y el manejo correcto de errores, con ejemplos.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(16, 4, 'tr', 'Veritabanı Optimizasyonu için SQL İpuçları', 'veritabani-optimizasyonu-icin-sql-ipuclari', 'Doğru indeksler, seçici sorgular ve EXPLAIN ile SQL performansını nasıl artıracağınıza dair pratik ipuçları.', '<p>Yavaş sorguların çoğu eksik indeks ya da gereksiz kolon seçiminden kaynaklanır. Bu yazıda <code>EXPLAIN</code> ile sorguları çözümlemeyi, doğru indeksleri seçmeyi ve N+1 problemini önlemeyi somut örneklerle gösteriyoruz.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(17, 4, 'en', 'SQL Tips for Database Optimization', 'sql-tips-for-database-optimization', 'Practical tips on boosting SQL performance with the right indexes, selective queries and EXPLAIN.', '<p>Most slow queries come from missing indexes or selecting unnecessary columns. In this article we show how to analyse queries with <code>EXPLAIN</code>, choose the right indexes and avoid the N+1 problem, with concrete examples.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(18, 4, 'de', 'SQL-Tipps zur Datenbankoptimierung', 'sql-tipps-zur-datenbankoptimierung', 'Praktische Tipps, um die SQL-Performance mit den richtigen Indizes, selektiven Abfragen und EXPLAIN zu steigern.', '<p>Die meisten langsamen Abfragen entstehen durch fehlende Indizes oder das Auswählen unnötiger Spalten. In diesem Artikel zeigen wir, wie man Abfragen mit <code>EXPLAIN</code> analysiert, die richtigen Indizes wählt und das N+1-Problem vermeidet – mit konkreten Beispielen.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(19, 4, 'fr', 'Astuces SQL pour optimiser votre base de données', 'astuces-sql-pour-optimiser-votre-base-de-donnees', 'Des astuces concrètes pour améliorer les performances SQL avec les bons index, des requêtes sélectives et EXPLAIN.', '<p>La plupart des requêtes lentes proviennent d\'index manquants ou de la sélection de colonnes inutiles. Dans cet article, nous montrons comment analyser les requêtes avec <code>EXPLAIN</code>, choisir les bons index et éviter le problème N+1, avec des exemples concrets.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(20, 4, 'es', 'Consejos SQL para optimizar bases de datos', 'consejos-sql-para-optimizar-bases-de-datos', 'Consejos prácticos para mejorar el rendimiento de SQL con los índices adecuados, consultas selectivas y EXPLAIN.', '<p>La mayoría de las consultas lentas se deben a índices ausentes o a seleccionar columnas innecesarias. En este artículo mostramos cómo analizar consultas con <code>EXPLAIN</code>, elegir los índices adecuados y evitar el problema N+1, con ejemplos concretos.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(21, 5, 'tr', 'Temiz Kod Yazmanın 10 Kuralı', 'temiz-kod-yazmanin-10-kurali', 'Anlamlı isimlendirme, küçük fonksiyonlar ve iyi testlerle sürdürülebilir kod yazmanın 10 temel kuralı.', '<p>Temiz kod; kısa fonksiyonlar, anlamlı isimler, tek sorumluluk ve iyi testlerle başlar. Bu yazıda ekip içinde okunabilirliği ve bakımı kolaylaştıran 10 pratik kuralı örneklerle derledik.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(22, 5, 'en', '10 Rules for Writing Clean Code', '10-rules-for-writing-clean-code', 'Ten core rules for writing maintainable code with meaningful names, small functions and good tests.', '<p>Clean code starts with short functions, meaningful names, single responsibility and good tests. In this article we gather ten practical rules that make code easier to read and maintain across a team, with examples.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(23, 5, 'de', '10 Regeln für sauberen Code', '10-regeln-fur-sauberen-code', 'Zehn Kernregeln für wartbaren Code mit aussagekräftigen Namen, kleinen Funktionen und guten Tests.', '<p>Sauberer Code beginnt mit kurzen Funktionen, aussagekräftigen Namen, einer einzigen Verantwortung und guten Tests. In diesem Artikel sammeln wir zehn praktische Regeln, die Code im Team lesbarer und wartbarer machen – mit Beispielen.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(24, 5, 'fr', '10 règles pour écrire du code propre', '10-regles-pour-ecrire-du-code-propre', 'Dix règles essentielles pour écrire du code maintenable avec des noms parlants, de petites fonctions et de bons tests.', '<p>Un code propre commence par des fonctions courtes, des noms parlants, une responsabilité unique et de bons tests. Dans cet article, nous rassemblons dix règles pratiques qui rendent le code plus lisible et plus facile à maintenir en équipe, avec des exemples.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(25, 5, 'es', '10 reglas para escribir código limpio', '10-reglas-para-escribir-codigo-limpio', 'Diez reglas esenciales para escribir código mantenible con nombres significativos, funciones pequeñas y buenas pruebas.', '<p>El código limpio empieza con funciones cortas, nombres significativos, una sola responsabilidad y buenas pruebas. En este artículo reunimos diez reglas prácticas que hacen el código más legible y fácil de mantener en equipo, con ejemplos.</p>', NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `order` smallint UNSIGNED NOT NULL DEFAULT '0',
  `cover_image` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gallery_images` json DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `brand` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `model` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fuel_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `heating_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `products`
--

INSERT INTO `products` (`id`, `order`, `cover_image`, `gallery_images`, `is_featured`, `status`, `brand`, `price`, `model`, `type`, `fuel_type`, `heating_type`, `product_url`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'uploads/products/2026-07/img1-1783167249-6112f5.webp', NULL, 1, 1, 'Umay', 49.00, 'v1.0', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(2, 2, 'uploads/products/2026-07/img2-1783167251-243bf9.webp', NULL, 0, 1, 'Umay', 39.00, 'v1.0', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(3, 3, 'uploads/products/2026-07/img3-1783167252-589979.webp', NULL, 0, 1, 'Umay', 129.00, 'v1.0', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(4, 4, 'uploads/products/2026-07/img4-1783167252-8bd4b5.webp', NULL, 0, 1, 'Umay', 29.00, 'v1.0', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(5, 5, 'uploads/products/2026-07/img5-1783167253-93573a.webp', NULL, 0, 1, 'Umay', 199.00, 'v1.0', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35', '2026-07-06 07:46:35');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `product_category`
--

CREATE TABLE `product_category` (
  `product_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `product_category`
--

INSERT INTO `product_category` (`product_id`, `category_id`) VALUES
(3, 2),
(4, 2),
(5, 2),
(1, 4),
(2, 4);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `product_tag`
--

CREATE TABLE `product_tag` (
  `product_id` bigint UNSIGNED NOT NULL,
  `tag_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `product_tag`
--

INSERT INTO `product_tag` (`product_id`, `tag_id`) VALUES
(3, 1),
(4, 1),
(5, 1),
(3, 2),
(5, 2),
(1, 3),
(2, 3),
(1, 4),
(2, 4),
(4, 5);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `product_translations`
--

CREATE TABLE `product_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `language_slug` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `specifications` json DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `features` longtext COLLATE utf8mb4_unicode_ci,
  `attributes` longtext COLLATE utf8mb4_unicode_ci,
  `documents` longtext COLLATE utf8mb4_unicode_ci,
  `seo_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_keywords` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `product_translations`
--

INSERT INTO `product_translations` (`id`, `product_id`, `language_slug`, `title`, `slug`, `short_description`, `specifications`, `content`, `features`, `attributes`, `documents`, `seo_title`, `seo_description`, `seo_keywords`, `created_at`, `updated_at`) VALUES
(1, 1, 'tr', 'Umay Yönetim Teması', 'umay-yonetim-temasi', 'Umay framework için modern, hızlı ve tamamen responsive bir yönetim paneli teması.', NULL, '<p>Umay Yönetim Teması; karanlık/aydınlık mod, hazır bileşenler ve temiz bir arayüzle projelerinize profesyonel bir yönetim paneli kazandırır. Kurulumu kolaydır ve tüm modüllerle uyumludur.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(2, 1, 'en', 'Umay Admin Theme', 'umay-admin-theme', 'A modern, fast and fully responsive admin panel theme for the Umay framework.', NULL, '<p>The Umay Admin Theme gives your projects a professional dashboard with dark/light mode, ready-made components and a clean interface. It is easy to install and compatible with every module.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(3, 1, 'de', 'Umay Admin-Theme', 'umay-admin-theme', 'Ein modernes, schnelles und vollständig responsives Admin-Panel-Theme für das Umay-Framework.', NULL, '<p>Das Umay Admin-Theme verleiht Ihren Projekten ein professionelles Dashboard mit Dunkel-/Hell-Modus, fertigen Komponenten und einer klaren Oberfläche. Es ist einfach zu installieren und mit jedem Modul kompatibel.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(4, 1, 'fr', 'Thème d\'administration Umay', 'theme-d-administration-umay', 'Un thème de panneau d\'administration moderne, rapide et entièrement responsive pour le framework Umay.', NULL, '<p>Le thème d\'administration Umay offre à vos projets un tableau de bord professionnel avec mode sombre/clair, des composants prêts à l\'emploi et une interface épurée. Il est facile à installer et compatible avec tous les modules.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(5, 1, 'es', 'Tema de Administración Umay', 'tema-de-administracion-umay', 'Un tema de panel de administración moderno, rápido y totalmente responsive para el framework Umay.', NULL, '<p>El Tema de Administración Umay ofrece a tus proyectos un panel profesional con modo oscuro/claro, componentes listos para usar y una interfaz limpia. Es fácil de instalar y compatible con todos los módulos.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(6, 2, 'tr', 'Umay Blog Şablonu', 'umay-blog-sablonu', 'Editöryel görünümlü, SEO uyumlu ve çok dilli bir blog için hazır arayüz şablonu.', NULL, '<p>Umay Blog Şablonu; okunabilir tipografi, öne çıkan yazı alanları ve hızlı sayfa yükleme ile modern bir blog deneyimi sunar. Çok dilli yapıyla kutudan çıktığı gibi uyumludur.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(7, 2, 'en', 'Umay Blog Template', 'umay-blog-template', 'A ready-made, editorial-style, SEO-friendly template for a multilingual blog.', NULL, '<p>The Umay Blog Template delivers a modern blogging experience with readable typography, featured-post areas and fast page loads. It works out of the box with the multilingual setup.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(8, 2, 'de', 'Umay Blog-Vorlage', 'umay-blog-vorlage', 'Eine fertige, redaktionell gestaltete und SEO-freundliche Vorlage für einen mehrsprachigen Blog.', NULL, '<p>Die Umay Blog-Vorlage bietet mit lesbarer Typografie, Bereichen für hervorgehobene Beiträge und schnellen Ladezeiten ein modernes Blog-Erlebnis. Sie ist von Haus aus mit dem mehrsprachigen Aufbau kompatibel.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(9, 2, 'fr', 'Modèle de blog Umay', 'modele-de-blog-umay', 'Un modèle prêt à l\'emploi, au style éditorial et optimisé pour le SEO, pour un blog multilingue.', NULL, '<p>Le modèle de blog Umay offre une expérience de blog moderne avec une typographie lisible, des zones d\'articles à la une et un chargement rapide des pages. Il fonctionne d\'emblée avec la configuration multilingue.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(10, 2, 'es', 'Plantilla de Blog Umay', 'plantilla-de-blog-umay', 'Una plantilla lista para usar, de estilo editorial y optimizada para SEO, para un blog multilingüe.', NULL, '<p>La Plantilla de Blog Umay ofrece una experiencia de blog moderna con tipografía legible, áreas de entradas destacadas y carga rápida de páginas. Funciona desde el primer momento con la configuración multilingüe.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(11, 3, 'tr', 'E-Ticaret Başlangıç Kiti', 'e-ticaret-baslangic-kiti', 'Ürün kataloğu, sepet ve ödeme akışıyla hızlı başlangıç için hazır e-ticaret modülü.', NULL, '<p>E-Ticaret Başlangıç Kiti; ürün listeleme, filtreleme, sepet ve ödeme adımlarını içeren hazır bir temel sunar. Umay üzerinde çalışır ve kendi tasarımınıza kolayca uyarlanır.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(12, 3, 'en', 'E-Commerce Starter Kit', 'e-commerce-starter-kit', 'A ready e-commerce module for a fast start with a product catalog, cart and checkout flow.', NULL, '<p>The E-Commerce Starter Kit gives you a ready foundation with product listing, filtering, cart and checkout steps. It runs on Umay and is easy to adapt to your own design.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(13, 3, 'de', 'E-Commerce-Starterkit', 'e-commerce-starterkit', 'Ein fertiges E-Commerce-Modul für den schnellen Start mit Produktkatalog, Warenkorb und Checkout-Flow.', NULL, '<p>Das E-Commerce-Starterkit bietet eine fertige Basis mit Produktliste, Filterung, Warenkorb und Checkout-Schritten. Es läuft auf Umay und lässt sich leicht an Ihr eigenes Design anpassen.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(14, 3, 'fr', 'Kit de démarrage e-commerce', 'kit-de-demarrage-e-commerce', 'Un module e-commerce prêt à l\'emploi pour démarrer vite avec catalogue produits, panier et tunnel de paiement.', NULL, '<p>Le kit de démarrage e-commerce vous offre une base prête avec listing produits, filtres, panier et étapes de paiement. Il fonctionne sur Umay et s\'adapte facilement à votre propre design.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(15, 3, 'es', 'Kit de inicio para e-commerce', 'kit-de-inicio-para-e-commerce', 'Un módulo de e-commerce listo para empezar rápido con catálogo de productos, carrito y flujo de pago.', NULL, '<p>El Kit de inicio para e-commerce te ofrece una base lista con listado de productos, filtros, carrito y pasos de pago. Funciona sobre Umay y se adapta fácilmente a tu propio diseño.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(16, 4, 'tr', 'SEO Optimizasyon Eklentisi', 'seo-optimizasyon-eklentisi', 'Meta etiketleri, site haritası ve zengin snippet\'lerle arama motoru görünürlüğünü artıran eklenti.', NULL, '<p>SEO Optimizasyon Eklentisi; otomatik meta etiketleri, XML site haritası ve yapılandırılmış veri desteğiyle sitenizin arama motorlarındaki görünürlüğünü artırır. Çok dilli içerikle tam uyumludur.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(17, 4, 'en', 'SEO Optimization Plugin', 'seo-optimization-plugin', 'A plugin that improves search engine visibility with meta tags, a sitemap and rich snippets.', NULL, '<p>The SEO Optimization Plugin boosts your site\'s search visibility with automatic meta tags, an XML sitemap and structured-data support. It is fully compatible with multilingual content.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(18, 4, 'de', 'SEO-Optimierungs-Plugin', 'seo-optimierungs-plugin', 'Ein Plugin, das die Sichtbarkeit in Suchmaschinen mit Meta-Tags, Sitemap und Rich Snippets verbessert.', NULL, '<p>Das SEO-Optimierungs-Plugin steigert die Sichtbarkeit Ihrer Website mit automatischen Meta-Tags, einer XML-Sitemap und Unterstützung für strukturierte Daten. Es ist voll mit mehrsprachigen Inhalten kompatibel.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(19, 4, 'fr', 'Extension d\'optimisation SEO', 'extension-d-optimisation-seo', 'Une extension qui améliore la visibilité sur les moteurs de recherche avec balises meta, sitemap et rich snippets.', NULL, '<p>L\'extension d\'optimisation SEO améliore la visibilité de votre site grâce à des balises meta automatiques, un sitemap XML et la prise en charge des données structurées. Elle est entièrement compatible avec le contenu multilingue.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(20, 4, 'es', 'Complemento de optimización SEO', 'complemento-de-optimizacion-seo', 'Un complemento que mejora la visibilidad en buscadores con metaetiquetas, sitemap y rich snippets.', NULL, '<p>El Complemento de optimización SEO mejora la visibilidad de tu sitio con metaetiquetas automáticas, un sitemap XML y soporte de datos estructurados. Es totalmente compatible con el contenido multilingüe.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(21, 5, 'tr', 'Çok Dilli Site Paketi', 'cok-dilli-site-paketi', 'Sınırsız dil, otomatik yönlendirme ve dile çevrili URL\'lerle tam çok dilli site kurulumu.', NULL, '<p>Çok Dilli Site Paketi; sınırsız dil desteği, dile göre çevrili URL\'ler ve kolay çeviri yönetimiyle sitenizi tamamen çok dilli hale getirir. Umay\'ın çeviri altyapısıyla sorunsuz çalışır.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(22, 5, 'en', 'Multilingual Site Bundle', 'multilingual-site-bundle', 'A complete multilingual site setup with unlimited languages, automatic redirects and localized URLs.', NULL, '<p>The Multilingual Site Bundle makes your site fully multilingual with unlimited language support, localized URLs and easy translation management. It works seamlessly with Umay\'s translation layer.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(23, 5, 'de', 'Mehrsprachiges Website-Paket', 'mehrsprachiges-website-paket', 'Ein komplettes mehrsprachiges Setup mit unbegrenzten Sprachen, automatischen Weiterleitungen und lokalisierten URLs.', NULL, '<p>Das mehrsprachige Website-Paket macht Ihre Website mit unbegrenzter Sprachunterstützung, lokalisierten URLs und einfacher Übersetzungsverwaltung vollständig mehrsprachig. Es arbeitet nahtlos mit der Übersetzungsebene von Umay zusammen.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(24, 5, 'fr', 'Pack de site multilingue', 'pack-de-site-multilingue', 'Une configuration multilingue complète avec langues illimitées, redirections automatiques et URLs localisées.', NULL, '<p>Le pack de site multilingue rend votre site entièrement multilingue avec un support de langues illimité, des URLs localisées et une gestion des traductions simple. Il fonctionne parfaitement avec la couche de traduction d\'Umay.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(25, 5, 'es', 'Paquete de sitio multilingüe', 'paquete-de-sitio-multilingue', 'Una configuración multilingüe completa con idiomas ilimitados, redirecciones automáticas y URLs localizadas.', NULL, '<p>El Paquete de sitio multilingüe hace que tu sitio sea totalmente multilingüe con soporte de idiomas ilimitado, URLs localizadas y una gestión de traducciones sencilla. Funciona a la perfección con la capa de traducción de Umay.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int UNSIGNED NOT NULL,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permission_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `slides`
--

CREATE TABLE `slides` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image',
  `media_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text_position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'left',
  `label_size` smallint UNSIGNED NOT NULL DEFAULT '12',
  `title_size` smallint UNSIGNED NOT NULL DEFAULT '64',
  `subtitle_size` smallint UNSIGNED NOT NULL DEFAULT '14',
  `order` int UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `slides`
--

INSERT INTO `slides` (`id`, `type`, `media_file`, `text_position`, `label_size`, `title_size`, `subtitle_size`, `order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'image', 'uploads/slides/2026-07/img1-1783167268-320c09.webp', 'left', 12, 48, 16, 1, 1, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(2, 'image', 'uploads/slides/2026-07/img2-1783167269-ecba5d.webp', 'right', 12, 48, 16, 2, 1, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(3, 'image', 'uploads/slides/2026-07/img3-1783167269-e0fe8f.webp', 'left', 12, 48, 16, 3, 1, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(4, 'image', 'uploads/slides/2026-07/img4-1783167270-0506f1.webp', 'right', 12, 48, 16, 4, 1, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(5, 'image', 'uploads/slides/2026-07/img5-1783167270-35853a.webp', 'left', 12, 48, 16, 5, 1, '2026-07-06 07:46:35', '2026-07-06 07:46:35');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `slide_translations`
--

CREATE TABLE `slide_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `slide_id` bigint UNSIGNED NOT NULL,
  `language_slug` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle` text COLLATE utf8mb4_unicode_ci,
  `button_text` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `slide_translations`
--

INSERT INTO `slide_translations` (`id`, `slide_id`, `language_slug`, `label`, `title`, `subtitle`, `button_text`, `button_url`, `created_at`, `updated_at`) VALUES
(1, 1, 'tr', 'PHP', 'PHP 8.3 ile Gelen Yenilikler', 'PHP 8.3 sürümüyle gelen yeni özellikleri, performans iyileştirmelerini ve dikkat edilmesi gerekenleri inceliyoruz.', 'Yazıyı Oku', '/tr/yazi/php-8-3-ile-gelen-yenilikler', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(2, 1, 'en', 'PHP', 'What\'s New in PHP 8.3', 'We look at the new features, performance improvements and gotchas that arrived with PHP 8.3.', 'Read Article', '/en/posts/whats-new-in-php-8-3', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(3, 1, 'de', 'PHP', 'Was ist neu in PHP 8.3', 'Wir werfen einen Blick auf die neuen Funktionen, Performance-Verbesserungen und Fallstricke von PHP 8.3.', 'Beitrag lesen', '/de/beitrag/was-ist-neu-in-php-8-3', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(4, 1, 'fr', 'PHP', 'Les nouveautés de PHP 8.3', 'Nous passons en revue les nouveautés, les améliorations de performances et les pièges de PHP 8.3.', 'Lire l\'article', '/fr/article/les-nouveautes-de-php-8-3', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(5, 1, 'es', 'PHP', 'Novedades de PHP 8.3', 'Repasamos las nuevas funciones, las mejoras de rendimiento y los detalles a tener en cuenta de PHP 8.3.', 'Leer artículo', '/es/entrada/novedades-de-php-8-3', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(6, 2, 'tr', 'CSS', 'Modern CSS ile Responsive Tasarım', 'Grid, Flexbox ve container query gibi modern CSS araçlarıyla her ekrana uyum sağlayan arayüzler kurmayı anlatıyoruz.', 'Yazıyı Oku', '/tr/yazi/modern-css-ile-responsive-tasarim', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(7, 2, 'en', 'CSS', 'Responsive Design with Modern CSS', 'We show how to build interfaces that adapt to any screen using modern CSS tools like Grid, Flexbox and container queries.', 'Read Article', '/en/posts/responsive-design-with-modern-css', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(8, 2, 'de', 'CSS', 'Responsives Design mit modernem CSS', 'Wir zeigen, wie man mit modernen CSS-Werkzeugen wie Grid, Flexbox und Container Queries Oberflächen für jeden Bildschirm baut.', 'Beitrag lesen', '/de/beitrag/responsives-design-mit-modernem-css', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(9, 2, 'fr', 'CSS', 'Design responsive avec le CSS moderne', 'Nous montrons comment créer des interfaces qui s\'adaptent à tous les écrans avec les outils CSS modernes : Grid, Flexbox et container queries.', 'Lire l\'article', '/fr/article/design-responsive-avec-le-css-moderne', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(10, 2, 'es', 'CSS', 'Diseño responsive con CSS moderno', 'Mostramos cómo crear interfaces que se adaptan a cualquier pantalla con herramientas de CSS moderno como Grid, Flexbox y container queries.', 'Leer artículo', '/es/entrada/diseno-responsive-con-css-moderno', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(11, 3, 'tr', 'JavaScript', 'JavaScript\'te Asenkron Programlama', 'Callback, Promise ve async/await ile JavaScript\'te asenkron akışları nasıl temiz biçimde yöneteceğinizi anlatıyoruz.', 'Yazıyı Oku', '/tr/yazi/javascriptte-asenkron-programlama', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(12, 3, 'en', 'JavaScript', 'Asynchronous Programming in JavaScript', 'We explain how to cleanly manage asynchronous flows in JavaScript with callbacks, Promises and async/await.', 'Read Article', '/en/posts/asynchronous-programming-in-javascript', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(13, 3, 'de', 'JavaScript', 'Asynchrone Programmierung in JavaScript', 'Wir erklären, wie man asynchrone Abläufe in JavaScript mit Callbacks, Promises und async/await sauber steuert.', 'Beitrag lesen', '/de/beitrag/asynchrone-programmierung-in-javascript', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(14, 3, 'fr', 'JavaScript', 'La programmation asynchrone en JavaScript', 'Nous expliquons comment gérer proprement les flux asynchrones en JavaScript avec les callbacks, les Promises et async/await.', 'Lire l\'article', '/fr/article/la-programmation-asynchrone-en-javascript', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(15, 3, 'es', 'JavaScript', 'Programación asíncrona en JavaScript', 'Explicamos cómo gestionar de forma limpia los flujos asíncronos en JavaScript con callbacks, Promises y async/await.', 'Leer artículo', '/es/entrada/programacion-asincrona-en-javascript', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(16, 4, 'tr', 'SQL', 'Veritabanı Optimizasyonu için SQL İpuçları', 'Doğru indeksler, seçici sorgular ve EXPLAIN ile SQL performansını nasıl artıracağınıza dair pratik ipuçları.', 'Yazıyı Oku', '/tr/yazi/veritabani-optimizasyonu-icin-sql-ipuclari', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(17, 4, 'en', 'SQL', 'SQL Tips for Database Optimization', 'Practical tips on boosting SQL performance with the right indexes, selective queries and EXPLAIN.', 'Read Article', '/en/posts/sql-tips-for-database-optimization', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(18, 4, 'de', 'SQL', 'SQL-Tipps zur Datenbankoptimierung', 'Praktische Tipps, um die SQL-Performance mit den richtigen Indizes, selektiven Abfragen und EXPLAIN zu steigern.', 'Beitrag lesen', '/de/beitrag/sql-tipps-zur-datenbankoptimierung', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(19, 4, 'fr', 'SQL', 'Astuces SQL pour optimiser votre base de données', 'Des astuces concrètes pour améliorer les performances SQL avec les bons index, des requêtes sélectives et EXPLAIN.', 'Lire l\'article', '/fr/article/astuces-sql-pour-optimiser-votre-base-de-donnees', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(20, 4, 'es', 'SQL', 'Consejos SQL para optimizar bases de datos', 'Consejos prácticos para mejorar el rendimiento de SQL con los índices adecuados, consultas selectivas y EXPLAIN.', 'Leer artículo', '/es/entrada/consejos-sql-para-optimizar-bases-de-datos', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(21, 5, 'tr', 'Temiz Kod', 'Temiz Kod Yazmanın 10 Kuralı', 'Anlamlı isimlendirme, küçük fonksiyonlar ve iyi testlerle sürdürülebilir kod yazmanın 10 temel kuralı.', 'Yazıyı Oku', '/tr/yazi/temiz-kod-yazmanin-10-kurali', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(22, 5, 'en', 'Clean Code', '10 Rules for Writing Clean Code', 'Ten core rules for writing maintainable code with meaningful names, small functions and good tests.', 'Read Article', '/en/posts/10-rules-for-writing-clean-code', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(23, 5, 'de', 'Sauberer Code', '10 Regeln für sauberen Code', 'Zehn Kernregeln für wartbaren Code mit aussagekräftigen Namen, kleinen Funktionen und guten Tests.', 'Beitrag lesen', '/de/beitrag/10-regeln-fur-sauberen-code', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(24, 5, 'fr', 'Code propre', '10 règles pour écrire du code propre', 'Dix règles essentielles pour écrire du code maintenable avec des noms parlants, de petites fonctions et de bons tests.', 'Lire l\'article', '/fr/article/10-regles-pour-ecrire-du-code-propre', '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(25, 5, 'es', 'Código limpio', '10 reglas para escribir código limpio', 'Diez reglas esenciales para escribir código mantenible con nombres significativos, funciones pequeñas y buenas pruebas.', 'Leer artículo', '/es/entrada/10-reglas-para-escribir-codigo-limpio', '2026-07-06 07:46:35', '2026-07-06 07:46:35');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tags`
--

CREATE TABLE `tags` (
  `id` bigint UNSIGNED NOT NULL,
  `color` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `usage_count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `tags`
--

INSERT INTO `tags` (`id`, `color`, `status`, `usage_count`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 0, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(2, NULL, 1, 0, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(3, NULL, 1, 0, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(4, NULL, 1, 0, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(5, NULL, 1, 0, '2026-07-06 07:46:35', '2026-07-06 07:46:35');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tag_translations`
--

CREATE TABLE `tag_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `tag_id` bigint UNSIGNED NOT NULL,
  `language_slug` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `seo_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_keywords` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `tag_translations`
--

INSERT INTO `tag_translations` (`id`, `tag_id`, `language_slug`, `name`, `slug`, `description`, `seo_title`, `seo_description`, `seo_keywords`, `created_at`, `updated_at`) VALUES
(1, 1, 'tr', 'PHP', 'php', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(2, 1, 'en', 'PHP', 'php', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(3, 1, 'de', 'PHP', 'php', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(4, 1, 'fr', 'PHP', 'php', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(5, 1, 'es', 'PHP', 'php', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(6, 2, 'tr', 'JavaScript', 'javascript', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(7, 2, 'en', 'JavaScript', 'javascript', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(8, 2, 'de', 'JavaScript', 'javascript', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(9, 2, 'fr', 'JavaScript', 'javascript', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(10, 2, 'es', 'JavaScript', 'javascript', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(11, 3, 'tr', 'CSS', 'css', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(12, 3, 'en', 'CSS', 'css', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(13, 3, 'de', 'CSS', 'css', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(14, 3, 'fr', 'CSS', 'css', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(15, 3, 'es', 'CSS', 'css', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(16, 4, 'tr', 'HTML', 'html', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(17, 4, 'en', 'HTML', 'html', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(18, 4, 'de', 'HTML', 'html', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(19, 4, 'fr', 'HTML', 'html', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(20, 4, 'es', 'HTML', 'html', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(21, 5, 'tr', 'SQL', 'sql', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(22, 5, 'en', 'SQL', 'sql', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(23, 5, 'de', 'SQL', 'sql', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(24, 5, 'fr', 'SQL', 'sql', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35'),
(25, 5, 'es', 'SQL', 'sql', NULL, NULL, NULL, NULL, '2026-07-06 07:46:35', '2026-07-06 07:46:35');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'member',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `username`, `email`, `password`, `profile_image`, `bio`, `role`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'User', 'admin', 'admin@umay.test', '$2y$10$vS3s3kPH..jYuVIN/3UBQeFZI81aTa9qLg844N6x3PRi3WYY80Cr6', NULL, NULL, 'admin', 'active', '563af19e593ca7b6543644065f4a890f2441497c53aea4a70572cf7a8858343a', NULL, '2026-07-05 10:46:36'),
(2, 'Test', 'Kullanıcı', 'testuser', 'test@umay.test', '$2y$10$dHtcVL0Al69WRyvw41jx6.C7DpX1.u5gvlSAUWn.V9WJQIIS2ZYEe', NULL, NULL, 'member', 'active', NULL, NULL, NULL);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_status_sort_order_index` (`status`,`sort_order`),
  ADD KEY `categories_parent_id_status_sort_order_index` (`parent_id`,`status`,`sort_order`),
  ADD KEY `categories_level_status_index` (`level`,`status`),
  ADD KEY `categories_path_index` (`path`),
  ADD KEY `categories_parent_id_level_index` (`parent_id`,`level`);

--
-- Tablo için indeksler `category_translations`
--
ALTER TABLE `category_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_translations_cat_lang_unique` (`category_id`,`language_slug`),
  ADD UNIQUE KEY `category_translations_slug_lang_unique` (`slug`,`language_slug`),
  ADD KEY `category_translations_language_slug_slug_index` (`language_slug`,`slug`),
  ADD KEY `category_translations_language_slug_category_id_index` (`language_slug`,`category_id`);

--
-- Tablo için indeksler `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `languages_slug_unique` (`slug`),
  ADD UNIQUE KEY `languages_single_default_unique` (`is_default_flag`),
  ADD KEY `languages_status_is_default_index` (`status`,`is_default`);

--
-- Tablo için indeksler `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menus_key_unique` (`key`);

--
-- Tablo için indeksler `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_items_parent_id_foreign` (`parent_id`),
  ADD KEY `menu_items_menu_id_parent_id_order_index` (`menu_id`,`parent_id`,`order`);

--
-- Tablo için indeksler `menu_item_translations`
--
ALTER TABLE `menu_item_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menu_item_translations_menu_item_id_language_slug_unique` (`menu_item_id`,`language_slug`),
  ADD KEY `menu_item_translations_language_slug_index` (`language_slug`);

--
-- Tablo için indeksler `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pages_status_sort_order_index` (`status`,`sort_order`),
  ADD KEY `pages_template_index` (`template`);

--
-- Tablo için indeksler `page_translations`
--
ALTER TABLE `page_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `page_translations_page_lang_unique` (`page_id`,`language_slug`),
  ADD UNIQUE KEY `page_translations_slug_lang_unique` (`slug`,`language_slug`),
  ADD KEY `page_translations_language_slug_slug_index` (`language_slug`,`slug`),
  ADD KEY `page_translations_language_slug_page_id_index` (`language_slug`,`page_id`);

--
-- Tablo için indeksler `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Tablo için indeksler `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Tablo için indeksler `popups`
--
ALTER TABLE `popups`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `popup_translations`
--
ALTER TABLE `popup_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `popup_translations_popup_id_language_slug_unique` (`popup_id`,`language_slug`),
  ADD KEY `popup_translations_language_slug_foreign` (`language_slug`);

--
-- Tablo için indeksler `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posts_status_published_at_index` (`status`,`published_at`),
  ADD KEY `posts_user_id_status_index` (`user_id`,`status`),
  ADD KEY `posts_is_featured_status_index` (`is_featured`,`status`),
  ADD KEY `posts_status_is_featured_published_at_index` (`status`,`is_featured`,`published_at`),
  ADD KEY `posts_order_index` (`order`);

--
-- Tablo için indeksler `post_category`
--
ALTER TABLE `post_category`
  ADD PRIMARY KEY (`post_id`,`category_id`),
  ADD KEY `post_category_category_id_foreign` (`category_id`);

--
-- Tablo için indeksler `post_tag`
--
ALTER TABLE `post_tag`
  ADD PRIMARY KEY (`post_id`,`tag_id`),
  ADD KEY `post_tag_tag_id_foreign` (`tag_id`);

--
-- Tablo için indeksler `post_translations`
--
ALTER TABLE `post_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_translations_post_lang_unique` (`post_id`,`language_slug`),
  ADD UNIQUE KEY `post_translations_slug_lang_unique` (`slug`,`language_slug`),
  ADD KEY `post_translations_language_slug_slug_index` (`language_slug`,`slug`),
  ADD KEY `post_translations_post_id_index` (`post_id`),
  ADD KEY `post_translations_language_slug_post_id_index` (`language_slug`,`post_id`);

--
-- Tablo için indeksler `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_status_published_at_index` (`status`,`published_at`),
  ADD KEY `products_is_featured_status_index` (`is_featured`,`status`),
  ADD KEY `products_brand_status_index` (`brand`,`status`),
  ADD KEY `products_price_status_index` (`price`,`status`),
  ADD KEY `products_order_index` (`order`),
  ADD KEY `products_status_is_featured_published_at_index` (`status`,`is_featured`,`published_at`);

--
-- Tablo için indeksler `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `product_category_category_id_foreign` (`category_id`);

--
-- Tablo için indeksler `product_tag`
--
ALTER TABLE `product_tag`
  ADD PRIMARY KEY (`product_id`,`tag_id`),
  ADD KEY `product_tag_tag_id_foreign` (`tag_id`);

--
-- Tablo için indeksler `product_translations`
--
ALTER TABLE `product_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_translations_product_id_language_slug_unique` (`product_id`,`language_slug`),
  ADD UNIQUE KEY `product_translations_slug_language_slug_unique` (`slug`,`language_slug`),
  ADD KEY `product_translations_language_slug_slug_index` (`language_slug`,`slug`),
  ADD KEY `product_translations_product_id_index` (`product_id`),
  ADD KEY `product_translations_language_slug_title_index` (`language_slug`,`title`);

--
-- Tablo için indeksler `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_permissions_role_permission_id_unique` (`role`,`permission_id`),
  ADD KEY `role_permissions_permission_id_foreign` (`permission_id`);

--
-- Tablo için indeksler `slides`
--
ALTER TABLE `slides`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `slide_translations`
--
ALTER TABLE `slide_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slide_translations_slide_id_language_slug_unique` (`slide_id`,`language_slug`),
  ADD KEY `slide_translations_language_slug_index` (`language_slug`);

--
-- Tablo için indeksler `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tags_status_usage_count_index` (`status`,`usage_count`);

--
-- Tablo için indeksler `tag_translations`
--
ALTER TABLE `tag_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tag_translations_tag_lang_unique` (`tag_id`,`language_slug`),
  ADD UNIQUE KEY `tag_translations_slug_lang_unique` (`slug`,`language_slug`),
  ADD KEY `tag_translations_language_slug_slug_index` (`language_slug`,`slug`),
  ADD KEY `tag_translations_language_slug_tag_id_index` (`language_slug`,`tag_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `category_translations`
--
ALTER TABLE `category_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Tablo için AUTO_INCREMENT değeri `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `menu_item_translations`
--
ALTER TABLE `menu_item_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Tablo için AUTO_INCREMENT değeri `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Tablo için AUTO_INCREMENT değeri `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `page_translations`
--
ALTER TABLE `page_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Tablo için AUTO_INCREMENT değeri `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `popups`
--
ALTER TABLE `popups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `popup_translations`
--
ALTER TABLE `popup_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Tablo için AUTO_INCREMENT değeri `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `post_translations`
--
ALTER TABLE `post_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Tablo için AUTO_INCREMENT değeri `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `product_translations`
--
ALTER TABLE `product_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Tablo için AUTO_INCREMENT değeri `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `slides`
--
ALTER TABLE `slides`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `slide_translations`
--
ALTER TABLE `slide_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Tablo için AUTO_INCREMENT değeri `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `tag_translations`
--
ALTER TABLE `tag_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `category_translations`
--
ALTER TABLE `category_translations`
  ADD CONSTRAINT `category_translations_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_translations_language_slug_foreign` FOREIGN KEY (`language_slug`) REFERENCES `languages` (`slug`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menu_items_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `menu_item_translations`
--
ALTER TABLE `menu_item_translations`
  ADD CONSTRAINT `menu_item_translations_language_slug_foreign` FOREIGN KEY (`language_slug`) REFERENCES `languages` (`slug`) ON DELETE CASCADE,
  ADD CONSTRAINT `menu_item_translations_menu_item_id_foreign` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `page_translations`
--
ALTER TABLE `page_translations`
  ADD CONSTRAINT `page_translations_language_slug_foreign` FOREIGN KEY (`language_slug`) REFERENCES `languages` (`slug`) ON DELETE CASCADE,
  ADD CONSTRAINT `page_translations_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `popup_translations`
--
ALTER TABLE `popup_translations`
  ADD CONSTRAINT `popup_translations_language_slug_foreign` FOREIGN KEY (`language_slug`) REFERENCES `languages` (`slug`) ON DELETE CASCADE,
  ADD CONSTRAINT `popup_translations_popup_id_foreign` FOREIGN KEY (`popup_id`) REFERENCES `popups` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `post_category`
--
ALTER TABLE `post_category`
  ADD CONSTRAINT `post_category_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_category_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `post_tag`
--
ALTER TABLE `post_tag`
  ADD CONSTRAINT `post_tag_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `post_translations`
--
ALTER TABLE `post_translations`
  ADD CONSTRAINT `post_translations_language_slug_foreign` FOREIGN KEY (`language_slug`) REFERENCES `languages` (`slug`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_translations_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `product_category`
--
ALTER TABLE `product_category`
  ADD CONSTRAINT `product_category_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_category_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `product_tag`
--
ALTER TABLE `product_tag`
  ADD CONSTRAINT `product_tag_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `product_translations`
--
ALTER TABLE `product_translations`
  ADD CONSTRAINT `product_translations_language_slug_foreign` FOREIGN KEY (`language_slug`) REFERENCES `languages` (`slug`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_translations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `slide_translations`
--
ALTER TABLE `slide_translations`
  ADD CONSTRAINT `slide_translations_language_slug_foreign` FOREIGN KEY (`language_slug`) REFERENCES `languages` (`slug`) ON DELETE CASCADE,
  ADD CONSTRAINT `slide_translations_slide_id_foreign` FOREIGN KEY (`slide_id`) REFERENCES `slides` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `tag_translations`
--
ALTER TABLE `tag_translations`
  ADD CONSTRAINT `tag_translations_language_slug_foreign` FOREIGN KEY (`language_slug`) REFERENCES `languages` (`slug`) ON DELETE CASCADE,
  ADD CONSTRAINT `tag_translations_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
