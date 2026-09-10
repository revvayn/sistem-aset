-- ============================================================
-- SQL DATABASE SISTEM ASET (CodeIgniter 4)
-- Buat database dengan menjalankan file ini di phpMyAdmin / MySQL CLI
-- ============================================================

CREATE DATABASE IF NOT EXISTS `sistem_aset`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE `sistem_aset`;

-- ------------------------------------------------------------
-- 1. Tabel users
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id`         INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama`       VARCHAR(100)     NOT NULL,
  `email`      VARCHAR(100)     NOT NULL,
  `password`   VARCHAR(255)     NOT NULL,
  `role`       ENUM('admin','staff','viewer') NOT NULL DEFAULT 'staff',
  `created_at` DATETIME         NULL,
  `updated_at` DATETIME         NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- 2. Tabel master_data (Kategori)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `master_data` (
  `id`            INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_kategori` VARCHAR(100)     NOT NULL,
  `keterangan`    TEXT             NULL,
  `created_at`    DATETIME         NULL,
  `updated_at`    DATETIME         NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- 3. Tabel components (Master Atribut)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `components` (
  `id`            INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_komponen` VARCHAR(100)     NOT NULL,
  `key_komponen`  VARCHAR(100)     NOT NULL,
  `tipe_input`    ENUM('text','number','password','date','file','qr_code') NOT NULL DEFAULT 'text',
  `created_at`    DATETIME         NULL,
  `updated_at`    DATETIME         NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_komponen` (`key_komponen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- 4. Tabel master_data_components (Relasi Kategori & Komponen)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `master_data_components` (
  `id`             INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `master_data_id` INT(11) UNSIGNED NOT NULL,
  `component_id`   INT(11) UNSIGNED NOT NULL,
  `is_required`    TINYINT(1)       NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `master_data_id` (`master_data_id`),
  KEY `component_id` (`component_id`),
  CONSTRAINT `fk_mdc_master_data` FOREIGN KEY (`master_data_id`) REFERENCES `master_data` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_mdc_component`   FOREIGN KEY (`component_id`)   REFERENCES `components` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- 5. Tabel assets (Unit Aset Fisik)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `assets` (
  `id`              INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `no_asset`        VARCHAR(100)     NOT NULL,
  `nama_aset`       VARCHAR(150)     NOT NULL,
  `master_data_id`  INT(11) UNSIGNED NOT NULL,
  `user_id`         INT(11) UNSIGNED NULL,
  `status`          ENUM('Aktif','Perbaikan','Rusak','Disimpan') NOT NULL DEFAULT 'Aktif',
  `specifications`  JSON             NULL,
  `created_at`      DATETIME         NULL,
  `updated_at`      DATETIME         NULL,
  `deleted_at`      DATETIME         NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_asset` (`no_asset`),
  KEY `master_data_id` (`master_data_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_assets_master_data` FOREIGN KEY (`master_data_id`) REFERENCES `master_data` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_assets_user`         FOREIGN KEY (`user_id`)       REFERENCES `users` (`id`)      ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- Data Awal: User Admin
-- Login: admin@mail.com / admin123
-- ------------------------------------------------------------
INSERT INTO `users` (`nama`, `email`, `password`, `role`, `created_at`)
VALUES ('Admin System', 'admin@mail.com', '$2y$10$M3gwpBOG./wIncuggSwxhun348fl3SXrYNiHM4n.xOuydl77O1ALS', 'admin', NOW());