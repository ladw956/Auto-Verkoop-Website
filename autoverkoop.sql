DROP DATABASE IF EXISTS `autoverkoop`;

CREATE DATABASE `autoverkoop`
DEFAULT CHARACTER SET utf8mb4
DEFAULT COLLATE utf8mb4_unicode_ci;

USE `autoverkoop`;

CREATE TABLE `gebruiker` (
    `id` SMALLINT AUTO_INCREMENT PRIMARY KEY,
    `naam` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `wachtwoord_hash` VARCHAR(255) NOT NULL,
    `is_admin` TINYINT(1) NOT NULL DEFAULT 0,
    `is_mainAdmin` TINYINT(1) NOT NULL DEFAULT 0,
    `saldo` INT NOT NULL DEFAULT 10000,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `rol` ENUM('Admin', 'User') NOT NULL DEFAULT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `auto` (
    `id` SMALLINT AUTO_INCREMENT PRIMARY KEY,
    `brand` VARCHAR(100) NOT NULL,
    `model` VARCHAR(100) NOT NULL,
    `year` INT NOT NULL,
    `prijs` INT NOT NULL,
    `omschrijving` TEXT,
    `image` VARCHAR(200),
    `gebruiker_id` SMALLINT NOT NULL,
    `naam` VARCHAR(60) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    INDEX gebr_ind (`gebruiker_id`),
    FOREIGN KEY (`gebruiker_id`) REFERENCES gebruiker(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO gebruiker (`naam`, `email`, `wachtwoord_hash`, `is_admin`, `is_mainAdmin`, `saldo`, `rol`)
VALUES ('admin', 'admin@demo.local', '$2y$12$NlaY1Zw0m5WArhzH9to7SOGijUtFl8Tg8FemPzGGctOest7Gss.bu', 1, 1, 50000, 'Admin');

INSERT INTO gebruiker (`naam`, `email`, `wachtwoord_hash`, `is_admin`, `is_mainAdmin`, `saldo`, `rol`)
VALUES ('demo user', 'user@demo.local', '$2y$12$bqCn8CxxLtXT/l9IjWc.C.5wSbN2BTSIvZsNSs25mTirKCVntPcTu', 0, 0, 20000, 'User');
