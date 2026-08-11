<?php

namespace App\Models;

use CodeIgniter\Model;

class ContentModel extends Model
{
    protected $table            = 'contents';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'category_id',
        'type',
        'title',
        'file_path',
        'file_size',
        'video_source',
        'youtube_url',
        'video_duration_seconds',
        'display_duration_seconds',
        'is_active',
        'created_by',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation disabled at Model level because Controllers perform input validation
    protected $skipValidation = true;

    /**
     * Get contents assigned to a category
     */
    public function getByCategory(int $categoryId)
    {
        return $this->where('category_id', $categoryId)
                    ->where('is_active', 1)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}
