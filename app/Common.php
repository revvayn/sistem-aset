<?php

/**
 * Helper global untuk mencatat aktivitas (audit log).
 */
if (!function_exists('log_activity')) {
    function log_activity(string $action, string $entity = null, $entityId = null, string $description = null): void
    {
        try {
            $db = \Config\Database::connect();
            $db->table('activity_logs')->insert([
                'user_id'     => session()->get('id'),
                'action'      => $action,
                'entity'      => $entity,
                'entity_id'   => $entityId,
                'description' => $description,
                'ip_address'  => (string) service('request')->getIPAddress(),
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            // Jangan ganggu alur utama jika logging gagal
        }
    }
}