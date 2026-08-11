<?php

namespace App\Models;

use CodeIgniter\Model;

class TvModel extends Model
{
    protected $table            = 'tvs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'slug',
        'location',
        'thumbnail',
        'pin',
        'pin_updated_at',
        'orientation',
        'is_active',
        'last_seen_at',
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
     * Generate unique 6-digit numeric PIN
     */
    public function generatePin(): string
    {
        do {
            $pin = (string) rand(100000, 999999);
            $count = $this->where('pin', $pin)->where('deleted_at', null)->countAllResults();
        } while ($count > 0);

        return $pin;
    }

    /**
     * Generate unique slug for TV
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
