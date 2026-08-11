<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'slug',
        'color',
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
     * Generate unique slug for category
     */
    public function generateSlug(string $name, ?int $ignoreId = null): string
    {
        helper('text');
        $slug = url_title($name, '-', true);

        $builder = $this->where('slug', $slug);
        if ($ignoreId !== null) {
            $builder->where('id !=', $ignoreId);
        }

        if ($builder->countAllResults() > 0) {
            $slug .= '-' . strtolower(random_string('alnum', 4));
        }

        return $slug;
    }
}
