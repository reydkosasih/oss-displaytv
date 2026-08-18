<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table      = 'audit_logs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id', 'user_name', 'user_role', 'action', 'module',
        'entity_id', 'entity_name', 'description',
        'old_values', 'new_values', 'ip_address', 'user_agent',
    ];

    protected $useTimestamps  = false;  // managed manually
    protected $useSoftDeletes = false;

    /**
     * Ambil daftar log dengan filter dinamis, scope by role.
     *
     * @param  array $filters
     *   - search        : string (description | entity_name)
     *   - module        : string
     *   - action        : string
     *   - user_id       : int    (jika non-superadmin, otomatis diset dari sesi)
     *   - date_from     : string Y-m-d
     *   - date_to       : string Y-m-d
     * @param  int   $currentUserId  ID user yg login saat ini
     * @param  bool  $isSuperadmin   Jika false, otomatis scope ke currentUserId
     * @param  int   $limit
     * @param  int   $offset
     * @return array
     */
    public function getFilteredLogs(
        array $filters,
        int $currentUserId,
        bool $isSuperadmin,
        int $limit = 50,
        int $offset = 0
    ): array {
        $builder = $this->db->table('audit_logs');

        // Role-scope: admin hanya bisa lihat log dirinya sendiri
        if (!$isSuperadmin) {
            $builder->where('user_id', $currentUserId);
        }

        // Filter: search keyword
        if (!empty($filters['search'])) {
            $keyword = $this->db->escapeString($filters['search']);
            $builder->groupStart()
                ->like('description', $filters['search'])
                ->orLike('entity_name', $filters['search'])
                ->orLike('user_name', $filters['search'])
                ->groupEnd();
        }

        // Filter: module
        if (!empty($filters['module'])) {
            $builder->where('module', $filters['module']);
        }

        // Filter: action
        if (!empty($filters['action'])) {
            $builder->where('action', $filters['action']);
        }

        // Filter: user_id (superadmin only)
        if ($isSuperadmin && !empty($filters['user_id'])) {
            $builder->where('user_id', (int) $filters['user_id']);
        }

        // Filter: date_from
        if (!empty($filters['date_from'])) {
            $builder->where('created_at >=', $filters['date_from'] . ' 00:00:00');
        }

        // Filter: date_to
        if (!empty($filters['date_to'])) {
            $builder->where('created_at <=', $filters['date_to'] . ' 23:59:59');
        }

        $builder->orderBy('created_at', 'DESC');
        $builder->limit($limit, $offset);

        return $builder->get()->getResultArray();
    }

    /**
     * Hitung total record dengan filter yang sama (untuk pagination).
     */
    public function countFilteredLogs(
        array $filters,
        int $currentUserId,
        bool $isSuperadmin
    ): int {
        $builder = $this->db->table('audit_logs');

        if (!$isSuperadmin) {
            $builder->where('user_id', $currentUserId);
        }

        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('description', $filters['search'])
                ->orLike('entity_name', $filters['search'])
                ->orLike('user_name', $filters['search'])
                ->groupEnd();
        }

        if (!empty($filters['module'])) {
            $builder->where('module', $filters['module']);
        }

        if (!empty($filters['action'])) {
            $builder->where('action', $filters['action']);
        }

        if ($isSuperadmin && !empty($filters['user_id'])) {
            $builder->where('user_id', (int) $filters['user_id']);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('created_at >=', $filters['date_from'] . ' 00:00:00');
        }

        if (!empty($filters['date_to'])) {
            $builder->where('created_at <=', $filters['date_to'] . ' 23:59:59');
        }

        return $builder->countAllResults();
    }

    /**
     * Hapus log lama berdasarkan jumlah hari (khusus Superadmin).
     * Jika $days <= 0, hapus seluruh data log (Reset Log).
     */
    public function purgeOlderThan(int $days): int
    {
        if ($days <= 0) {
            $total = $this->db->table('audit_logs')->countAllResults(false);
            $this->db->table('audit_logs')->emptyTable();
            return $total;
        }

        $cutoff = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        $this->db->table('audit_logs')->where('created_at <', $cutoff)->delete();
        return $this->db->affectedRows();
    }

    /**
     * Ambil daftar user yang pernah melakukan aksi (untuk filter dropdown Superadmin).
     */
    public function getDistinctUsers(): array
    {
        return $this->db->table('audit_logs')
            ->select('user_id, user_name, user_role')
            ->where('user_id IS NOT NULL', null, false)
            ->groupBy('user_id, user_name, user_role')
            ->orderBy('user_name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Statistik ringkasan untuk header halaman audit log.
     */
    public function getSummaryStats(int $currentUserId, bool $isSuperadmin): array
    {
        $base = $isSuperadmin
            ? $this->db->table('audit_logs')
            : $this->db->table('audit_logs')->where('user_id', $currentUserId);

        $total = (clone $base)->countAllResults(false);

        $today = date('Y-m-d');
        $todayCount = $this->db->table('audit_logs');
        if (!$isSuperadmin) $todayCount->where('user_id', $currentUserId);
        $todayCount = $todayCount->where('created_at >=', $today . ' 00:00:00')->countAllResults();

        return [
            'total' => $total,
            'today' => $todayCount,
        ];
    }
}