<?php

namespace App\Models;

use CodeIgniter\Model;

class TvCategoryModel extends Model
{
    protected $table            = 'tv_categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tv_id',
        'category_id',
        'created_at',
    ];

    /**
     * Sync categories for a TV
     */
    public function syncCategories(int $tvId, array $categoryIds)
    {
        // Remove existing
        $this->where('tv_id', $tvId)->delete();

        // Insert new ones
        if (!empty($categoryIds)) {
            $data = [];
            foreach ($categoryIds as $catId) {
                $data[] = [
                    'tv_id'       => $tvId,
                    'category_id' => (int) $catId,
                    'created_at'  => date('Y-m-d H:i:s'),
                ];
            }
            $this->insertBatch($data);
        }
    }

    /**
     * Get assigned category IDs for a TV
     */
    public function getCategoryIdsByTv(int $tvId): array
    {
        $rows = $this->select('category_id')->where('tv_id', $tvId)->findAll();
        return array_column($rows, 'category_id');
    }
}
