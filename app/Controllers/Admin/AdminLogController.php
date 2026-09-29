<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;

class AdminLogController extends Controller {
    public function index(Request $request): void {
        $logs = Database::fetchAll("SELECT * FROM api_logs ORDER BY id DESC LIMIT 100");
        $statusHistory = Database::fetchAll(
            "SELECT h.*, o.order_number 
             FROM order_status_history h 
             JOIN orders o ON h.order_id = o.id 
             ORDER BY h.id DESC LIMIT 50"
        );

        $this->view('admin/logs/index', [
            'apiLogs' => $logs,
            'statusHistory' => $statusHistory
        ]);
    }
}
