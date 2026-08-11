<?php

namespace App\Models;

use CodeIgniter\Model;

class TvPlaylistItemModel extends Model
{
    protected $table            = 'tv_playlist_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tv_id',
        'content_id',
        'sort_order',
        'created_at',
    ];

    // Dates
    protected $useTimestamps = false;

    /**
     * Get sorted playlist items for a TV
     */
    public function getPlaylistForTv(int $tvId): array
    {
        $db = \Config\Database::connect();

        // 1. Get assigned categories for this TV
        $assignedCategories = $db->table('tv_categories')
                                 ->select('category_id')
                                 ->where('tv_id', $tvId)
                                 ->get()
                                 ->getResultArray();

        $catIds = array_column($assignedCategories, 'category_id');

        if (empty($catIds)) {
            return [];
        }

        // 2. Fetch active contents under these categories with left join to tv_playlist_items for custom sort_order
        $contents = $db->table('contents c')
                       ->select('c.*, cat.name as category_name, cat.color as category_color, COALESCE(pi.sort_order, 999999) as sort_order')
                       ->join('categories cat', 'cat.id = c.category_id')
                       ->join('tv_playlist_items pi', 'pi.content_id = c.id AND pi.tv_id = ' . (int) $tvId, 'left')
                       ->whereIn('c.category_id', $catIds)
                       ->where('c.deleted_at', null)
                       ->where('c.is_active', 1)
                       ->orderBy('sort_order', 'ASC')
                       ->orderBy('c.created_at', 'DESC')
                       ->get()
                       ->getResultArray();

        return $contents;
    }

    /**
     * Update sort order for a TV playlist
     */
    public function updateSortOrder(int $tvId, array $contentIds)
    {
        // Clear existing sort orders for this TV
        $this->where('tv_id', $tvId)->delete();

        if (!empty($contentIds)) {
            $data = [];
            foreach ($contentIds as $index => $cId) {
                $data[] = [
                    'tv_id'      => $tvId,
                    'content_id' => (int) $cId,
                    'sort_order' => $index + 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }
            $this->insertBatch($data);
        }
    }
}
