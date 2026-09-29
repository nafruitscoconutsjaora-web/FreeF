<?php
/**
 * Cron Job: Sync Orders Status
 * Runs every minute to sync provider API order statuses
 * Usage: php cron/sync_orders.php
 */

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Services/ProviderApiService.php';

use App\Core\Database;
use App\Services\ProviderApiService;

$startTime = microtime(true);
$affectedRecords = 0;
$apiService = new ProviderApiService();

// Log job start
$cronLogId = Database::insert(
    "INSERT INTO cron_logs (job_name, status, started_at) VALUES ('sync_orders', 'started', NOW())"
);

try {
    // Select orders with active provider order ID in pending or processing status
    $orders = Database::fetchAll(
        "SELECT id, order_number, user_id, provider_id, provider_order_id, status 
         FROM orders 
         WHERE service_mode = 'api' 
           AND provider_order_id IS NOT NULL 
           AND status IN ('pending', 'processing')
         LIMIT 50"
    );

    foreach ($orders as $order) {
        try {
            $statusRes = $apiService->getOrderStatus((int)$order['provider_id'], (string)$order['provider_order_id']);
            $providerStatus = strtolower($statusRes['status'] ?? '');

            $newStatus = null;
            if (in_array($providerStatus, ['completed', 'success', 'finished'])) {
                $newStatus = 'completed';
            } elseif (in_array($providerStatus, ['canceled', 'cancelled', 'refunded'])) {
                $newStatus = 'cancelled';
            } elseif (in_array($providerStatus, ['in progress', 'processing'])) {
                $newStatus = 'processing';
            } elseif (in_array($providerStatus, ['fail', 'failed', 'partial'])) {
                $newStatus = 'failed';
            }

            if ($newStatus && $newStatus !== $order['status']) {
                $completedAt = ($newStatus === 'completed') ? date('Y-m-d H:i:s') : null;

                Database::query(
                    "UPDATE orders SET status = ?, completed_at = COALESCE(?, completed_at), updated_at = NOW() WHERE id = ?",
                    [$newStatus, $completedAt, $order['id']]
                );

                Database::insert(
                    "INSERT INTO order_status_history (order_id, previous_status, new_status, changed_by, notes)
                     VALUES (?, ?, ?, 'cron', 'Synchronized from provider API status: {$providerStatus}')",
                    [$order['id'], $order['status'], $newStatus]
                );

                Database::insert(
                    "INSERT INTO notifications (user_id, title, message, type, action_url)
                     VALUES (?, ?, ?, 'order', ?)",
                    [
                        $order['user_id'],
                        "Order #{$order['order_number']} is " . ucfirst($newStatus),
                        "Your order has been updated to {$newStatus}.",
                        "/orders/{$order['order_number']}"
                    ]
                );

                $affectedRecords++;
            }
        } catch (Exception $e) {
            error_log("Cron sync error on order {$order['id']}: " . $e->getMessage());
        }
    }

    // Finish log
    Database::query(
        "UPDATE cron_logs SET status = 'completed', affected_records = ?, output_summary = ?, finished_at = NOW() WHERE id = ?",
        [$affectedRecords, "Successfully processed " . count($orders) . " orders. Updated: {$affectedRecords}", $cronLogId]
    );

    echo "Sync completed. Affected records: {$affectedRecords}\n";
} catch (Exception $e) {
    Database::query(
        "UPDATE cron_logs SET status = 'failed', output_summary = ?, finished_at = NOW() WHERE id = ?",
        [$e->getMessage(), $cronLogId]
    );
    echo "Sync failed: " . $e->getMessage() . "\n";
}
