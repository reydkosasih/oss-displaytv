<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;
use App\Libraries\AuditLogger;

class AuditLogController extends BaseController
{
    protected AuditLogModel $auditLogModel;
    protected bool $isSuperadmin;
    protected int $currentUserId;

    public function __construct()
    {
        $this->auditLogModel = new AuditLogModel();
        $this->isSuperadmin  = session()->get('role') === 'superadmin';
        $this->currentUserId = (int) (session()->get('user_id') ?? 0);
    }

    /**
     * Halaman utama Audit Logs
     */
    public function index()
    {
        // Daftar user untuk filter dropdown (superadmin only)
        $users = $this->isSuperadmin
            ? $this->auditLogModel->getDistinctUsers()
            : [];

        return view('admin/audit_log/index', [
            'title'        => 'Audit Logs',
            'isSuperadmin' => $this->isSuperadmin,
            'users'        => $users,
        ]);
    }

    /**
     * Endpoint AJAX: Ambil list log terfilter (JSON)
     */
    public function list()
    {
        $filters = [
            'search'    => $this->request->getGet('search')    ?? '',
            'module'    => $this->request->getGet('module')    ?? '',
            'action'    => $this->request->getGet('action')    ?? '',
            'user_id'   => $this->request->getGet('user_id')   ?? '',
            'date_from' => $this->request->getGet('date_from') ?? '',
            'date_to'   => $this->request->getGet('date_to')   ?? '',
        ];

        $limit  = (int) ($this->request->getGet('limit')  ?? 50);
        $offset = (int) ($this->request->getGet('offset') ?? 0);

        // Clamp limit
        $limit = min(max($limit, 1), 200);

        $logs  = $this->auditLogModel->getFilteredLogs($filters, $this->currentUserId, $this->isSuperadmin, $limit, $offset);
        $total = $this->auditLogModel->countFilteredLogs($filters, $this->currentUserId, $this->isSuperadmin);
        $stats = $this->auditLogModel->getSummaryStats($this->currentUserId, $this->isSuperadmin);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $logs,
            'total'  => $total,
            'stats'  => $stats,
        ]);
    }

    /**
     * Endpoint AJAX: Ambil detail satu log entry (untuk modal diff)
     */
    public function getJson($id = null)
    {
        $log = $this->auditLogModel->find($id);

        if (!$log) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Log entry tidak ditemukan.',
            ]);
        }

        // Scope check: non-superadmin hanya boleh akses log miliknya
        if (!$this->isSuperadmin && (int)$log['user_id'] !== $this->currentUserId) {
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki akses ke log ini.',
            ]);
        }

        // Decode JSON values if string
        $log['old_values'] = is_string($log['old_values']) 
            ? json_decode($log['old_values'], true) 
            : (is_array($log['old_values']) ? $log['old_values'] : null);

        $log['new_values'] = is_string($log['new_values']) 
            ? json_decode($log['new_values'], true) 
            : (is_array($log['new_values']) ? $log['new_values'] : null);

        // Compute diff
        $log['diff'] = [];
        if ($log['old_values'] && $log['new_values']) {
            $allKeys = array_unique(array_merge(array_keys($log['old_values']), array_keys($log['new_values'])));
            foreach ($allKeys as $key) {
                $oldVal = $log['old_values'][$key] ?? null;
                $newVal = $log['new_values'][$key] ?? null;
                if (json_encode($oldVal) !== json_encode($newVal)) {
                    $log['diff'][$key] = ['old' => $oldVal, 'new' => $newVal];
                }
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $log,
        ]);
    }

    /**
     * Ekspor data audit log ke CSV (Direct Download)
     */
    public function exportCsv()
    {
        $filters = [
            'search'    => $this->request->getGet('search')    ?? '',
            'module'    => $this->request->getGet('module')    ?? '',
            'action'    => $this->request->getGet('action')    ?? '',
            'user_id'   => $this->request->getGet('user_id')   ?? '',
            'date_from' => $this->request->getGet('date_from') ?? '',
            'date_to'   => $this->request->getGet('date_to')   ?? '',
        ];

        // Max 5000 baris untuk ekspor
        $logs = $this->auditLogModel->getFilteredLogs($filters, $this->currentUserId, $this->isSuperadmin, 5000, 0);

        $filename = 'audit_logs_' . date('Ymd_His') . '.csv';

        // Memory buffer for CSV
        $output = fopen('php://temp', 'r+');
        // UTF-8 BOM for Microsoft Excel compatibility
        fputs($output, "\xEF\xBB\xBF");

        // Header row
        fputcsv($output, [
            'ID', 'Waktu', 'Pengguna', 'Role', 'Modul', 'Tipe Aksi',
            'Nama Entitas', 'ID Entitas', 'Deskripsi Aktivitas', 'IP Address',
        ]);

        foreach ($logs as $log) {
            fputcsv($output, [
                $log['id'],
                $log['created_at'],
                $log['user_name'],
                $log['user_role'],
                $log['module'],
                $log['action'],
                $log['entity_name'] ?? '-',
                $log['entity_id']   ?? '-',
                $log['description'],
                $log['ip_address']  ?? '-',
            ]);
        }

        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return $this->response->download($filename, $csvContent);
    }

    /**
     * Endpoint AJAX: Purge log lama (Superadmin only)
     */
    public function purge()
    {
        if (!$this->isSuperadmin) {
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => 'error',
                'message' => 'Akses ditolak. Hanya Superadmin yang dapat membersihkan log.',
            ]);
        }

        $days = (int) ($this->request->getPost('days') ?? 30);

        if ($days < 0) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Nilai periode tidak valid.',
            ]);
        }

        $deleted = $this->auditLogModel->purgeOlderThan($days);

        $periodText = $days === 0 ? 'seluruh periode (semua data)' : "lebih dari {$days} hari";

        // Record a new audit log for the purge action
        AuditLogger::log('delete', 'auth', "Pembersihan log audit: {$deleted} entri log ({$periodText}) telah dibersihkan oleh Superadmin.");

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => "Berhasil membersihkan {$deleted} entri log ({$periodText}).",
            'deleted' => $deleted,
        ]);
    }
}