<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;

class AdminPaymentController extends Controller {
    public function index(Request $request): void {
        $search = trim((string)$request->input('q'));
        
        $query = "SELECT p.*, u.name as user_name, u.email as user_email, o.order_number 
                  FROM payments p 
                  LEFT JOIN users u ON p.user_id = u.id 
                  LEFT JOIN orders o ON p.order_id = o.id 
                  WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $query .= " AND (p.transaction_id LIKE ? OR p.gateway_order_id LIKE ? OR u.name LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $query .= " ORDER BY p.id DESC LIMIT 100";
        $payments = Database::fetchAll($query, $params);

        $totalRevenue = Database::fetch("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'completed'")['total'];

        $this->view('admin/payments/index', [
            'payments' => $payments,
            'totalRevenue' => (float)$totalRevenue,
            'search' => $search,
        ]);
    }

    public function walletLogs(Request $request): void {
        $query = "SELECT w.*, u.name as user_name, u.email as user_email 
                  FROM wallet_transactions w 
                  JOIN users u ON w.user_id = u.id 
                  ORDER BY w.id DESC LIMIT 100";
        $logs = Database::fetchAll($query);

        $this->view('admin/wallet/index', [
            'logs' => $logs
        ]);
    }
}
