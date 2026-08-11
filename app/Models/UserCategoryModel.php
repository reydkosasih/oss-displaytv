<?php

namespace App\Models;

use CodeIgniter\Model;

class UserCategoryModel extends Model
{
    protected $table            = 'user_categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'category_id',
    ];

    // Dates
    protected $useTimestamps = false;

    /**
     * Ambil ID kategori yang diizinkan untuk user
     */
    public function getCategoryIdsByUser(int $userId): array
    {
        $results = $this->where('user_id', $userId)->findAll();
        return array_map('intval', array_column($results, 'category_id'));
    }

    /**
     * Ambil detail data kategori (name, color) yang diizinkan untuk user
     */
    public function getCategoriesByUser(int $userId): array
    {
        $db = \Config\Database::connect();
        return $db->table('user_categories uc')
                  ->select('c.id, c.name, c.color')
                  ->join('categories c', 'c.id = uc.category_id')
                  ->where('uc.user_id', $userId)
                  ->where('c.deleted_at', null)
                  ->get()
                  ->getResultArray();
    }

    /**
     * Sinkronisasi kategori akses user (Replace All)
     */
    public function syncCategories(int $userId, array $categoryIds): void
    {
        // Hapus kategori lama
        $this->where('user_id', $userId)->delete();

        // Filter & simpan kategori baru
        $categoryIds = array_filter(array_map('intval', $categoryIds));

        if (!empty($categoryIds)) {
            $insertData = [];
            foreach ($categoryIds as $catId) {
                $insertData[] = [
                    'user_id'     => $userId,
                    'category_id' => $catId,
                ];
            }
            $this->insertBatch($insertData);
        }
    }
}
