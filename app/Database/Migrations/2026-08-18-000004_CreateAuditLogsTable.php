<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuditLogsTable extends Migration
{
    public function up()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        $this->db->query("
            CREATE TABLE IF NOT EXISTS audit_logs (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id INT UNSIGNED NULL,
                user_name VARCHAR(100) NOT NULL DEFAULT '',
                user_role VARCHAR(50) NOT NULL DEFAULT '',
                `action` ENUM('create','update','delete','toggle','reorder','login','logout','regenerate') NOT NULL,
                `module` ENUM('content','category','tv','playlist','user','auth') NOT NULL,
                entity_id VARCHAR(100) NULL,
                entity_name VARCHAR(255) NULL,
                description TEXT NOT NULL,
                old_values JSON NULL,
                new_values JSON NULL,
                ip_address VARCHAR(45) NULL,
                user_agent VARCHAR(500) NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
                INDEX idx_user_id (user_id),
                INDEX idx_module (`module`),
                INDEX idx_action (`action`),
                INDEX idx_created_at (created_at),
                INDEX idx_module_action (`module`, `action`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function down()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->query('DROP TABLE IF EXISTS audit_logs');
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
    }
}