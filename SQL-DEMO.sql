-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 06 Tem 2026, 01:02:47
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
(9, NULL, 0, NULL, NULL, NULL, 1, 0, 0, 1, '2026-07-04 12:13:20', '2026-07-04 12:13:20'),
(10, NULL, 0, NULL, NULL, NULL, 1, 0, 0, 2, '2026-07-04 12:13:21', '2026-07-04 12:13:21'),
(11, NULL, 0, NULL, NULL, NULL, 1, 0, 0, 3, '2026-07-04 12:13:21', '2026-07-04 12:13:21'),
(12, NULL, 0, NULL, NULL, NULL, 1, 0, 0, 4, '2026-07-04 12:13:22', '2026-07-04 12:13:22'),
(13, NULL, 0, NULL, NULL, NULL, 1, 0, 0, 5, '2026-07-04 12:13:22', '2026-07-04 12:13:22');

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
(11, 9, 'tr', 'Teknoloji', 'teknoloji', NULL, NULL, NULL, '2026-07-04 12:13:20', '2026-07-04 12:13:20'),
(12, 9, 'en', 'Teknoloji EN', 'teknoloji-en', NULL, NULL, NULL, '2026-07-04 12:13:20', '2026-07-04 12:13:20'),
(13, 10, 'tr', 'Tasarim', 'tasarim', NULL, NULL, NULL, '2026-07-04 12:13:21', '2026-07-04 12:13:21'),
(14, 10, 'en', 'Tasarim EN', 'tasarim-en', NULL, NULL, NULL, '2026-07-04 12:13:21', '2026-07-04 12:13:21'),
(15, 11, 'tr', 'Yazilim', 'yazilim', NULL, NULL, NULL, '2026-07-04 12:13:21', '2026-07-04 12:13:21'),
(16, 11, 'en', 'Yazilim EN', 'yazilim-en', NULL, NULL, NULL, '2026-07-04 12:13:21', '2026-07-04 12:13:21'),
(17, 12, 'tr', 'Donanim', 'donanim', NULL, NULL, NULL, '2026-07-04 12:13:22', '2026-07-04 12:13:22'),
(18, 12, 'en', 'Donanim EN', 'donanim-en', NULL, NULL, NULL, '2026-07-04 12:13:22', '2026-07-04 12:13:22'),
(19, 13, 'tr', 'Haberler', 'haberler', NULL, NULL, NULL, '2026-07-04 12:13:22', '2026-07-04 12:13:22'),
(20, 13, 'en', 'Haberler EN', 'haberler-en', NULL, NULL, NULL, '2026-07-04 12:13:22', '2026-07-04 12:13:22'),
(21, 11, 'de', 'Yazilim (DE)', 'yazilim-de', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(22, 9, 'de', 'Teknoloji (DE)', 'teknoloji-de', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(23, 10, 'de', 'Tasarim (DE)', 'tasarim-de', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(24, 13, 'de', 'Haberler (DE)', 'haberler-de', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(25, 12, 'de', 'Donanim (DE)', 'donanim-de', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(26, 11, 'fr', 'Yazilim (FR)', 'yazilim-fr', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(27, 9, 'fr', 'Teknoloji (FR)', 'teknoloji-fr', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(28, 10, 'fr', 'Tasarim (FR)', 'tasarim-fr', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(29, 13, 'fr', 'Haberler (FR)', 'haberler-fr', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(30, 12, 'fr', 'Donanim (FR)', 'donanim-fr', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(31, 11, 'es', 'Yazilim (ES)', 'yazilim-es', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(32, 9, 'es', 'Teknoloji (ES)', 'teknoloji-es', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(33, 10, 'es', 'Tasarim (ES)', 'tasarim-es', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(34, 13, 'es', 'Haberler (ES)', 'haberler-es', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(35, 12, 'es', 'Donanim (ES)', 'donanim-es', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59');

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
(3, 'Almanca', 'de', 'de', 1, 0, '2026-07-04 12:13:02', '2026-07-04 12:16:35'),
(4, 'Fransizca', 'fr', 'fr', 1, 0, '2026-07-04 12:13:02', '2026-07-04 12:16:35'),
(5, 'Ispanyolca', 'es', 'es', 1, 0, '2026-07-04 12:13:02', '2026-07-04 12:16:36');

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
(1, NULL, NULL, 0, 'always', '[]', 0, '2026-07-04 12:14:49', '2026-07-06 00:07:25'),
(2, NULL, NULL, 0, 'always', '[]', 0, '2026-07-04 12:14:50', '2026-07-06 00:07:29'),
(3, NULL, NULL, 0, 'always', '[]', 0, '2026-07-04 12:14:50', '2026-07-06 00:07:32'),
(4, NULL, NULL, 0, 'always', '[]', 0, '2026-07-04 12:14:50', '2026-07-06 00:07:36'),
(5, NULL, NULL, 0, 'always', '[]', 0, '2026-07-04 12:14:51', '2026-07-06 00:07:39');

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
(1, 1, 'tr', 'Popup 1', 'Popup 1 metni', '', 'Tamam', '/tr', '2026-07-04 12:14:49', '2026-07-06 00:07:25'),
(2, 2, 'tr', 'Popup 2', 'Popup 2 metni', '', 'Tamam', '/tr', '2026-07-04 12:14:50', '2026-07-06 00:07:29'),
(3, 3, 'tr', 'Popup 3', 'Popup 3 metni', '', 'Tamam', '/tr', '2026-07-04 12:14:50', '2026-07-06 00:07:32'),
(4, 4, 'tr', 'Popup 4', 'Popup 4 metni', '', 'Tamam', '/tr', '2026-07-04 12:14:50', '2026-07-06 00:07:36'),
(5, 5, 'tr', 'Popup 5', 'Popup 5 metni', '', 'Tamam', '/tr', '2026-07-04 12:14:51', '2026-07-06 00:07:39'),
(6, 5, 'en', 'Popup 5 (EN)', 'Popup 5 metni', '', 'Tamam', '/tr', '2026-07-04 12:39:59', '2026-07-06 00:07:39'),
(7, 4, 'en', 'Popup 4 (EN)', 'Popup 4 metni', '', 'Tamam', '/tr', '2026-07-04 12:39:59', '2026-07-06 00:07:36'),
(8, 3, 'en', 'Popup 3 (EN)', 'Popup 3 metni', '', 'Tamam', '/tr', '2026-07-04 12:39:59', '2026-07-06 00:07:32'),
(9, 2, 'en', 'Popup 2 (EN)', 'Popup 2 metni', '', 'Tamam', '/tr', '2026-07-04 12:39:59', '2026-07-06 00:07:29'),
(10, 1, 'en', 'Popup 1 (EN)', 'Popup 1 metni', '', 'Tamam', '/tr', '2026-07-04 12:39:59', '2026-07-06 00:07:25'),
(11, 5, 'de', 'Popup 5 (DE)', 'Popup 5 metni', '', 'Tamam', '/tr', '2026-07-04 12:39:59', '2026-07-06 00:07:39'),
(12, 4, 'de', 'Popup 4 (DE)', 'Popup 4 metni', '', 'Tamam', '/tr', '2026-07-04 12:39:59', '2026-07-06 00:07:36'),
(13, 3, 'de', 'Popup 3 (DE)', 'Popup 3 metni', '', 'Tamam', '/tr', '2026-07-04 12:39:59', '2026-07-06 00:07:32'),
(14, 2, 'de', 'Popup 2 (DE)', 'Popup 2 metni', '', 'Tamam', '/tr', '2026-07-04 12:39:59', '2026-07-06 00:07:29'),
(15, 1, 'de', 'Popup 1 (DE)', 'Popup 1 metni', '', 'Tamam', '/tr', '2026-07-04 12:39:59', '2026-07-06 00:07:25'),
(16, 5, 'fr', 'Popup 5 (FR)', 'Popup 5 metni', '', 'Tamam', '/tr', '2026-07-04 12:39:59', '2026-07-06 00:07:39'),
(17, 4, 'fr', 'Popup 4 (FR)', 'Popup 4 metni', '', 'Tamam', '/tr', '2026-07-04 12:39:59', '2026-07-06 00:07:36'),
(18, 3, 'fr', 'Popup 3 (FR)', 'Popup 3 metni', '', 'Tamam', '/tr', '2026-07-04 12:39:59', '2026-07-06 00:07:32'),
(19, 2, 'fr', 'Popup 2 (FR)', 'Popup 2 metni', '', 'Tamam', '/tr', '2026-07-04 12:39:59', '2026-07-06 00:07:29'),
(20, 1, 'fr', 'Popup 1 (FR)', 'Popup 1 metni', '', 'Tamam', '/tr', '2026-07-04 12:39:59', '2026-07-06 00:07:25'),
(21, 5, 'es', 'Popup 5 (ES)', 'Popup 5 metni', '', 'Tamam', '/tr', '2026-07-04 12:39:59', '2026-07-06 00:07:39'),
(22, 4, 'es', 'Popup 4 (ES)', 'Popup 4 metni', '', 'Tamam', '/tr', '2026-07-04 12:39:59', '2026-07-06 00:07:36'),
(23, 3, 'es', 'Popup 3 (ES)', 'Popup 3 metni', '', 'Tamam', '/tr', '2026-07-04 12:39:59', '2026-07-06 00:07:32'),
(24, 2, 'es', 'Popup 2 (ES)', 'Popup 2 metni', '', 'Tamam', '/tr', '2026-07-04 12:39:59', '2026-07-06 00:07:29'),
(25, 1, 'es', 'Popup 1 (ES)', 'Popup 1 metni', '', 'Tamam', '/tr', '2026-07-04 12:39:59', '2026-07-06 00:07:25');

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
(27, 1, 0, NULL, '[]', 1, 0, 1, 4, NULL, '2026-07-04 12:13:45', '2026-07-04 12:40:17'),
(28, 1, 0, NULL, '[]', 0, 0, 1, 4, NULL, '2026-07-04 12:13:46', '2026-07-05 23:53:32'),
(29, 1, 0, NULL, '[]', 0, 0, 1, 3, NULL, '2026-07-04 12:13:46', '2026-07-05 23:54:57'),
(30, 1, 0, NULL, '[]', 0, 0, 1, 4, NULL, '2026-07-04 12:13:47', '2026-07-05 23:53:37'),
(31, 1, 0, NULL, '[]', 0, 0, 1, 1, NULL, '2026-07-04 12:13:47', '2026-07-05 23:54:47');

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
(27, 9),
(28, 10),
(29, 11),
(30, 12),
(31, 13);

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
(27, 6),
(28, 7),
(29, 8),
(30, 9),
(31, 10);

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
(27, 27, 'tr', 'Blog Yazisi 1', 'blog-yazisi-1', 'Bu 1 numarali blog yazisinin kisa aciklamasi.', '<h2>Baslik 1</h2><p>Bu blog yazisinin icerigidir. Numara 1.</p>', NULL, NULL, NULL, '2026-07-04 12:13:45', '2026-07-04 12:13:45'),
(28, 27, 'en', 'Blog Post 1', 'blog-post-1', NULL, '<p>English content 1.</p>', NULL, NULL, NULL, '2026-07-04 12:13:45', '2026-07-04 12:13:45'),
(29, 28, 'tr', 'Blog Yazisi 2', 'blog-yazisi-2', 'Bu 2 numarali blog yazisinin kisa aciklamasi.', '<h2>Baslik 2</h2><p>Bu blog yazisinin icerigidir. Numara 2.</p>', NULL, NULL, NULL, '2026-07-04 12:13:46', '2026-07-04 12:13:46'),
(30, 28, 'en', 'Blog Post 2', 'blog-post-2', NULL, '<p>English content 2.</p>', NULL, NULL, NULL, '2026-07-04 12:13:46', '2026-07-04 12:13:46'),
(31, 29, 'tr', 'Blog Yazisi 3', 'blog-yazisi-3', 'Bu 3 numarali blog yazisinin kisa aciklamasi.', '<h2>Baslik 3</h2><p>Bu blog yazisinin icerigidir. Numara 3.</p>', NULL, NULL, NULL, '2026-07-04 12:13:46', '2026-07-04 12:13:46'),
(32, 29, 'en', 'Blog Post 3', 'blog-post-3', NULL, '<p>English content 3.</p>', NULL, NULL, NULL, '2026-07-04 12:13:46', '2026-07-04 12:13:46'),
(33, 30, 'tr', 'Blog Yazisi 4', 'blog-yazisi-4', 'Bu 4 numarali blog yazisinin kisa aciklamasi.', '<h2>Baslik 4</h2><p>Bu blog yazisinin icerigidir. Numara 4.</p>', NULL, NULL, NULL, '2026-07-04 12:13:47', '2026-07-04 12:13:47'),
(34, 30, 'en', 'Blog Post 4', 'blog-post-4', NULL, '<p>English content 4.</p>', NULL, NULL, NULL, '2026-07-04 12:13:47', '2026-07-04 12:13:47'),
(35, 31, 'tr', 'Blog Yazisi 5', 'blog-yazisi-5', 'Bu 5 numarali blog yazisinin kisa aciklamasi.', '<h2>Baslik 5</h2><p>Bu blog yazisinin icerigidir. Numara 5.</p>', NULL, NULL, NULL, '2026-07-04 12:13:47', '2026-07-04 12:13:47'),
(36, 31, 'en', 'Blog Post 5', 'blog-post-5', NULL, '<p>English content 5.</p>', NULL, NULL, NULL, '2026-07-04 12:13:47', '2026-07-04 12:13:47'),
(37, 27, 'de', 'Blog Yazisi 1 (DE)', 'blog-yazisi-1-de', 'Bu 1 numarali blog yazisinin kisa aciklamasi.', '<h2>Baslik 1</h2><p>Bu blog yazisinin icerigidir. Numara 1.</p>', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(38, 28, 'de', 'Blog Yazisi 2 (DE)', 'blog-yazisi-2-de', 'Bu 2 numarali blog yazisinin kisa aciklamasi.', '<h2>Baslik 2</h2><p>Bu blog yazisinin icerigidir. Numara 2.</p>', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(39, 29, 'de', 'Blog Yazisi 3 (DE)', 'blog-yazisi-3-de', 'Bu 3 numarali blog yazisinin kisa aciklamasi.', '<h2>Baslik 3</h2><p>Bu blog yazisinin icerigidir. Numara 3.</p>', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(40, 30, 'de', 'Blog Yazisi 4 (DE)', 'blog-yazisi-4-de', 'Bu 4 numarali blog yazisinin kisa aciklamasi.', '<h2>Baslik 4</h2><p>Bu blog yazisinin icerigidir. Numara 4.</p>', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(41, 31, 'de', 'Blog Yazisi 5 (DE)', 'blog-yazisi-5-de', 'Bu 5 numarali blog yazisinin kisa aciklamasi.', '<h2>Baslik 5</h2><p>Bu blog yazisinin icerigidir. Numara 5.</p>', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(42, 27, 'fr', 'Blog Yazisi 1 (FR)', 'blog-yazisi-1-fr', 'Bu 1 numarali blog yazisinin kisa aciklamasi.', '<h2>Baslik 1</h2><p>Bu blog yazisinin icerigidir. Numara 1.</p>', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(43, 28, 'fr', 'Blog Yazisi 2 (FR)', 'blog-yazisi-2-fr', 'Bu 2 numarali blog yazisinin kisa aciklamasi.', '<h2>Baslik 2</h2><p>Bu blog yazisinin icerigidir. Numara 2.</p>', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(44, 29, 'fr', 'Blog Yazisi 3 (FR)', 'blog-yazisi-3-fr', 'Bu 3 numarali blog yazisinin kisa aciklamasi.', '<h2>Baslik 3</h2><p>Bu blog yazisinin icerigidir. Numara 3.</p>', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(45, 30, 'fr', 'Blog Yazisi 4 (FR)', 'blog-yazisi-4-fr', 'Bu 4 numarali blog yazisinin kisa aciklamasi.', '<h2>Baslik 4</h2><p>Bu blog yazisinin icerigidir. Numara 4.</p>', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(46, 31, 'fr', 'Blog Yazisi 5 (FR)', 'blog-yazisi-5-fr', 'Bu 5 numarali blog yazisinin kisa aciklamasi.', '<h2>Baslik 5</h2><p>Bu blog yazisinin icerigidir. Numara 5.</p>', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(47, 27, 'es', 'Blog Yazisi 1 (ES)', 'blog-yazisi-1-es', 'Bu 1 numarali blog yazisinin kisa aciklamasi.', '<h2>Baslik 1</h2><p>Bu blog yazisinin icerigidir. Numara 1.</p>', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(48, 28, 'es', 'Blog Yazisi 2 (ES)', 'blog-yazisi-2-es', 'Bu 2 numarali blog yazisinin kisa aciklamasi.', '<h2>Baslik 2</h2><p>Bu blog yazisinin icerigidir. Numara 2.</p>', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(49, 29, 'es', 'Blog Yazisi 3 (ES)', 'blog-yazisi-3-es', 'Bu 3 numarali blog yazisinin kisa aciklamasi.', '<h2>Baslik 3</h2><p>Bu blog yazisinin icerigidir. Numara 3.</p>', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(50, 30, 'es', 'Blog Yazisi 4 (ES)', 'blog-yazisi-4-es', 'Bu 4 numarali blog yazisinin kisa aciklamasi.', '<h2>Baslik 4</h2><p>Bu blog yazisinin icerigidir. Numara 4.</p>', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(51, 31, 'es', 'Blog Yazisi 5 (ES)', 'blog-yazisi-5-es', 'Bu 5 numarali blog yazisinin kisa aciklamasi.', '<h2>Baslik 5</h2><p>Bu blog yazisinin icerigidir. Numara 5.</p>', NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59');

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
(2, 0, 'uploads/products/2026-07/img1-1783167249-6112f5.webp', '[]', 0, 1, 'Marka 1', 500.00, 'Model-1', 'Soba', 'Pelet', 'Merkezi', 'https://example.com/urun-1', NULL, '2026-07-04 12:14:11', '2026-07-04 12:14:11'),
(3, 0, 'uploads/products/2026-07/img2-1783167251-243bf9.webp', '[]', 0, 1, 'Marka 2', 1000.00, 'Model-2', 'Soba', 'Pelet', 'Merkezi', 'https://example.com/urun-2', NULL, '2026-07-04 12:14:12', '2026-07-04 12:14:12'),
(4, 0, 'uploads/products/2026-07/img3-1783167252-589979.webp', '[]', 0, 1, 'Marka 3', 1500.00, 'Model-3', 'Soba', 'Pelet', 'Merkezi', 'https://example.com/urun-3', NULL, '2026-07-04 12:14:12', '2026-07-04 12:14:12'),
(5, 0, 'uploads/products/2026-07/img4-1783167252-8bd4b5.webp', '[]', 0, 1, 'Marka 4', 2000.00, 'Model-4', 'Soba', 'Pelet', 'Merkezi', 'https://example.com/urun-4', NULL, '2026-07-04 12:14:12', '2026-07-04 12:14:12'),
(6, 0, 'uploads/products/2026-07/img5-1783167253-93573a.webp', '[]', 0, 1, 'Marka 5', 2500.00, 'Model-5', 'Soba', 'Pelet', 'Merkezi', 'https://example.com/urun-5', NULL, '2026-07-04 12:14:13', '2026-07-04 12:14:13');

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
(2, 9),
(3, 10),
(4, 11),
(5, 12),
(6, 13);

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
(2, 6),
(3, 7),
(4, 8),
(5, 9),
(6, 10);

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
(2, 2, 'tr', 'Urun 1', 'urun-1', 'Urun 1 kisa aciklama.', NULL, '<h2>Ozellikler</h2><p>Urun 1 detayli aciklama.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:14:11', '2026-07-04 12:14:11'),
(3, 2, 'en', 'Product 1', 'product-1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:14:11', '2026-07-04 12:14:11'),
(4, 3, 'tr', 'Urun 2', 'urun-2', 'Urun 2 kisa aciklama.', NULL, '<h2>Ozellikler</h2><p>Urun 2 detayli aciklama.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:14:12', '2026-07-04 12:14:12'),
(5, 3, 'en', 'Product 2', 'product-2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:14:12', '2026-07-04 12:14:12'),
(6, 4, 'tr', 'Urun 3', 'urun-3', 'Urun 3 kisa aciklama.', NULL, '<h2>Ozellikler</h2><p>Urun 3 detayli aciklama.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:14:12', '2026-07-04 12:14:12'),
(7, 4, 'en', 'Product 3', 'product-3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:14:12', '2026-07-04 12:14:12'),
(8, 5, 'tr', 'Urun 4', 'urun-4', 'Urun 4 kisa aciklama.', NULL, '<h2>Ozellikler</h2><p>Urun 4 detayli aciklama.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:14:12', '2026-07-04 12:14:12'),
(9, 5, 'en', 'Product 4', 'product-4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:14:12', '2026-07-04 12:14:12'),
(10, 6, 'tr', 'Urun 5', 'urun-5', 'Urun 5 kisa aciklama.', NULL, '<h2>Ozellikler</h2><p>Urun 5 detayli aciklama.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:14:13', '2026-07-04 12:14:13'),
(11, 6, 'en', 'Product 5', 'product-5', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:14:13', '2026-07-04 12:14:13'),
(12, 2, 'de', 'Urun 1 (DE)', 'urun-1-de', 'Urun 1 kisa aciklama.', NULL, '<h2>Ozellikler</h2><p>Urun 1 detayli aciklama.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(13, 3, 'de', 'Urun 2 (DE)', 'urun-2-de', 'Urun 2 kisa aciklama.', NULL, '<h2>Ozellikler</h2><p>Urun 2 detayli aciklama.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(14, 4, 'de', 'Urun 3 (DE)', 'urun-3-de', 'Urun 3 kisa aciklama.', NULL, '<h2>Ozellikler</h2><p>Urun 3 detayli aciklama.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(15, 5, 'de', 'Urun 4 (DE)', 'urun-4-de', 'Urun 4 kisa aciklama.', NULL, '<h2>Ozellikler</h2><p>Urun 4 detayli aciklama.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(16, 6, 'de', 'Urun 5 (DE)', 'urun-5-de', 'Urun 5 kisa aciklama.', NULL, '<h2>Ozellikler</h2><p>Urun 5 detayli aciklama.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(17, 2, 'fr', 'Urun 1 (FR)', 'urun-1-fr', 'Urun 1 kisa aciklama.', NULL, '<h2>Ozellikler</h2><p>Urun 1 detayli aciklama.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(18, 3, 'fr', 'Urun 2 (FR)', 'urun-2-fr', 'Urun 2 kisa aciklama.', NULL, '<h2>Ozellikler</h2><p>Urun 2 detayli aciklama.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(19, 4, 'fr', 'Urun 3 (FR)', 'urun-3-fr', 'Urun 3 kisa aciklama.', NULL, '<h2>Ozellikler</h2><p>Urun 3 detayli aciklama.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(20, 5, 'fr', 'Urun 4 (FR)', 'urun-4-fr', 'Urun 4 kisa aciklama.', NULL, '<h2>Ozellikler</h2><p>Urun 4 detayli aciklama.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(21, 6, 'fr', 'Urun 5 (FR)', 'urun-5-fr', 'Urun 5 kisa aciklama.', NULL, '<h2>Ozellikler</h2><p>Urun 5 detayli aciklama.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(22, 2, 'es', 'Urun 1 (ES)', 'urun-1-es', 'Urun 1 kisa aciklama.', NULL, '<h2>Ozellikler</h2><p>Urun 1 detayli aciklama.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(23, 3, 'es', 'Urun 2 (ES)', 'urun-2-es', 'Urun 2 kisa aciklama.', NULL, '<h2>Ozellikler</h2><p>Urun 2 detayli aciklama.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(24, 4, 'es', 'Urun 3 (ES)', 'urun-3-es', 'Urun 3 kisa aciklama.', NULL, '<h2>Ozellikler</h2><p>Urun 3 detayli aciklama.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(25, 5, 'es', 'Urun 4 (ES)', 'urun-4-es', 'Urun 4 kisa aciklama.', NULL, '<h2>Ozellikler</h2><p>Urun 4 detayli aciklama.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(26, 6, 'es', 'Urun 5 (ES)', 'urun-5-es', 'Urun 5 kisa aciklama.', NULL, '<h2>Ozellikler</h2><p>Urun 5 detayli aciklama.</p>', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59');

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
(3, 'image', 'uploads/slides/2026-07/img1-1783167268-320c09.webp', 'left', 12, 48, 16, 1, 1, '2026-07-04 12:14:28', '2026-07-04 12:14:28'),
(4, 'image', 'uploads/slides/2026-07/img2-1783167269-ecba5d.webp', 'right', 12, 48, 16, 2, 1, '2026-07-04 12:14:29', '2026-07-04 12:14:29'),
(5, 'image', 'uploads/slides/2026-07/img3-1783167269-e0fe8f.webp', 'left', 12, 48, 16, 3, 1, '2026-07-04 12:14:29', '2026-07-04 12:14:29'),
(6, 'image', 'uploads/slides/2026-07/img4-1783167270-0506f1.webp', 'right', 12, 48, 16, 4, 1, '2026-07-04 12:14:30', '2026-07-04 12:14:30'),
(7, 'image', 'uploads/slides/2026-07/img5-1783167270-35853a.webp', 'left', 12, 48, 16, 5, 1, '2026-07-04 12:14:30', '2026-07-04 12:14:30');

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
(4, 3, 'tr', 'Etiket 1', 'Slayt 1', 'Slayt 1 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:14:29', '2026-07-04 12:14:29'),
(5, 4, 'tr', 'Etiket 2', 'Slayt 2', 'Slayt 2 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:14:29', '2026-07-04 12:14:29'),
(6, 5, 'tr', 'Etiket 3', 'Slayt 3', 'Slayt 3 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:14:29', '2026-07-04 12:14:29'),
(7, 6, 'tr', 'Etiket 4', 'Slayt 4', 'Slayt 4 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:14:30', '2026-07-04 12:14:30'),
(8, 7, 'tr', 'Etiket 5', 'Slayt 5', 'Slayt 5 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:14:30', '2026-07-04 12:14:30'),
(9, 7, 'en', 'Etiket 5', 'Slayt 5 (EN)', 'Slayt 5 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(10, 6, 'en', 'Etiket 4', 'Slayt 4 (EN)', 'Slayt 4 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(11, 5, 'en', 'Etiket 3', 'Slayt 3 (EN)', 'Slayt 3 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(12, 4, 'en', 'Etiket 2', 'Slayt 2 (EN)', 'Slayt 2 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(13, 3, 'en', 'Etiket 1', 'Slayt 1 (EN)', 'Slayt 1 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(14, 7, 'de', 'Etiket 5', 'Slayt 5 (DE)', 'Slayt 5 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(15, 6, 'de', 'Etiket 4', 'Slayt 4 (DE)', 'Slayt 4 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(16, 5, 'de', 'Etiket 3', 'Slayt 3 (DE)', 'Slayt 3 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(17, 4, 'de', 'Etiket 2', 'Slayt 2 (DE)', 'Slayt 2 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(18, 3, 'de', 'Etiket 1', 'Slayt 1 (DE)', 'Slayt 1 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(19, 7, 'fr', 'Etiket 5', 'Slayt 5 (FR)', 'Slayt 5 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(20, 6, 'fr', 'Etiket 4', 'Slayt 4 (FR)', 'Slayt 4 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(21, 5, 'fr', 'Etiket 3', 'Slayt 3 (FR)', 'Slayt 3 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(22, 4, 'fr', 'Etiket 2', 'Slayt 2 (FR)', 'Slayt 2 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(23, 3, 'fr', 'Etiket 1', 'Slayt 1 (FR)', 'Slayt 1 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(24, 7, 'es', 'Etiket 5', 'Slayt 5 (ES)', 'Slayt 5 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(25, 6, 'es', 'Etiket 4', 'Slayt 4 (ES)', 'Slayt 4 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(26, 5, 'es', 'Etiket 3', 'Slayt 3 (ES)', 'Slayt 3 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(27, 4, 'es', 'Etiket 2', 'Slayt 2 (ES)', 'Slayt 2 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(28, 3, 'es', 'Etiket 1', 'Slayt 1 (ES)', 'Slayt 1 alt basligi', 'Kesfet', '/tr/products', '2026-07-04 12:39:59', '2026-07-04 12:39:59');

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
(6, NULL, 1, 0, '2026-07-04 12:13:23', '2026-07-04 12:13:23'),
(7, NULL, 1, 0, '2026-07-04 12:13:23', '2026-07-04 12:13:23'),
(8, NULL, 1, 0, '2026-07-04 12:13:24', '2026-07-04 12:13:24'),
(9, NULL, 1, 0, '2026-07-04 12:13:24', '2026-07-04 12:13:24'),
(10, NULL, 1, 0, '2026-07-04 12:13:24', '2026-07-04 12:13:24');

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
(6, 6, 'tr', 'PHP', 'php', NULL, NULL, NULL, NULL, '2026-07-04 12:13:23', '2026-07-04 12:13:23'),
(7, 7, 'tr', 'JS', 'js', NULL, NULL, NULL, NULL, '2026-07-04 12:13:23', '2026-07-04 12:13:23'),
(8, 8, 'tr', 'CSS', 'css', NULL, NULL, NULL, NULL, '2026-07-04 12:13:24', '2026-07-04 12:13:24'),
(9, 9, 'tr', 'HTML', 'html', NULL, NULL, NULL, NULL, '2026-07-04 12:13:24', '2026-07-04 12:13:24'),
(10, 10, 'tr', 'SQL', 'sql', NULL, NULL, NULL, NULL, '2026-07-04 12:13:24', '2026-07-04 12:13:24'),
(11, 10, 'en', 'SQL (EN)', 'sql-en', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(12, 6, 'en', 'PHP (EN)', 'php-en', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(13, 7, 'en', 'JS (EN)', 'js-en', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(14, 9, 'en', 'HTML (EN)', 'html-en', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(15, 8, 'en', 'CSS (EN)', 'css-en', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(16, 10, 'de', 'SQL (DE)', 'sql-de', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(17, 6, 'de', 'PHP (DE)', 'php-de', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(18, 7, 'de', 'JS (DE)', 'js-de', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(19, 9, 'de', 'HTML (DE)', 'html-de', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(20, 8, 'de', 'CSS (DE)', 'css-de', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(21, 10, 'fr', 'SQL (FR)', 'sql-fr', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(22, 6, 'fr', 'PHP (FR)', 'php-fr', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(23, 7, 'fr', 'JS (FR)', 'js-fr', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(24, 9, 'fr', 'HTML (FR)', 'html-fr', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(25, 8, 'fr', 'CSS (FR)', 'css-fr', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(26, 10, 'es', 'SQL (ES)', 'sql-es', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(27, 6, 'es', 'PHP (ES)', 'php-es', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(28, 7, 'es', 'JS (ES)', 'js-es', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(29, 9, 'es', 'HTML (ES)', 'html-es', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59'),
(30, 8, 'es', 'CSS (ES)', 'css-es', NULL, NULL, NULL, NULL, '2026-07-04 12:39:59', '2026-07-04 12:39:59');

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Tablo için AUTO_INCREMENT değeri `category_translations`
--
ALTER TABLE `category_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Tablo için AUTO_INCREMENT değeri `post_translations`
--
ALTER TABLE `post_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- Tablo için AUTO_INCREMENT değeri `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `product_translations`
--
ALTER TABLE `product_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Tablo için AUTO_INCREMENT değeri `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `slides`
--
ALTER TABLE `slides`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `slide_translations`
--
ALTER TABLE `slide_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Tablo için AUTO_INCREMENT değeri `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `tag_translations`
--
ALTER TABLE `tag_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

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
