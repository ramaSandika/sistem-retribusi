-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: db_retribusi_red
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` text NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,1,'Administrator BAPENDA','LOGIN','Sistem login sebagai Administrator BAPENDA','127.0.0.1','2026-08-17 18:55:34','2026-08-17 18:55:34'),(2,2,'Operator Dishub','UPLOAD_PDF','Mengupload & mengekstraksi file Realisasi_Dishub_Agustus_2026.pdf (Nilai: Rp 154.500.000)','127.0.0.1','2026-08-17 18:55:34','2026-08-17 18:55:34'),(3,3,'Operator Disdag','VERIFY_DATA','Memvalidasi data realisasi retribusi pasar periode Agustus 2026','127.0.0.1','2026-08-17 18:55:34','2026-08-17 18:55:34');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_08_18_000002_create_upload_retribusis_table',1),(5,'2026_08_18_000003_create_realisasi_retribusis_table',1),(6,'2026_08_18_000004_create_audit_logs_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `realisasi_retribusis`
--

DROP TABLE IF EXISTS `realisasi_retribusis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `realisasi_retribusis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `upload_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `kode_rekening` varchar(255) NOT NULL,
  `nama_retribusi` varchar(255) NOT NULL,
  `opd_name` varchar(255) NOT NULL,
  `nilai` decimal(15,2) NOT NULL,
  `periode` varchar(255) NOT NULL,
  `tahun` int(11) NOT NULL,
  `tanggal_realisasi` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `realisasi_retribusis_upload_id_foreign` (`upload_id`),
  KEY `realisasi_retribusis_user_id_foreign` (`user_id`),
  CONSTRAINT `realisasi_retribusis_upload_id_foreign` FOREIGN KEY (`upload_id`) REFERENCES `upload_retribusis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `realisasi_retribusis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `realisasi_retribusis`
--

LOCK TABLES `realisasi_retribusis` WRITE;
/*!40000 ALTER TABLE `realisasi_retribusis` DISABLE KEYS */;
INSERT INTO `realisasi_retribusis` VALUES (1,1,2,'4.1.02.01.01','Retribusi Parkir Tepi Jalan Umum','Dinas Perhubungan',125000000.00,'Juli 2026',2026,'2026-08-15','2026-08-17 18:55:34','2026-08-17 18:55:34'),(2,1,2,'4.1.02.01.02','Retribusi Pengujian Kendaraan Bermotor (Kir)','Dinas Perhubungan',48200000.00,'Juli 2026',2026,'2026-08-15','2026-08-17 18:55:34','2026-08-17 18:55:34'),(3,1,2,'4.1.02.01.01','Retribusi Parkir Tepi Jalan Umum','Dinas Perhubungan',135000000.00,'Agustus 2026',2026,'2026-08-15','2026-08-17 18:55:34','2026-08-17 18:55:34'),(4,1,2,'4.1.02.01.03','Retribusi Terminal & Markas Angkutan','Dinas Perhubungan',19500000.00,'Agustus 2026',2026,'2026-08-15','2026-08-17 18:55:34','2026-08-17 18:55:34'),(5,2,3,'4.1.02.02.01','Retribusi Pelayanan Pasar Daerah','Dinas Perdagangan',80500000.00,'Juli 2026',2026,'2026-08-15','2026-08-17 18:55:34','2026-08-17 18:55:34'),(6,2,3,'4.1.02.02.03','Retribusi Sewa Toko & Ruko Pasar','Dinas Perdagangan',62000000.00,'Juli 2026',2026,'2026-08-15','2026-08-17 18:55:34','2026-08-17 18:55:34'),(7,2,3,'4.1.02.02.01','Retribusi Pelayanan Pasar Daerah','Dinas Perdagangan',91000000.00,'Agustus 2026',2026,'2026-08-15','2026-08-17 18:55:34','2026-08-17 18:55:34'),(8,NULL,4,'4.1.02.03.01','Retribusi Persetujuan Bangunan Gedung (PBG)','Dinas Perkim',175000000.00,'Juli 2026',2026,'2026-08-15','2026-08-17 18:55:34','2026-08-17 18:55:34'),(9,NULL,4,'4.1.02.03.01','Retribusi Persetujuan Bangunan Gedung (PBG)','Dinas Perkim',192000000.00,'Agustus 2026',2026,'2026-08-15','2026-08-17 18:55:34','2026-08-17 18:55:34'),(10,NULL,5,'4.1.02.05.01','Retribusi Pelayanan Puskesmas & Labkesda','Dinas Kesehatan',71500000.00,'Juli 2026',2026,'2026-08-15','2026-08-17 18:55:34','2026-08-17 18:55:34'),(11,NULL,5,'4.1.02.05.01','Retribusi Pelayanan Puskesmas & Labkesda','Dinas Kesehatan',78400000.00,'Agustus 2026',2026,'2026-08-15','2026-08-17 18:55:34','2026-08-17 18:55:34');
/*!40000 ALTER TABLE `realisasi_retribusis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `upload_retribusis`
--

DROP TABLE IF EXISTS `upload_retribusis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `upload_retribusis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_filename` varchar(255) NOT NULL,
  `tahun` int(11) NOT NULL,
  `periode` varchar(255) NOT NULL,
  `opd_name` varchar(255) NOT NULL,
  `total_nilai` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_item` int(11) NOT NULL DEFAULT 0,
  `status` enum('Processing','Success','Failed') NOT NULL DEFAULT 'Processing',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `upload_retribusis_user_id_foreign` (`user_id`),
  CONSTRAINT `upload_retribusis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `upload_retribusis`
--

LOCK TABLES `upload_retribusis` WRITE;
/*!40000 ALTER TABLE `upload_retribusis` DISABLE KEYS */;
INSERT INTO `upload_retribusis` VALUES (1,2,'REKAP_RETRIBUSI_DISHUB_AUG2026.pdf','Realisasi_Dishub_Agustus_2026.pdf',2026,'Agustus 2026','Dinas Perhubungan',154500000.00,2,'Success','Hasil ekstraksi OCR otomatis - Terverifikasi oleh Operator','2026-08-17 18:55:34','2026-08-17 18:55:34'),(2,3,'REKAP_RETRIBUSI_DISDAG_AUG2026.pdf','Dokumen_Pasar_Agustus_2026.pdf',2026,'Agustus 2026','Dinas Perdagangan',91000000.00,1,'Success','Hasil ekstraksi OCR otomatis','2026-08-17 18:55:34','2026-08-17 18:55:34');
/*!40000 ALTER TABLE `upload_retribusis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user_opd') NOT NULL DEFAULT 'user_opd',
  `opd_name` varchar(255) NOT NULL DEFAULT 'Dinas Perhubungan',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator BAPENDA','admin@retribusi.go.id',NULL,'$2y$12$IyqY6ltcUDQdSQG.g/usruAbvc0B71K5XAcswi/2G4VMwOjj.nDNW','admin','Badan Pendapatan Daerah',NULL,'2026-08-17 18:55:32','2026-08-17 18:55:32'),(2,'Operator Dishub','dishub@retribusi.go.id',NULL,'$2y$12$3hBvKCoYAmzMTB.hRXc2ZOjlOA4/JBhkiLHNM.iwHjI3VlPmGubjm','user_opd','Dinas Perhubungan',NULL,'2026-08-17 18:55:32','2026-08-17 18:55:32'),(3,'Operator Disdag','disdag@retribusi.go.id',NULL,'$2y$12$7uvb5MfO6V/GhT2xPLkXCeRH02w/SrhaGmtXd.hV1Ohyu6eLBKbkC','user_opd','Dinas Perdagangan',NULL,'2026-08-17 18:55:33','2026-08-17 18:55:33'),(4,'Operator Perkim','perkim@retribusi.go.id',NULL,'$2y$12$wcs10hqr//TC7bh/mXVNKOXOgN.pW6MhcyFOOEUKaucIYSzxdEAQ2','user_opd','Dinas Perkim',NULL,'2026-08-17 18:55:33','2026-08-17 18:55:33'),(5,'Operator Dinkes','dinkes@retribusi.go.id',NULL,'$2y$12$5FZ.VBfCwBpyro9I1kh/HONoaPxi9EexBjfI5ZZc0WbpxxFfy7zmm','user_opd','Dinas Kesehatan',NULL,'2026-08-17 18:55:34','2026-08-17 18:55:34');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-18 10:55:41
