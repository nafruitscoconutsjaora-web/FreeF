<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Services\ProviderApiService;
use Exception;

class AdminOrderController extends Controller {
    public function index(Request $request): void {
        $status = $request->input('status');
        $search = trim((string)$request->input('q'));

        $query = "SELECT o.*, u.name as user_name, u.email as user_email, p.name as product_name 
                  FROM orders o 
                  JOIN users u ON o.user_id = u.id 
                  JOIN products p ON o.product_id = p.id 
                  WHERE 1=1";
        $params = [];

        if (!empty($status)) {
            $query .= " AND o.status = ?";
            $params[] = $status;
        }

        if (!empty($search)) {
            $query .= " AND (o.order_number LIKE ? OR o.customer_ff_uid LIKE ? OR u.name LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $query .= " ORDER BY o.id DESC LIMIT 100";
        $orders = Database::fetchAll($query, $params);

        $this->view('admin/orders/index', [
            'orders' => $orders,
            'activeStatus' => $status,
            'search' => $search
        ]);
    }

    public function updateStatus(Request $request, array $params): void {
        $orderId = (int)($params['id'] ?? 0);
        $newStatus = (string)$request->input('status');
        $notes = trim((string)$request->input('notes', ''));

        $order = Database::fetch("SELECT * FROM orders WHERE id = ?", [$orderId]);
        if (!$order) {
            $this->json(['success' => false, 'message' => 'Order not found.'], 404);
        }

        $prevStatus = $order['status'];

        Database::beginTransaction();
        try {
            $completedAt = ($newStatus === 'completed') ? date('Y-m-d H:i:s') : null;

            Database::query(
                "UPDATE orders SET status = ?, admin_notes = ?, completed_at = COALESCE(?, completed_at), updated_at = NOW() WHERE id = ?",
                [$newStatus, $notes, $completedAt, $orderId]
            );

            Database::insert(
                "INSERT INTO order_status_history (order_id, previous_status, new_status, changed_by, notes)
                 VALUES (?, ?, ?, 'admin', ?)",
                [$orderId, $prevStatus, $newStatus, $notes ?: "Status changed by admin"]
            );

            // Notify user
            Database::insert(
                "INSERT INTO notifications (user_id, title, message, type, action_url)
                 VALUES (?, ?, ?, 'order', ?)",
                [
                    $order['user_id'],
                    "Order #{$order['order_number']} is " . ucfirst($newStatus),
                    "Your order status has been updated to {$newStatus}.",
                    "/orders/{$order['order_number']}"
                ]
            );

            Database::commit();

            $this->json(['success' => true, 'message' => "Order #{$order['order_number']} updated to {$newStatus}."]);
        } catch (Exception $e) {
            Database::rollBack();
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
