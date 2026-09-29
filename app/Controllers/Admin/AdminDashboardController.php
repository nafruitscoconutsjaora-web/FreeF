<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;

class AdminDashboardController extends Controller {
    public function showLogin(Request $request): void {
        if (!empty($_SESSION['admin_id']) && !empty($_SESSION['admin_logged_in'])) {
            $this->redirect('/admin');
        }
        $error = $_SESSION['admin_error'] ?? null;
        unset($_SESSION['admin_error']);
        $this->view('admin/login', ['error' => $error]);
    }

    public function login(Request $request): void {
        $email = trim((string)$request->input('email', ''));
        $password = (string)$request->input('password', '');
        $isAjax = $request->input('ajax') || !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'application/json');

        if (empty($email) || empty($password)) {
            $msg = 'Please enter both administrative email and password.';
            if ($isAjax) $this->json(['success' => false, 'message' => $msg], 422);
            $_SESSION['admin_error'] = $msg;
            $this->redirect('/admin/login');
        }

        $admin = Database::fetch("SELECT * FROM admins WHERE email = ? AND status = 'active' LIMIT 1", [$email]);
        if (!$admin || !password_verify($password, $admin['password'])) {
            $msg = 'Invalid administrative credentials.';
            if ($isAjax) $this->json(['success' => false, 'message' => $msg], 401);
            $_SESSION['admin_error'] = $msg;
            $this->redirect('/admin/login');
        }

        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int)$admin['id'];
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin'] = [
            'id' => (int)$admin['id'],
            'name' => $admin['name'],
            'email' => $admin['email'],
            'role' => $admin['role'],
        ];

        Database::query("UPDATE admins SET last_login_at = NOW() WHERE id = ?", [$admin['id']]);

        if ($isAjax) {
            $this->json(['success' => true, 'redirect' => '/admin']);
        }
        $this->redirect('/admin');
    }

    public function index(Request $request): void {
        // Real database statistics
        $totalSales = Database::fetch("SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE payment_status = 'paid'")['total'];
        $totalOrders = Database::fetch("SELECT COUNT(*) as count FROM orders")['count'];
        $pendingOrders = Database::fetch("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'")['count'];
        $completedOrders = Database::fetch("SELECT COUNT(*) as count FROM orders WHERE status = 'completed'")['count'];
        $totalUsers = Database::fetch("SELECT COUNT(*) as count FROM users")['count'];

        // Provider balance summary
        $providers = Database::fetchAll("SELECT name, currency, balance, last_balance_check FROM providers WHERE status = 'active'");

        // Recent orders
        $recentOrders = Database::fetchAll(
            "SELECT o.*, u.name as user_name, p.name as product_name 
             FROM orders o 
             JOIN users u ON o.user_id = u.id 
             JOIN products p ON o.product_id = p.id 
             ORDER BY o.id DESC LIMIT 10"
        );

        $this->view('admin/dashboard', [
            'totalSales' => (float)$totalSales,
            'totalOrders' => (int)$totalOrders,
            'pendingOrders' => (int)$pendingOrders,
            'completedOrders' => (int)$completedOrders,
            'totalUsers' => (int)$totalUsers,
            'providers' => $providers,
            'recentOrders' => $recentOrders,
        ]);
    }

    public function cronRun(Request $request): void {
        $ordersCount = Database::fetch("SELECT COUNT(*) as count FROM orders WHERE delivery_mode = 'api' AND status IN ('pending', 'processing')")['count'];
        $providers = Database::fetchAll("SELECT name, balance, currency, last_balance_check FROM providers");

        $this->view('admin/cron/index', [
            'pendingSyncOrders' => (int)$ordersCount,
            'providers' => $providers
        ]);
    }

    public function reports(Request $request): void {
        $dailySales = Database::fetchAll(
            "SELECT DATE(created_at) as sale_date, COUNT(*) as count, SUM(total_amount) as amount 
             FROM orders 
             WHERE payment_status = 'paid' 
             GROUP BY DATE(created_at) 
             ORDER BY sale_date DESC LIMIT 30"
        );

        $popularProducts = Database::fetchAll(
            "SELECT p.name, COUNT(o.id) as order_count, SUM(o.total_amount) as total_volume 
             FROM orders o 
             JOIN products p ON o.product_id = p.id 
             WHERE o.payment_status = 'paid' 
             GROUP BY p.id 
             ORDER BY order_count DESC LIMIT 10"
        );

        $this->view('admin/reports/index', [
            'dailySales' => $dailySales,
            'popularProducts' => $popularProducts
        ]);
    }

    public function logout(Request $request): void {
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_logged_in']);
        unset($_SESSION['admin']);
        session_regenerate_id(true);
        $this->redirect('/admin/login');
    }
}
