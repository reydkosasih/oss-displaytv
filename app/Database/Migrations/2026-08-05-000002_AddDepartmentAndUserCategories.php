<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDepartmentAndUserCategories extends Migration
{
    public function up()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        // 1. Tambah kolom department pada tabel users (aman berulang via IF NOT EXISTS)
        $this->db->query("
            ALTER TABLE users
            ADD COLUMN IF NOT EXISTS department VARCHAR(100) NULL AFTER `role`;
        ");

        // 2. Buat tabel user_categories
        $this->db->query("
            CREATE TABLE IF NOT EXISTS user_categories (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id INT UNSIGNED NOT NULL,
                category_id INT UNSIGNED NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uniq_user_category (user_id, category_id),
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function down()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        // Hapus tabel user_categories jika ada
        $this->db->query('DROP TABLE IF EXISTS user_categories');

        // Hapus kolom department dari users jika ada (MySQL 8+)
        $this->db->query("
            ALTER TABLE users
            DROP COLUMN IF EXISTS department;
        ");

        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
    }
}
