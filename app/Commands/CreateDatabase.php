<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;

class CreateDatabase extends BaseCommand
{
    /**
     * Group command CLI
     */
    protected $group = 'Database';

    /**
     * Nama command Spark
     */
    protected $name = 'db:create';

    /**
     * Deskripsi singkat command
     */
    protected $description = 'Membuat database MySQL jika belum ada, lalu menjalankan migrasi database (spark migrate).';

    /**
     * Argumen & Penggunaan
     */
    protected $usage = 'db:create [db_name]';

    protected $arguments = [
        'db_name' => 'Nama database yang akan dibuat (default: oss_displaytv / sesuai .env)',
    ];

    public function run(array $params)
    {
        // 1. Ambil konfigurasi database dari .env atau Config\Database
        $config   = config('Database')->default;
        $hostname = env('database.default.hostname', $config['hostname'] ?? 'localhost');
        $username = env('database.default.username', $config['username'] ?? 'root');
        $password = env('database.default.password', $config['password'] ?? '');
        $port     = (int) env('database.default.port', $config['port'] ?? 3306);
        $dbName   = $params[0] ?? env('database.default.database', $config['database'] ?? 'oss_displaytv');

        if (empty($dbName)) {
            $dbName = 'oss_displaytv';
        }

        CLI::write("Memeriksa & membuat database '{$dbName}' di {$hostname}:{$port}...", 'yellow');

        // 2. Hubungkan ke MySQL server tanpa memilih database terlebih dahulu
        try {
            $mysqli = new \mysqli($hostname, $username, $password, '', $port);

            if ($mysqli->connect_error) {
                CLI::write("Gagal terhubung ke MySQL server: " . $mysqli->connect_error, 'red');
                return;
            }

            // 3. Buat Database IF NOT EXISTS
            $charset = $config['charset'] ?? 'utf8mb4';
            $collat  = $config['DBCollat'] ?? 'utf8mb4_general_ci';
            $sql     = "CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET {$charset} COLLATE {$collat};";

            if ($mysqli->query($sql) === TRUE) {
                CLI::write("Database '{$dbName}' berhasil dipastikan/dibuat!", 'green');
            } else {
                CLI::write("Gagal membuat database: " . $mysqli->error, 'red');
                $mysqli->close();
                return;
            }

            $mysqli->close();
        } catch (\Throwable $e) {
            CLI::write("Error koneksi MySQL: " . $e->getMessage(), 'red');
            return;
        }

        // 4. Jalankan Migrasi Database (php spark migrate)
        CLI::newLine();
        CLI::write("Menjalankan migrasi database...", 'yellow');

        try {
            $runner = Services::migrations();
            $runner->setGroup('default');

            if ($runner->latest()) {
                CLI::write("Migrasi database berhasil dijalankan!", 'green');
            } else {
                CLI::write("Seluruh migrasi sudah up-to-date.", 'cyan');
            }
        } catch (\Throwable $e) {
            CLI::write("Gagal menjalankan migrasi: " . $e->getMessage(), 'red');
        }
    }
}
