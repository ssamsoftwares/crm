-- Adminer 4.8.1 MySQL 10.4.31-MariaDB-log dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(85,	'2014_10_12_000000_create_users_table',	1),
(86,	'2014_10_12_100000_create_password_reset_tokens_table',	1),
(87,	'2019_08_19_000000_create_failed_jobs_table',	1),
(88,	'2019_12_14_000001_create_personal_access_tokens_table',	1),
(89,	'2023_10_19_104437_create_students_table',	1),
(90,	'2023_10_19_160500_create_plans_table',	1),
(91,	'2023_11_01_170141_create_permission_tables',	1);

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1,	'App\\Models\\User',	1),
(2,	'App\\Models\\User',	2),
(2,	'App\\Models\\User',	3),
(2,	'App\\Models\\User',	4),
(2,	'App\\Models\\User',	5),
(2,	'App\\Models\\User',	6),
(3,	'App\\Models\\User',	7);

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1,	'student-list',	'web',	'2023-11-15 10:56:42',	'2023-11-15 10:56:42'),
(2,	'student-view',	'web',	'2023-11-15 10:56:42',	'2023-11-15 10:56:42'),
(3,	'student-create',	'web',	'2023-11-15 10:56:42',	'2023-11-15 10:56:42'),
(4,	'student-edit',	'web',	'2023-11-15 10:56:42',	'2023-11-15 10:56:42'),
(5,	'student-delete',	'web',	'2023-11-15 10:56:42',	'2023-11-15 10:56:42'),
(6,	'plan-list',	'web',	'2023-11-15 10:56:42',	'2023-11-15 10:56:42'),
(7,	'plan-view',	'web',	'2023-11-15 10:56:42',	'2023-11-15 10:56:42'),
(8,	'plan-create',	'web',	'2023-11-15 10:56:42',	'2023-11-15 10:56:42'),
(9,	'plan-edit',	'web',	'2023-11-15 10:56:42',	'2023-11-15 10:56:42'),
(10,	'plan-delete',	'web',	'2023-11-15 10:56:42',	'2023-11-15 10:56:42');

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `plans`;
CREATE TABLE `plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `plan` varchar(255) NOT NULL,
  `mode_of_payment` varchar(255) NOT NULL,
  `valid_from_date` date NOT NULL,
  `valid_upto_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plans_student_id_foreign` (`student_id`),
  CONSTRAINT `plans_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `plans` (`id`, `student_id`, `plan`, `mode_of_payment`, `valid_from_date`, `valid_upto_date`, `created_at`, `updated_at`) VALUES
(1,	1,	'plan1',	'Online',	'2023-11-10',	'2023-11-15',	'2023-11-15 17:00:03',	'2023-11-16 16:27:16'),
(2,	1,	'plan1',	'Online',	'2023-11-16',	'2023-11-26',	'2023-11-16 18:55:24',	'2023-11-16 18:55:24'),
(3,	3,	'plan1',	'Cash',	'2023-11-16',	'2023-11-16',	'2023-11-16 14:05:55',	'2023-11-17 16:42:54'),
(7,	4,	'plan1',	'Cash',	'2023-11-16',	'2023-11-30',	'2023-11-16 19:28:49',	'2023-11-16 19:28:49'),
(8,	2,	'plan1',	'Online',	'2023-11-18',	'2023-11-22',	'2023-11-18 14:21:19',	'2023-11-18 14:21:19'),
(9,	5,	'plan1',	'cash',	'2023-11-20',	'2023-11-21',	'2023-11-20 15:26:09',	'2023-11-20 15:26:09'),
(10,	6,	'plan1',	'CASH',	'2023-11-20',	'2023-12-19',	'2023-11-20 16:16:58',	'2023-11-20 16:16:58'),
(11,	7,	'plan1',	'ONLINE PAYTM',	'2023-11-20',	'2024-02-19',	'2023-11-20 16:31:23',	'2023-11-20 16:31:23'),
(12,	8,	'plan1',	'ONLINE PAYTM',	'2023-11-20',	'2024-02-19',	'2023-11-20 16:42:57',	'2023-11-20 16:42:57'),
(13,	9,	'plan1',	'ONLINE PAYTM',	'2023-11-20',	'2024-02-19',	'2023-11-20 17:02:22',	'2023-11-20 17:02:22'),
(14,	10,	'plan1',	'CASH',	'2023-11-20',	'2024-02-19',	'2023-11-20 17:10:08',	'2023-11-20 17:10:08'),
(15,	11,	'plan1',	'ONLINE PAYTM',	'2023-11-20',	'2023-12-19',	'2023-11-20 17:21:15',	'2023-11-20 17:21:15'),
(16,	12,	'plan1',	'ONLINE PAYTM',	'2023-11-20',	'2024-12-19',	'2023-11-20 17:28:47',	'2023-11-20 17:28:47'),
(17,	13,	'plan1',	'ONLINE PAYTM',	'2023-11-20',	'2023-12-19',	'2023-11-20 18:46:40',	'2023-11-20 18:46:40'),
(18,	14,	'plan1',	'ONLINE PAYTM',	'2023-11-20',	'2023-12-19',	'2023-11-22 11:37:05',	'2023-11-22 11:37:05'),
(19,	15,	'plan1',	'CASH',	'2023-11-20',	'2024-02-19',	'2023-11-22 11:50:27',	'2023-11-22 11:50:27'),
(20,	16,	'plan1',	'ONLINE PAYTM',	'2023-11-20',	'2024-02-19',	'2023-11-22 18:30:24',	'2023-11-22 18:30:24'),
(21,	17,	'plan1',	'ONLINE PAYTM',	'2023-11-20',	'2023-12-19',	'2023-11-22 18:37:34',	'2023-11-22 18:37:34');

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1,	'admin',	'web',	'2023-11-15 10:56:42',	'2023-11-15 10:56:42'),
(2,	'manager',	'web',	'2023-11-15 10:56:42',	'2023-11-15 10:56:42'),
(3,	'superadmin',	'web',	'2023-11-23 21:48:58',	'2023-11-23 21:48:58');

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1,	1),
(1,	2),
(1,	3),
(2,	1),
(2,	2),
(2,	3),
(3,	1),
(3,	2),
(3,	3),
(4,	2),
(4,	3),
(5,	3),
(6,	1),
(6,	2),
(6,	3),
(7,	1),
(7,	2),
(7,	3),
(8,	1),
(8,	2),
(8,	3),
(9,	3),
(10,	3);

DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `personal_number` varchar(255) NOT NULL,
  `emergency_number` varchar(255) DEFAULT NULL,
  `dob` varchar(255) DEFAULT NULL,
  `course` varchar(255) DEFAULT NULL,
  `current_address` varchar(255) DEFAULT NULL,
  `permanent_address` varchar(255) DEFAULT NULL,
  `subscription` varchar(255) DEFAULT NULL,
  `payment` varchar(255) DEFAULT NULL,
  `pending_payment` varchar(255) DEFAULT NULL,
  `remark_singnature` varchar(255) DEFAULT NULL,
  `hall_number` varchar(255) DEFAULT NULL,
  `vehicle_number` varchar(255) DEFAULT NULL,
  `aadhar_number` varchar(255) NOT NULL,
  `aadhar_front_img` varchar(255) DEFAULT NULL,
  `aadhar_back_img` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','block') NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_email_unique` (`email`),
  UNIQUE KEY `students_personal_number_unique` (`personal_number`),
  UNIQUE KEY `students_aadhar_number_unique` (`aadhar_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `students` (`id`, `user_id`, `name`, `email`, `password`, `personal_number`, `emergency_number`, `dob`, `course`, `current_address`, `permanent_address`, `subscription`, `payment`, `pending_payment`, `remark_singnature`, `hall_number`, `vehicle_number`, `aadhar_number`, `aadhar_front_img`, `aadhar_back_img`, `image`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1,	3,	'Student1',	'student1@gmail.com',	'password',	'8909876756',	'78654567876',	'2023-11-15',	'PHP',	'INDORE',	'INDORE',	'Montly',	'500',	'200',	'testing',	'02',	'03',	'11111111111111',	'student_aadhar_img/6554a49f5e3f9.jpeg',	'student_aadhar_img/6554a49f5ea97.png',	'student_img/65572c2ce9dab.jpg',	'active',	NULL,	'2023-11-15 16:29:43',	'2023-11-17 14:32:36'),
(2,	2,	'Student2',	'student2@gmail.com',	'12345678',	'8989898989',	'6543234323',	'2023-11-15',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'222222222222',	NULL,	NULL,	NULL,	'active',	NULL,	'2023-11-15 17:58:25',	'2023-11-18 11:20:10'),
(3,	1,	'Suman Naskar',	's1@mailinator.com',	'123456',	'9876543210',	'9876543211',	'2007-02-16',	'UPSC',	'114 Nagappa Building',	'3rd floor, Benniganahalli Bangalore North',	NULL,	NULL,	NULL,	NULL,	'1',	'MP091222',	'1234567890',	'student_aadhar_img/65572bcb0088b.png',	'student_aadhar_img/65572bcb00e2d.jpg',	'student_img/65572bcb01226.jpg',	'active',	NULL,	'2023-11-16 13:59:14',	'2023-11-17 14:30:59'),
(4,	1,	'Kinchit',	'Nobody@gmail.com',	'123456',	'1234567890',	'1234567890',	'2023-11-16',	'Upsc',	'Weg',	'Adgh',	NULL,	NULL,	NULL,	NULL,	'1',	'Mp09cu1234',	'Qwetjjvc',	'student_aadhar_img/65561f50406dc.jpg',	'student_aadhar_img/65561f5043d5e.jpg',	'student_img/65561f504409b.jpg',	'active',	NULL,	'2023-11-16 19:25:28',	'2023-11-16 19:25:28'),
(5,	1,	'MAHAK VERMA',	'MAHAK123@GMAIL.COM',	'MAHA1211',	'8085153499',	'1234567891',	'2003-12-11',	'UPSC',	'VIJAY NAGAR',	'INDORE',	NULL,	NULL,	NULL,	'ABC',	'HALL 1',	'MP091234',	'12345678912',	'student_aadhar_img/655b284e82447.png',	'student_aadhar_img/655b284e82a22.jpg',	'student_img/655b284e82dee.jpg',	'active',	NULL,	'2023-11-20 15:05:10',	'2023-11-20 15:05:10'),
(6,	1,	'SUJAL SINGH BAGHEL',	'SUJALSB123@GMAIL.COM',	'SUJA1120',	'7617345165',	'SISTER 6265060502',	'2023-11-20',	'CA FOUNDATION',	'SATYA SAI',	'REWA',	NULL,	NULL,	NULL,	'ABC',	'HALL 3',	'-',	'4897485746587',	'student_aadhar_img/655b388913ded.png',	'student_aadhar_img/655b3889143aa.jpg',	'student_img/655b3889149b8.jpg',	'active',	NULL,	'2023-11-20 16:14:25',	'2023-11-20 16:14:25'),
(7,	1,	'SUHANI TIWARI',	'SUHANIT@GMAIL.COM',	'SUHA1120',	'8471030112',	'HUSBAND 7999669275',	'2023-11-20',	'SUPER TET',	'RAM KRISHNA BAGH COLONY',	'JHANSI',	NULL,	NULL,	NULL,	'ABC',	'HALL 1',	'-',	'58974657652198',	'student_aadhar_img/655b3bfab8e1b.png',	NULL,	'student_img/655b3bfab963f.jpg',	'active',	NULL,	'2023-11-20 16:29:06',	'2023-11-20 16:29:06'),
(8,	1,	'BHARTI SONI',	'BHARTIS@GMAIL.COM',	'BHAR0105',	'6266525540',	'SISTER 9399050088',	'2001-01-05',	'SSC',	'VIJAY NAGAR',	'BETUL',	NULL,	NULL,	NULL,	'ABC',	'HALL 2',	'-',	'189746587884',	'student_aadhar_img/655b3eec52598.png',	'student_aadhar_img/655b3eec52b91.jpg',	'student_img/655b3eec52eb6.jpg',	'active',	NULL,	'2023-11-20 16:41:40',	'2023-11-20 16:41:40'),
(9,	1,	'ANAS NIRBAN',	'ANASN@GMAIL.COM',	'ANAS0205',	'6263715078',	'SELF NO.  8719871636',	'2002-02-05',	'CAT',	'ROBOT SQUARE',	'INDORE',	NULL,	NULL,	NULL,	'ABC',	'HALL 2',	'-',	'948154681654',	'student_aadhar_img/655b43322652e.png',	'student_aadhar_img/655b433226c62.jpg',	'student_img/655b433227596.jpg',	'active',	NULL,	'2023-11-20 16:59:54',	'2023-11-20 16:59:54'),
(10,	1,	'DIMPAL RAIPURIYA',	'DIMPALR@GMAIL.COM',	'DIMP1104',	'9285422426',	'HUSBAND NO.  9806422426',	'2002-11-04',	'MPPSC',	'NEW CHETAN NAGAR',	'INDORE',	NULL,	NULL,	NULL,	'ABC',	'HALL 2',	'-',	'65871597415',	'student_aadhar_img/655b455a6927c.png',	'student_aadhar_img/655b455a6977d.jpg',	'student_img/655b455a69a67.jpg',	'active',	NULL,	'2023-11-20 17:09:06',	'2023-11-20 17:09:06'),
(11,	1,	'HARSHITA DUBEY',	'HARSHITAD@GMAIL.COM',	'HARS0804',	'9109654296',	'SELF NO. 9926506963',	'1999-08-04',	'BANKING',	'GANGA DEVI NAGAR',	'SAGAR',	NULL,	NULL,	NULL,	'ABC',	'HALL 2',	'-',	'154815595626',	'student_aadhar_img/655b478de6caa.png',	'student_aadhar_img/655b478de74df.jpg',	'student_img/655b478de7828.jpg',	'active',	NULL,	'2023-11-20 17:18:29',	'2023-11-20 17:18:29'),
(12,	1,	'VARUN SINGH',	'VARUNS@GMAIL.COM',	'VARU0609',	'9754741999',	'MOTHER NO.  8085209685',	'1996-06-09',	'MPPSC',	'SUNCITY',	'INDORE',	NULL,	NULL,	NULL,	'ABC',	'HALL 1',	'MP09CR3969',	'992650696359',	'student_aadhar_img/655b49babcc53.png',	'student_aadhar_img/655b49babd393.jpg',	'student_img/655b49babd8a1.jpg',	'active',	NULL,	'2023-11-20 17:27:46',	'2023-11-20 17:27:46'),
(13,	1,	'SNEHA SAHU',	'SNEHAS@GMAIL.COM',	'SNEH0908',	'8718819447',	'SISTER NO. 8269616073',	'2001-09-08',	'NEET',	'BHAGYASHREE COLONY',	'VIDISHA',	NULL,	NULL,	NULL,	'ABC',	'HALL 2',	'-',	'808520968566',	'student_aadhar_img/655b5bed1b98e.png',	'student_aadhar_img/655b5bed1bf6f.jpg',	'student_img/655b5bed1c2b3.jpg',	'active',	NULL,	'2023-11-20 18:45:25',	'2023-11-20 18:45:25'),
(14,	1,	'PRADHYUMNA NAGAR',	'PRADHYUMNAN@GMAIL.COM',	'PRAD0504',	'8120846054',	'FRIEND 9516856270',	'1999-05-04',	'CS',	'SHEETAL NAGAR',	'DEWAS',	NULL,	NULL,	NULL,	'ABC',	'HALL 1',	'-',	'896219992654',	'student_aadhar_img/655d9a1fc69fc.png',	'student_aadhar_img/655d9a1fc6eb3.jpg',	'student_img/655d9a1fc7145.jpg',	'active',	NULL,	'2023-11-22 11:35:19',	'2023-11-22 11:35:19'),
(15,	1,	'NAMAH PARMAR',	'NAMAHP@GMAIL.COM',	'NAMA0508',	'8962199926',	'BROTHER 9111222843',	'2000-05-08',	'BANKING',	'BHAGYASHREE COLONY',	'ASTA',	NULL,	NULL,	NULL,	'ABC',	'HALL 3',	'-',	'812084605456',	'student_aadhar_img/655d9d3fbba0b.png',	'student_aadhar_img/655d9d3fbbf5c.jpg',	'student_img/655d9d3fbc20b.jpg',	'active',	NULL,	'2023-11-22 11:48:39',	'2023-11-22 11:48:39'),
(16,	1,	'REKHA JANGIR',	'REKHAJ@GMAIL.COM',	'REKH0202',	'8602625909',	'HUSBAND 8871464341',	'2000-02-02',	'TEACHING',	'GANGA DEVI NAGAR',	'INDORE',	NULL,	NULL,	NULL,	'DONE',	'HALL 2',	'-',	'639817971154',	'student_aadhar_img/655dfb2adde73.png',	'student_aadhar_img/655dfb2ade4f3.jpg',	'student_img/655dfb2ade8cd.jpg',	'active',	NULL,	'2023-11-22 18:29:22',	'2023-11-22 18:29:22'),
(17,	1,	'ABHISHEK UPADHYAY',	'ABHISHEKU@GMAIL.COM',	'ABHI0303',	'6398179711',	'BROTHER 7983117899',	'2000-03-03',	'CA FINAL',	'BCM HEIGHTS',	'ITAWA',	NULL,	NULL,	NULL,	'DONE',	'HALL 2',	'-',	'860262590968',	'student_aadhar_img/655dfca2e2c0f.png',	'student_aadhar_img/655dfca2e3134.jpg',	'student_img/655dfca2e342d.jpg',	'active',	NULL,	'2023-11-22 18:35:38',	'2023-11-22 18:35:38');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `normal_password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `normal_password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1,	'Naman Jain',	'naman17@outlook.com',	'2023-11-15 10:56:42',	'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',	NULL,	'klNYJFaw4o34DBgKd3VUSDUG74Gh4IpbWitLmo5HPyxdaDH8cqH4tnhwNoV7',	'2023-11-15 10:56:42',	'2023-11-15 10:56:42'),
(2,	'Manager',	'manager@gmail.com',	'2023-11-15 10:56:42',	'$2y$10$XEGEoQThrShvojajGUafl..irAUGdd0cCC51j0OwqeVXnZnyAtGZC',	'password',	'vuFhaeFUFF',	'2023-11-15 10:56:42',	'2023-11-17 16:17:25'),
(3,	'Manager1',	'manager1@gmail.com',	NULL,	'$2y$10$8WbZk6vfITddt4cUPDIwne8xIef0NW0AEVWo2ANvZU8sDMt7PRuz.',	'password',	'6O71U0B1TUXoUnaQviwqAWW0IhWdHcYT4nIYESWuGx1RemXHhzhoZvREpeB0',	'2023-11-15 16:30:30',	'2023-11-15 16:30:30'),
(4,	'Mahak Verma',	'Mahakverma@K3library.com',	NULL,	'$2y$10$plzg6zDEdvNy3LE0Oz88du69dxr5VoY45OhcC2.Guv/m2.y99yI0y',	'password',	'S3MZso81ynYo8WnDgaOLrHT8b6y8t7Jl9Eh8niuYYp8VWot0bPRhWtKXk662',	'2023-11-23 19:02:12',	'2023-11-23 21:15:56'),
(5,	'Mnager2',	'manager2@gmail.com',	NULL,	'$2y$10$ERC/1AqAg7f/ea9mLIJ98ucnqYNVeWl/XJiIJ04E2.j96uQ.kOKuq',	'password',	NULL,	'2023-11-23 20:57:05',	'2023-11-23 20:57:05'),
(6,	'Manager5',	'manager5@gmail.com',	NULL,	'$2y$10$rFJp9D.wIlPA6Bo4byvvW.hlZif/wjszeaS0Tbycp4q6ZROMw2ft2',	'password',	NULL,	'2023-11-23 21:15:20',	'2023-11-23 21:15:20'),
(7,	'Super Admin',	'superadmin@gmail.com',	NULL,	'$2y$10$R/GHju9qSM7jEyUFkaxN5.0Z.SweUqFvdnWigDeIjOutZVKKsZtJu',	'password',	'oUgsEeN2tlCH38S3QkXTLVGQZOSLWpMW5HZngYKwfXtdMu66OkBcOzDsJ7RB',	'2023-11-23 21:53:24',	'2023-11-23 21:53:24');

-- 2023-11-24 05:39:55
