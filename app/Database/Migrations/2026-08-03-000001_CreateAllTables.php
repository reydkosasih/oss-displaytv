<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAllTables extends Migration
{
    public function up()
    {
        // Disable foreign key checks while creating tables
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        // 1. USERS
        $this->db->query("
            CREATE TABLE IF NOT EXISTS users (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(150) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role ENUM('superadmin', 'admin') NOT NULL DEFAULT 'admin',
                is_active TINYINT(1) NOT NULL DEFAULT 1,
                last_login_at DATETIME NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
                deleted_at DATETIME NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // 2. TVS
        $this->db->query("
            CREATE TABLE IF NOT EXISTS tvs (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                slug VARCHAR(120) NOT NULL UNIQUE,
                location VARCHAR(150) NULL,
                thumbnail VARCHAR(255) NULL COMMENT 'gambar background kartu di landing page',
                pin VARCHAR(6) NOT NULL COMMENT 'PIN 6 digit, regenerable',
                pin_updated_at DATETIME NULL,
                orientation ENUM('landscape') NOT NULL DEFAULT 'landscape',
                is_active TINYINT(1) NOT NULL DEFAULT 1,
                last_seen_at DATETIME NULL COMMENT 'update saat TV membuka SSE connection',
                created_by INT UNSIGNED NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
                deleted_at DATETIME NULL,
                FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // 3. CATEGORIES
        $this->db->query("
            CREATE TABLE IF NOT EXISTS categories (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                slug VARCHAR(120) NOT NULL UNIQUE,
                color VARCHAR(7) NULL COMMENT 'hex color untuk badge UI, misal #3B82F6',
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
                deleted_at DATETIME NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // 4. TV_CATEGORIES
        $this->db->query("
            CREATE TABLE IF NOT EXISTS tv_categories (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                tv_id INT UNSIGNED NOT NULL,
                category_id INT UNSIGNED NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uniq_tv_category (tv_id, category_id),
                FOREIGN KEY (tv_id) REFERENCES tvs(id) ON DELETE CASCADE,
                FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // 5. CONTENTS
        $this->db->query("
            CREATE TABLE IF NOT EXISTS contents (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                category_id INT UNSIGNED NOT NULL,
                type ENUM('image', 'video', 'chart') NOT NULL,
                title VARCHAR(150) NOT NULL,
                file_path VARCHAR(255) NULL,
                file_size INT UNSIGNED NULL COMMENT 'bytes',
                video_source ENUM('upload', 'youtube') NULL,
                youtube_url VARCHAR(255) NULL,
                video_duration_seconds INT UNSIGNED NULL COMMENT 'auto-detect saat upload, atau dari YouTube API',
                display_duration_seconds SMALLINT UNSIGNED NULL DEFAULT 10,
                is_active TINYINT(1) NOT NULL DEFAULT 1,
                created_by INT UNSIGNED NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
                deleted_at DATETIME NULL,
                FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
                FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // 6. CONTENT_CHARTS
        $this->db->query("
            CREATE TABLE IF NOT EXISTS content_charts (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                content_id INT UNSIGNED NOT NULL,
                chart_type ENUM('bar', 'line', 'pie') NOT NULL DEFAULT 'bar',
                chart_labels JSON NOT NULL COMMENT '[\"Jan\",\"Feb\",\"Mar\"]',
                chart_datasets JSON NOT NULL COMMENT '[{\"label\":\"Penjualan\",\"data\":[10,20,30],\"color\":\"#3B82F6\"}]',
                updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (content_id) REFERENCES contents(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // 7. TV_PLAYLIST_ITEMS
        $this->db->query("
            CREATE TABLE IF NOT EXISTS tv_playlist_items (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                tv_id INT UNSIGNED NOT NULL,
                content_id INT UNSIGNED NOT NULL,
                sort_order INT UNSIGNED NOT NULL DEFAULT 0,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uniq_tv_content (tv_id, content_id),
                FOREIGN KEY (tv_id) REFERENCES tvs(id) ON DELETE CASCADE,
                FOREIGN KEY (content_id) REFERENCES contents(id) ON DELETE CASCADE,
                INDEX idx_tv_sort (tv_id, sort_order)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // 8. TV_ACCESS_LOGS
        $this->db->query("
            CREATE TABLE IF NOT EXISTS tv_access_logs (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                tv_id INT UNSIGNED NOT NULL,
                ip_address VARCHAR(45) NULL,
                user_agent VARCHAR(255) NULL,
                accessed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (tv_id) REFERENCES tvs(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // 9. TV_UPDATE_EVENTS
        $this->db->query("
            CREATE TABLE IF NOT EXISTS tv_update_events (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                tv_id INT UNSIGNED NOT NULL,
                event_type ENUM('playlist_changed', 'content_changed', 'pin_regenerated') NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (tv_id) REFERENCES tvs(id) ON DELETE CASCADE,
                INDEX idx_tv_created (tv_id, created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function down()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->query('DROP TABLE IF EXISTS tv_update_events');
        $this->db->query('DROP TABLE IF EXISTS tv_access_logs');
        $this->db->query('DROP TABLE IF EXISTS tv_playlist_items');
        $this->db->query('DROP TABLE IF EXISTS content_charts');
        $this->db->query('DROP TABLE IF EXISTS contents');
        $this->db->query('DROP TABLE IF EXISTS tv_categories');
        $this->db->query('DROP TABLE IF EXISTS categories');
        $this->db->query('DROP TABLE IF EXISTS tvs');
        $this->db->query('DROP TABLE IF EXISTS users');
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
    }
}
