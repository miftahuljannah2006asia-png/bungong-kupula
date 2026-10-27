-- Skema MySQL/MariaDB Bungong Kupula, setara dengan `php artisan migrate`.
-- Pakai ini hanya jika tidak punya akses terminal: phpMyAdmin -> pilih database -> Import.
-- Jika sudah menjalankan `php artisan migrate`, JANGAN impor file ini.
-- Setelah impor, isi produk contoh lewat admin (menu Produk) atau `php artisan db:seed`.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(24) NOT NULL,
  `name` varchar(191) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `city` varchar(191) NOT NULL,
  `address` text NOT NULL,
  `notes` text NULL,
  `total` bigint unsigned NOT NULL,
  `status` varchar(24) NOT NULL DEFAULT 'baru',
  `ip` varchar(45) NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` varchar(191) NOT NULL,
  `title` varchar(191) NOT NULL,
  `unit` varchar(191) NULL,
  `price` int unsigned NOT NULL,
  `qty` smallint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `contact` varchar(191) NOT NULL,
  `topic` varchar(64) NOT NULL,
  `body` text NOT NULL,
  `ip` varchar(45) NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `recipes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `short_name` varchar(60) NULL,
  `category` varchar(60) NOT NULL,
  `region` varchar(120) NOT NULL,
  `short_desc` varchar(300) NOT NULL,
  `full_story` text NOT NULL,
  `prep_time` varchar(40) NOT NULL,
  `cook_time` varchar(40) NOT NULL,
  `servings` varchar(60) NOT NULL,
  `difficulty` varchar(40) NOT NULL,
  `main_ingredients` json NOT NULL,
  `spice_blend` json NOT NULL,
  `steps` json NOT NULL,
  `chef_tips` text NOT NULL,
  `cultural_note` text NOT NULL,
  `spicy` tinyint unsigned NOT NULL DEFAULT 0,
  `savory` tinyint unsigned NOT NULL DEFAULT 0,
  `sweet` tinyint unsigned NOT NULL DEFAULT 0,
  `aromatic` tinyint unsigned NOT NULL DEFAULT 0,
  `image` varchar(191) NULL,
  `image_position` varchar(30) NOT NULL DEFAULT 'center',
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `recipes_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(191) NOT NULL,
  `title` varchar(191) NOT NULL,
  `artisan` varchar(191) NOT NULL,
  `village` varchar(191) NOT NULL,
  `district` varchar(191) NOT NULL,
  `price` int unsigned NOT NULL,
  `unit` varchar(191) NOT NULL,
  `rating` decimal(2,1) NOT NULL DEFAULT 0.0,
  `reviews` int unsigned NOT NULL DEFAULT 0,
  `description` text NOT NULL,
  `badge` varchar(191) NULL,
  `features` json NOT NULL,
  `image` varchar(191) NULL,
  `image_position` varchar(30) NOT NULL DEFAULT 'center',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`migration`, `batch`) VALUES
  ('2026_10_01_000001_create_orders_table', 1),
  ('2026_10_01_000002_create_messages_table', 1),
  ('2026_10_14_000003_create_recipes_table', 1),
  ('2026_10_20_000004_create_products_table', 1);

SET FOREIGN_KEY_CHECKS = 1;
