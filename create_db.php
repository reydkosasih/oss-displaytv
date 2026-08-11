<?php

/**
 * Script standalone untuk membuat database MySQL (oss_displaytv)
 * dan secara otomatis menjalankan spark migrate.
 * 
 * Penggunaan:
 * php create_db.php
 */

// Membaca file .env jika ada
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if (strpos($trimmed, '#') === 0) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name  = trim($name);
            $value = trim(trim($value), '"\'');
            $_ENV[$name] = $value;
            putenv("{$name}={$value}");
        }
    }
}

$host   = getenv('database.default.hostname') ?: 'localhost';
$user   = getenv('database.default.username') ?: 'root';
$pass   = getenv('database.default.password') ?: '';
$port   = getenv('database.default.port') ?: 3306;
$dbName = getenv('database.default.database') ?: 'oss_displaytv';

echo "========================================================\n";
echo "  Display TV - Script Auto Create Database & Migration  \n";
echo "========================================================\n\n";

echo "[1/3] Memeriksa koneksi MySQL server ({$host}:{$port})...\n";

try {
    $mysqli = new mysqli($host, $user, $pass, '', (int)$port);
    if ($mysqli->connect_error) {
        echo "❌ Gagal terhubung ke MySQL: " . $mysqli->connect_error . "\n";
        exit(1);
    }

    echo "[2/3] Membuat database '{$dbName}' (jika belum ada)...\n";
    $sql = "CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;";
    
    if ($mysqli->query($sql) === TRUE) {
        echo "✅ Database '{$dbName}' siap!\n\n";
    } else {
        echo "❌ Gagal membuat database: " . $mysqli->error . "\n";
        $mysqli->close();
        exit(1);
    }
    $mysqli->close();
} catch (Throwable $e) {
    echo "❌ Error MySQL: " . $e->getMessage() . "\n";
    exit(1);
}

echo "[3/3] Menjalankan migrasi database via spark migrate...\n\n";
passthru('php spark migrate');

echo "\nProses selesai!\n";
