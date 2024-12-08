-- Adminer 4.8.1 MySQL 5.7.33 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `accesses`;
CREATE TABLE `accesses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `access_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `books`;
CREATE TABLE `books` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_revisi` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `penulis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_terbit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penerbit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `books` (`id`, `judul`, `no_revisi`, `category_id`, `penulis`, `tahun_terbit`, `penerbit`, `created_at`, `updated_at`, `image_url`) VALUES
(1,	'Tutorial Flutter',	1,	1,	'Doan Joe',	'2023',	'Angkasa Mulia',	NULL,	NULL,	NULL),
(2,	'Tutorial Laravel',	1,	2,	'Eko Andrian',	'2007',	'Doan Joe',	NULL,	NULL,	NULL);

DROP TABLE IF EXISTS `book_stocks`;
CREATE TABLE `book_stocks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `book_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `book_stocks` (`id`, `book_id`, `category_id`, `total`, `created_at`, `updated_at`) VALUES
(1,	NULL,	1,	2,	NULL,	'2024-12-08 07:13:04'),
(2,	NULL,	2,	21,	NULL,	'2024-12-08 07:13:04');

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('jojo@student.uir.ac.id|127.0.0.1',	'i:1;',	1733635145),
('jojo@student.uir.ac.id|127.0.0.1:timer',	'i:1733635145;',	1733635145),
('roni@gmail.com|127.0.0.1',	'i:1;',	1733463728),
('roni@gmail.com|127.0.0.1:timer',	'i:1733463728;',	1733463728);

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`, `category_name`, `created_at`, `updated_at`) VALUES
(1,	'Mobile',	NULL,	'2024-12-06 10:01:07'),
(3,	'AI',	NULL,	NULL),
(4,	'Networking',	'2024-12-06 00:28:05',	'2024-12-06 00:28:05'),
(5,	'Augmented Reality',	'2024-12-06 00:31:01',	'2024-12-06 00:31:01'),
(6,	'Virtual Reality',	'2024-12-06 00:43:17',	'2024-12-06 00:43:17'),
(7,	'IoT',	'2024-12-06 00:51:17',	'2024-12-06 00:51:17');

DROP TABLE IF EXISTS `collagers`;
CREATE TABLE `collagers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `npm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `collagers_npm_unique` (`npm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `collagers` (`id`, `npm`, `nama`, `email`, `created_at`, `updated_at`) VALUES
(1,	'312313',	'Yazid Kurnia Ramadhan',	'yazidkurniaramadhan@student.uir.ac.id',	NULL,	NULL),
(2,	'23424223',	'roni',	'roni@gmail.com',	NULL,	NULL),
(3,	'123123',	'jojo',	'jojo@student.uir.ac.id',	'2024-12-07 22:11:11',	'2024-12-07 22:11:11'),
(5,	'32123',	'jee',	'jeje@student.uir.ac.id',	'2024-12-07 23:09:10',	'2024-12-07 23:09:10'),
(6,	'2342343',	'jiji',	'jiji@student.uir.ac.id',	'2024-12-07 23:14:05',	'2024-12-07 23:14:05');

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `lectures`;
CREATE TABLE `lectures` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nidn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lectures_nidn_unique` (`nidn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lectures` (`id`, `nidn`, `nama`, `email`, `created_at`, `updated_at`) VALUES
(1,	'234234',	'Doan Joe',	'doan.joe@gmail.com',	NULL,	NULL),
(2,	'3211232',	'jaka',	'jaka@uir.ac.id',	'2024-12-07 22:16:20',	'2024-12-07 22:16:20');

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1,	'0001_01_01_000000_create_users_table',	1),
(2,	'0001_01_01_000001_create_cache_table',	1),
(3,	'0001_01_01_000002_create_jobs_table',	1),
(4,	'2024_10_25_172207_create_table_roles',	1),
(5,	'2024_10_25_193124_create_role_accessces_table',	1),
(6,	'2024_10_25_201503_create_accesses_table',	1),
(7,	'2024_11_04_134215_create_lectures_table',	1),
(8,	'2024_11_04_134434_create_collagers_table',	1),
(9,	'2024_11_04_134632_create_books_table',	1),
(10,	'2024_11_04_135036_create_book_stocks_table',	1),
(11,	'2024_11_04_135229_create_transactions_table',	1),
(12,	'2024_11_04_135604_create_transaction_details_table',	1),
(13,	'2024_11_08_075610_add_column_image_url_for_table_books',	2),
(14,	'2024_11_09_195231_add_column_status_approval_for_transaction',	3),
(15,	'2024_11_22_012212_add_column_nama_kategori_for_stok',	4),
(16,	'2024_11_25_055115_create_categories_table',	5),
(17,	'2024_11_25_055744_update_column_nama_kategori_from_book_stocks',	6),
(18,	'2024_11_25_061228_update_datatype_column_category_id_table_book_stocks',	7),
(19,	'2024_11_26_153225_add_column_qr_url_for_transaction_table',	8),
(20,	'2024_11_29_072550_add_column_category_id_table_books',	9);

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `role_accesses`;
CREATE TABLE `role_accesses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `roleid` int(11) DEFAULT NULL,
  `accessesid` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('grhxrBtqqAoOVXmXq7dt65gTWNuKrbhY3Q0qm0UN',	NULL,	'127.0.0.1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',	'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTmcxMjBRZ0szNEdGa2FRd2dPdHFlMG8yaDJSWkYwMjRIN3hrTEpLciI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3BlbWluamFtYW4tYnVrdSI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',	1733679023);

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `jenis_transaksi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tgl_pinjam` date DEFAULT NULL,
  `tgl_wajib_kembali` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status_approval` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_return` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `transactions` (`id`, `userid`, `book_id`, `jenis_transaksi`, `tgl_pinjam`, `tgl_wajib_kembali`, `created_at`, `updated_at`, `status_approval`, `status_return`, `qr_url`) VALUES
(3,	3,	NULL,	'Peminjaman',	'2024-11-10',	'2024-11-15',	'2024-11-09 12:06:56',	'2024-11-09 12:06:56',	'Waiting',	NULL,	NULL),
(4,	3,	NULL,	'Pengembalian',	'2024-11-10',	'2024-11-15',	'2024-11-09 12:55:42',	'2024-11-09 12:55:42',	'Waiting',	NULL,	NULL),
(5,	3,	NULL,	'Pengembalian',	'2024-11-10',	'2024-11-15',	'2024-11-09 12:56:27',	'2024-11-09 12:56:27',	'Waiting',	NULL,	NULL),
(6,	1,	NULL,	'Peminjaman',	'2024-11-16',	'2024-11-21',	'2024-11-15 14:19:45',	'2024-11-15 14:19:45',	NULL,	NULL,	NULL),
(9,	1,	NULL,	'Peminjaman',	'2024-11-16',	'2024-11-21',	'2024-11-15 15:21:12',	'2024-11-15 15:21:12',	NULL,	NULL,	NULL),
(10,	1,	NULL,	'Pengembalian',	'2024-11-22',	'2024-11-27',	'2024-11-20 20:38:32',	'2024-11-20 20:38:32',	NULL,	NULL,	NULL),
(11,	1,	NULL,	'Pengembalian',	'2024-11-21',	'2024-11-26',	'2024-11-20 20:48:20',	'2024-11-20 20:48:20',	'Waiting',	NULL,	NULL),
(12,	1,	NULL,	'Peminjaman',	'2024-12-21',	'2024-12-26',	'2024-12-20 20:48:27',	'2024-12-20 20:48:27',	'Waiting',	NULL,	NULL),
(15,	4,	NULL,	'Peminjaman',	'2024-11-22',	'2024-11-27',	'2024-11-21 19:31:54',	'2024-12-08 07:10:19',	'Approved',	'Approved',	NULL),
(16,	4,	NULL,	'Peminjaman',	'2024-11-22',	'2024-11-27',	'2024-11-21 20:07:31',	'2024-12-08 07:12:23',	'Approved',	'Approved',	NULL),
(17,	4,	NULL,	'Peminjaman',	'2024-11-25',	'2024-11-30',	'2024-11-24 22:25:34',	'2024-12-08 07:13:04',	'Approved',	'Waiting',	NULL),
(21,	4,	NULL,	'Peminjaman',	'2024-11-26',	'2024-12-01',	'2024-11-26 08:23:21',	'2024-11-29 00:37:08',	'Approved',	NULL,	NULL),
(24,	4,	NULL,	'Peminjaman',	'2024-11-26',	'2024-12-01',	'2024-11-26 08:40:14',	'2024-11-29 01:14:32',	'Approved',	NULL,	'assets/generated_qr/EWR3KTlmy86E.png'),
(25,	4,	NULL,	'Peminjaman',	'2024-11-26',	'2024-12-01',	'2024-11-26 08:45:45',	'2024-12-01 00:59:45',	'Approved',	NULL,	'assets/generated_qr/r54aeKgHmx3s.png'),
(27,	4,	NULL,	'Peminjaman',	'2024-12-01',	'2024-12-06',	'2024-11-30 23:31:42',	'2024-12-08 01:19:40',	'Approved',	'Waiting',	'assets/generated_qr/3kHWnyRSyXDw.png'),
(28,	4,	NULL,	'Peminjaman',	'2024-12-01',	'2024-12-06',	'2024-11-30 23:47:58',	'2024-12-01 01:03:56',	'Approved',	'Waiting',	'assets/generated_qr/adSfdMx5deQl.png'),
(29,	4,	NULL,	'Peminjaman',	'2024-12-01',	'2024-12-06',	'2024-12-01 00:33:14',	'2024-12-01 01:54:10',	'Approved',	'Approved',	'assets/generated_qr/ZN5yI0eTG3ek.png'),
(31,	4,	NULL,	'Peminjaman',	'2024-12-06',	'2024-12-14',	'2024-12-06 10:39:29',	'2024-12-06 10:39:31',	'Waiting',	NULL,	'assets/generated_qr/xdEtiYsTjSSA.png'),
(32,	4,	NULL,	'Peminjaman',	'2024-12-07',	'2024-12-14',	'2024-12-06 11:38:40',	'2024-12-06 11:38:41',	'Waiting',	NULL,	'assets/generated_qr/W3VOycxxTVcP.png');

DROP TABLE IF EXISTS `transaction_details`;
CREATE TABLE `transaction_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `transaction_details` (`id`, `transaction_id`, `book_id`, `created_at`, `updated_at`) VALUES
(3,	4,	2,	'2024-11-09 12:06:56',	'2024-11-09 12:06:56'),
(4,	4,	1,	'2024-11-09 12:55:42',	'2024-11-09 12:55:42'),
(5,	5,	1,	'2024-11-09 12:56:27',	'2024-11-09 12:56:27'),
(6,	6,	1,	'2024-11-15 14:19:45',	'2024-11-15 14:19:45'),
(7,	9,	2,	'2024-11-15 15:21:21',	'2024-11-15 15:21:21'),
(8,	9,	1,	'2024-11-15 15:21:21',	'2024-11-15 15:21:21'),
(9,	10,	2,	'2024-11-20 20:38:32',	'2024-11-20 20:38:32'),
(10,	10,	1,	'2024-11-20 20:38:32',	'2024-11-20 20:38:32'),
(11,	11,	1,	'2024-11-20 20:48:20',	'2024-11-20 20:48:20'),
(12,	11,	2,	'2024-11-20 20:48:22',	'2024-11-20 20:48:22'),
(13,	12,	1,	'2024-11-20 20:48:27',	'2024-11-20 20:48:27'),
(14,	12,	2,	'2024-11-20 20:48:27',	'2024-11-20 20:48:27'),
(19,	15,	1,	'2024-11-21 19:31:54',	'2024-11-21 19:31:54'),
(20,	15,	2,	'2024-11-21 19:31:55',	'2024-11-21 19:31:55'),
(21,	16,	2,	'2024-11-21 20:07:31',	'2024-11-21 20:07:31'),
(22,	16,	1,	'2024-11-21 20:07:31',	'2024-11-21 20:07:31'),
(23,	17,	2,	'2024-11-24 22:25:34',	'2024-11-24 22:25:34'),
(24,	17,	1,	'2024-11-24 22:25:34',	'2024-11-24 22:25:34'),
(25,	21,	1,	'2024-11-26 08:23:21',	'2024-11-26 08:23:21'),
(26,	21,	2,	'2024-11-26 08:23:21',	'2024-11-26 08:23:21'),
(29,	24,	1,	'2024-11-26 08:40:15',	'2024-11-26 08:40:15'),
(30,	24,	2,	'2024-11-26 08:40:15',	'2024-11-26 08:40:15'),
(31,	25,	2,	'2024-11-26 08:45:46',	'2024-11-26 08:45:46'),
(32,	25,	1,	'2024-11-26 08:45:46',	'2024-11-26 08:45:46'),
(34,	27,	1,	'2024-11-30 23:31:45',	'2024-11-30 23:31:45'),
(35,	28,	2,	'2024-11-30 23:47:58',	'2024-11-30 23:47:58'),
(36,	29,	2,	'2024-12-01 00:33:14',	'2024-12-01 00:33:14'),
(38,	31,	1,	'2024-12-06 10:39:31',	'2024-12-06 10:39:31'),
(39,	31,	2,	'2024-12-06 10:39:31',	'2024-12-06 10:39:31'),
(40,	32,	1,	'2024-12-06 11:38:41',	'2024-12-06 11:38:41'),
(41,	32,	2,	'2024-12-06 11:38:41',	'2024-12-06 11:38:41');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `roleid` int(11) DEFAULT NULL,
  `collagerid` int(11) DEFAULT NULL,
  `lectureid` int(11) DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `roleid`, `collagerid`, `lectureid`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4,	'admin',	'admin@gmail.com',	'2024-11-09 15:14:08',	'$2y$12$G1aNQEpmMTgUkBB2wr6sD.T6/JEto/O94tfclxKtM2zSOG5vn/u9W',	1,	NULL,	NULL,	'BlyZ9Z97srcIwkwwK2jrPVXGsF0EyKJ2ZCwqwPAo0aqcfU3t9nNrcRfjvkt7',	'2024-11-09 15:14:08',	'2024-11-09 15:14:08',	NULL),
(6,	'Yazid Kurnia Ramadhan',	'yazidkurniaramadhan@student.uir.ac.id',	'2024-11-23 21:50:18',	'$2y$12$L/pm8nUdBTwzN3HcMYfwp.8mZ7SYHMO4skjU/SjHHF3V5S1i7aAta',	3,	1,	NULL,	NULL,	'2024-11-23 21:50:18',	'2024-11-23 21:50:18',	NULL),
(14,	'Doan Joe',	'doan.joe@gmail.com',	'2024-12-05 23:09:43',	'$2y$12$khakjCJXDjrfRHDgzMiMRug1XGL/Tk3NrkDfJaORO7rUDtBxvZkrO',	1,	NULL,	1,	NULL,	'2024-12-05 23:09:43',	'2024-12-05 23:09:43',	NULL),
(15,	'jojo',	'jojo@student.uir.ac.id',	NULL,	'$2y$12$4NdZjIDWXF9a2DZc4MTl3OPXEv0qyAXmsjvVQHLyMDLd/3nVP.0/m',	3,	NULL,	NULL,	NULL,	'2024-12-07 22:11:13',	'2024-12-07 22:11:13',	NULL),
(16,	'jaka',	'jaka@uir.ac.id',	NULL,	'$2y$12$Rjm5Nyf9lXwfcbSnMjWtJOHlKd2NtDp6cxj2LlM25nf1W.TCXn3By',	2,	NULL,	NULL,	NULL,	'2024-12-07 22:16:20',	'2024-12-07 22:16:20',	NULL),
(23,	'jee',	'jeje@student.uir.ac.id',	NULL,	'$2y$12$N5YjISgPtBCiFaocn9G1L.Ehx2eHhs3rv/N22UbQNwkvbCGMXXzrq',	3,	NULL,	NULL,	NULL,	'2024-12-07 23:09:11',	'2024-12-07 23:09:11',	NULL),
(24,	'jiji',	'jiji@student.uir.ac.id',	'2024-12-07 23:15:45',	'$2y$12$cCPvuZdvxLZ5dRq5NwAOy.MGekpm4xgfFo7HDLnjSDzOfvHxREScW',	3,	6,	NULL,	NULL,	'2024-12-07 23:14:05',	'2024-12-07 23:15:45',	NULL);

-- 2024-12-08 18:32:29
