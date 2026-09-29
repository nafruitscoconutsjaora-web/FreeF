<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;

class OrderController extends Controller {
    public function index(Request $request): void {
        $userId = $_SESSION['user_id'];
        $status = $request->input('status');

        $query = "SELECT o.*, p.name as product_name, p.slug as product_slug 
                  FROM orders o 
                  JOIN products p ON o.product_id = p.id 
                  WHERE o.user_id = ?";
        $params = [$userId];

        if (!empty($status)) {
            $query .= " AND o.status = ?";
            $params[] = $status;
        }

        $query .= " ORDER BY o.id DESC";
        $orders = Database::fetchAll($query, $params);

        $this->view('orders/index', [
            'orders' => $orders,
            'activeStatus' => $status
        ]);
    }

    public function detail(Request $request, array $params): void {
        $orderIdentifier = $params['id'] ?? '';
        $userId = $_SESSION['user_id'];

        $order = Database::fetch(
            "SELECT o.*, p.name as product_name, p.slug as product_slug, p.short_description 
             FROM orders o 
             JOIN products p ON o.product_id = p.id 
             WHERE (o.order_number = ? OR o.id = ?) AND o.user_id = ?",
            [$orderIdentifier, is_numeric($orderIdentifier) ? (int)$orderIdentifier : 0, $userId]
        );

        if (!$order) {
            $this->redirect('/orders');
        }

        $history = Database::fetchAll(
            "SELECT * FROM order_status_history WHERE order_id = ? ORDER BY id ASC",
            [$order['id']]
        );

        $this->view('orders/detail', [
            'order' => $order,
            'history' => $history
        ]);
    }
}
