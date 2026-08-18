<?php

namespace App\Libraries;

/**
 * AuditLogger — Service statis untuk mencatat audit log secara fail-safe.
 * Tidak akan menghentikan transaksi utama jika logging gagal.
 */
class AuditLogger
{
    /**
     * Catat satu entri audit log.
     *
     * @param string      $action      Aksi: create|update|delete|toggle|reorder|login|logout|regenerate
     * @param string      $module      Modul: content|category|tv|playlist|user|auth
     * @param string|null $description Deskripsi human-readable
     * @param array       $options     Opsi tambahan: entity_id, entity_name, old_values, new_values
     */
    public static function log(
        string $action,
        string $module,
        string $description = '',
        array $options = []
    ): void {
        try {
            $session = session();
            $request = \Config\Services::request();

            $userId   = $session->get('user_id')   ?? $options['user_id']   ?? null;
            $userName = $session->get('name')       ?? $options['user_name'] ?? 'System';
            $userRole = $session->get('role')       ?? $options['user_role'] ?? '';

            $oldValues = isset($options['old_values']) && is_array($options['old_values'])
                ? self::sanitizeValues($options['old_values'])
                : null;

            $newValues = isset($options['new_values']) && is_array($options['new_values'])
                ? self::sanitizeValues($options['new_values'])
                : null;

            $db = \Config\Database::connect();
            $db->table('audit_logs')->insert([
                'user_id'     => $userId,
                'user_name'   => (string) $userName,
                'user_role'   => (string) $userRole,
                'action'      => $action,
                'module'      => $module,
                'entity_id'   => isset($options['entity_id'])   ? (string) $options['entity_id']   : null,
                'entity_name' => isset($options['entity_name']) ? (string) $options['entity_name'] : null,
                'description' => $description,
                'old_values'  => $oldValues !== null ? json_encode($oldValues, JSON_UNESCAPED_UNICODE) : null,
                'new_values'  => $newValues !== null ? json_encode($newValues, JSON_UNESCAPED_UNICODE) : null,
                'ip_address'  => $options['ip_address'] ?? $request->getIPAddress(),
                'user_agent'  => $options['user_agent'] ?? substr((string) $request->getUserAgent(), 0, 500),
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            // Fail-safe: log ke file CI4 tapi jangan hentikan alur utama
            log_message('error', '[AuditLogger] Gagal mencatat log: ' . $e->getMessage());
        }
    }

    /**
     * Hapus field sensitif dari array values (password, token, dll).
     */
    protected static function sanitizeValues(array $values): array
    {
        $sensitiveKeys = ['password', 'password_hash', 'token', 'pin', 'secret'];

        foreach ($sensitiveKeys as $key) {
            if (array_key_exists($key, $values)) {
                $values[$key] = '[REDACTED]';
            }
        }

        return $values;
    }

    /**
     * Hitung perbedaan antara old values dan new values.
     * Berguna untuk mengisi description secara otomatis.
     *
     * @return array Daftar field yang berubah: ['field' => ['old' => ..., 'new' => ...]]
     */
    public static function diff(array $old, array $new): array
    {
        $diff = [];
        $allKeys = array_unique(array_merge(array_keys($old), array_keys($new)));

        foreach ($allKeys as $key) {
            $oldVal = $old[$key] ?? null;
            $newVal = $new[$key] ?? null;

            if (json_encode($oldVal) !== json_encode($newVal)) {
                $diff[$key] = [
                    'old' => $oldVal,
                    'new' => $newVal,
                ];
            }
        }

        return $diff;
    }
}