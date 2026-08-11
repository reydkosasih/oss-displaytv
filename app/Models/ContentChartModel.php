<?php

namespace App\Models;

use CodeIgniter\Model;

class ContentChartModel extends Model
{
    protected $table            = 'content_charts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'content_id',
        'chart_type',
        'chart_labels',
        'chart_datasets',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get chart details by content_id
     */
    public function getByContentId(int $contentId)
    {
        return $this->where('content_id', $contentId)->first();
    }
}
