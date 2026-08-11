<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ConvertImagesToWebp extends BaseCommand
{
    /**
     * Group command CLI
     */
    protected $group = 'Media Optimization';

    /**
     * Nama command Spark
     */
    protected $name = 'images:convert-webp';

    /**
     * Deskripsi singkat command
     */
    protected $description = 'Mengonversi seluruh gambar konten lama (JPG/PNG) ke format WebP yang efisien.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('contents');

        $images = $builder->where('type', 'image')
                          ->where('deleted_at', null)
                          ->get()
                          ->getResultArray();

        if (empty($images)) {
            CLI::write('Tidak ada konten gambar yang perlu dikonversi.', 'yellow');
            return;
        }

        $uploadPath = ROOTPATH . 'public/uploads/contents/images/';
        $convertedCount = 0;
        $skippedCount   = 0;
        $failedCount    = 0;

        CLI::write('Memulai konversi gambar ke format WebP...', 'green');

        foreach ($images as $img) {
            $currentPath = $img['file_path'];
            if (!$currentPath) {
                continue;
            }

            $ext = strtolower(pathinfo($currentPath, PATHINFO_EXTENSION));
            if ($ext === 'webp') {
                $skippedCount++;
                continue;
            }

            $fullOriginalPath = $uploadPath . $currentPath;
            if (!file_exists($fullOriginalPath)) {
                CLI::write("File tidak ditemukan: {$currentPath}", 'red');
                $failedCount++;
                continue;
            }

            $newFileName = pathinfo($currentPath, PATHINFO_FILENAME) . '.webp';
            $fullWebpPath = $uploadPath . $newFileName;

            $success = $this->convertFileToWebp($fullOriginalPath, $fullWebpPath);

            if ($success && file_exists($fullWebpPath)) {
                $newFileSize = filesize($fullWebpPath);

                // Update database
                $db->table('contents')
                   ->where('id', $img['id'])
                   ->update([
                       'file_path' => $newFileName,
                       'file_size' => $newFileSize,
                   ]);

                // Hapus file lama non-webp
                if ($fullOriginalPath !== $fullWebpPath && file_exists($fullOriginalPath)) {
                    @unlink($fullOriginalPath);
                }

                $convertedCount++;
                CLI::write("Berhasil mengonversi [ID {$img['id']}]: {$currentPath} -> {$newFileName}", 'green');
            } else {
                $failedCount++;
                CLI::write("Gagal mengonversi [ID {$img['id']}]: {$currentPath}", 'red');
            }
        }

        CLI::newLine();
        CLI::write("Selesai! Berhasil: {$convertedCount}, Lewati (Sudah WebP): {$skippedCount}, Gagal: {$failedCount}", 'cyan');
    }

    private function convertFileToWebp(string $sourcePath, string $targetPath): bool
    {
        if (function_exists('imagewebp')) {
            $mime = mime_content_type($sourcePath);
            $srcImg = null;

            if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
                $srcImg = @imagecreatefromjpeg($sourcePath);
            } elseif ($mime === 'image/png') {
                $srcImg = @imagecreatefrompng($sourcePath);
                if ($srcImg) {
                    imagepalettetotruecolor($srcImg);
                    imagealphablending($srcImg, true);
                    imagesavealpha($srcImg, true);
                }
            }

            if ($srcImg) {
                $width  = imagesx($srcImg);
                $height = imagesy($srcImg);

                if ($width > 1920 || $height > 1080) {
                    $ratio     = min(1920 / $width, 1080 / $height);
                    $newWidth  = (int) round($width * $ratio);
                    $newHeight = (int) round($height * $ratio);
                    $resizedImg = imagecreatetruecolor($newWidth, $newHeight);
                    imagealphablending($resizedImg, false);
                    imagesavealpha($resizedImg, true);
                    imagecopyresampled($resizedImg, $srcImg, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                    imagedestroy($srcImg);
                    $srcImg = $resizedImg;
                }

                $res = imagewebp($srcImg, $targetPath, 85);
                imagedestroy($srcImg);
                if ($res) {
                    return true;
                }
            }
        }

        try {
            $imageService = \Config\Services::image('gd');
            $imageService->withFile($sourcePath)
                         ->convert(IMAGETYPE_WEBP)
                         ->save($targetPath, 85);
            return file_exists($targetPath);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
