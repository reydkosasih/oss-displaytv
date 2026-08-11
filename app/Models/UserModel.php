<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'email',
        'password',
        'role',
        'department',
        'is_active',
        'last_login_at',
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
     * Find user by email for authentication
     */
    public function findByEmail(string $email)
    {
        return $this->where('email', $email)
                    ->where('is_active', 1)
                    ->first();
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin(int $userId)
    {
        return $this->update($userId, [
            'last_login_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
