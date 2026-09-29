<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use Exception;

class AdminUserController extends Controller {
    public function index(Request $request): void {
        $search = trim((string)$request->input('q'));
        
        $query = "SELECT u.*, 
                  (SELECT COUNT(*) FROM orders o WHERE o.user_id = u.id) as total_orders,
                  (SELECT COALESCE(SUM(total_amount), 0) FROM orders o WHERE o.user_id = u.id AND o.payment_status = 'paid') as total_spent
                  FROM users u WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $query .= " AND (u.name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $query .= " ORDER BY u.id DESC LIMIT 100";
        $users = Database::fetchAll($query, $params);

        $this->view('admin/users/index', [
            'users' => $users,
            'search' => $search,
        ]);
    }

    public function detail(Request $request, array $params): void {
        $userId = (int)($params['id'] ?? 0);
        $user = Database::fetch("SELECT * FROM users WHERE id = ?", [$userId]);

        if (!$user) {
            $this->redirect('/admin/users');
        }

        $orders = Database::fetchAll(
            "SELECT o.*, p.name as product_name 
             FROM orders o 
             JOIN products p ON o.product_id = p.id 
             WHERE o.user_id = ? 
             ORDER BY o.id DESC LIMIT 20",
            [$userId]
        );

        $walletLogs = Database::fetchAll(
            "SELECT * FROM wallet_transactions WHERE user_id = ? ORDER BY id DESC LIMIT 20",
            [$userId]
        );

        $this->view('admin/users/detail', [
            'user' => $user,
            'orders' => $orders,
            'walletLogs' => $walletLogs,
        ]);
    }

    public function adjustBalance(Request $request, array $params): void {
        $userId = (int)($params['id'] ?? 0);
        $type = (string)$request->input('type'); // credit or debit
        $amount = (float)$request->input('amount');
        $reason = trim((string)$request->input('reason', 'Admin adjustment'));

        if ($amount <= 0) {
            $this->json(['success' => false, 'message' => 'Amount must be greater than 0.'], 422);
        }

        Database::beginTransaction();
        try {
            $user = Database::fetch("SELECT wallet_balance FROM users WHERE id = ? FOR UPDATE", [$userId]);
            if (!$user) {
                throw new Exception('User not found.');
            }

            $currentBal = (float)$user['wallet_balance'];
            if ($type === 'debit') {
                if ($currentBal < $amount) {
                    throw new Exception('Insufficient user balance.');
                }
                $newBal = $currentBal - $amount;
            } else {
                $newBal = $currentBal + $amount;
            }

            Database::query("UPDATE users SET wallet_balance = ?, updated_at = NOW() WHERE id = ?", [$newBal, $userId]);

            Database::insert(
                "INSERT INTO wallet_transactions (user_id, type, amount, balance_after, description, reference_id)
                 VALUES (?, ?, ?, ?, ?, ?)",
                [$userId, $type, $amount, $newBal, $reason, 'ADMIN-' . uniqid()]
            );

            Database::commit();
            $this->json(['success' => true, 'message' => 'Wallet balance adjusted successfully.', 'new_balance' => $newBal]);
        } catch (Exception $e) {
            Database::rollBack();
            $this->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function updateStatus(Request $request, array $params): void {
        $userId = (int)($params['id'] ?? 0);
        $status = (string)$request->input('status');

        if (!in_array($status, ['active', 'banned', 'suspended'], true)) {
            $this->json(['success' => false, 'message' => 'Invalid status.'], 422);
        }

        Database::query("UPDATE users SET status = ?, updated_at = NOW() WHERE id = ?", [$status, $userId]);
        $this->json(['success' => true, 'message' => 'User status updated to ' . $status]);
    }
}
