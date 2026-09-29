<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;

class UserController extends Controller {
    public function dashboard(Request $request): void {
        $userId = $_SESSION['user_id'];
        $user = Database::fetch("SELECT * FROM users WHERE id = ?", [$userId]);

        $ordersCount = Database::fetch("SELECT COUNT(*) as count FROM orders WHERE user_id = ?", [$userId])['count'] ?? 0;
        $completedCount = Database::fetch("SELECT COUNT(*) as count FROM orders WHERE user_id = ? AND status = 'completed'", [$userId])['count'] ?? 0;
        $totalSpent = Database::fetch("SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE user_id = ? AND payment_status = 'paid'", [$userId])['total'] ?? 0;
        
        $recentOrders = Database::fetchAll(
            "SELECT o.*, p.name as product_name 
             FROM orders o 
             JOIN products p ON o.product_id = p.id 
             WHERE o.user_id = ? 
             ORDER BY o.id DESC LIMIT 5",
            [$userId]
        );

        $notifications = Database::fetchAll(
            "SELECT * FROM notifications WHERE user_id = ? OR is_global = 1 ORDER BY id DESC LIMIT 5",
            [$userId]
        );

        $this->view('user/dashboard', [
            'user' => $user,
            'ordersCount' => (int)$ordersCount,
            'completedCount' => (int)$completedCount,
            'totalSpent' => (float)$totalSpent,
            'recentOrders' => $recentOrders,
            'notifications' => $notifications,
        ]);
    }

    public function coupons(Request $request): void {
        $userId = $_SESSION['user_id'];
        $user = Database::fetch("SELECT * FROM users WHERE id = ?", [$userId]);

        $activeCoupons = Database::fetchAll(
            "SELECT * FROM coupons WHERE status = 'active' AND (expires_at IS NULL OR expires_at > NOW()) ORDER BY id DESC"
        );

        $this->view('user/coupons', [
            'user' => $user,
            'coupons' => $activeCoupons,
        ]);
    }

    public function notifications(Request $request): void {
        $userId = $_SESSION['user_id'];
        $user = Database::fetch("SELECT * FROM users WHERE id = ?", [$userId]);

        $notifications = Database::fetchAll(
            "SELECT * FROM notifications WHERE user_id = ? OR is_global = 1 ORDER BY id DESC LIMIT 50",
            [$userId]
        );

        $this->view('user/notifications', [
            'user' => $user,
            'notifications' => $notifications,
        ]);
    }

    public function profile(Request $request): void {
        $userId = $_SESSION['user_id'];
        $user = Database::fetch("SELECT * FROM users WHERE id = ?", [$userId]);

        $this->view('user/profile', [
            'user' => $user,
            'error' => $_SESSION['profile_error'] ?? null,
            'success' => $_SESSION['profile_success'] ?? null,
        ]);
        unset($_SESSION['profile_error'], $_SESSION['profile_success']);
    }

    public function updateProfile(Request $request): void {
        $userId = $_SESSION['user_id'];
        $name = trim((string)$request->input('name'));
        $phone = trim((string)$request->input('phone'));
        $ffUid = trim((string)$request->input('ff_player_uid'));

        if (empty($name)) {
            $_SESSION['profile_error'] = "Name is required.";
            $this->redirect('/profile');
            return;
        }

        Database::query(
            "UPDATE users SET name = ?, phone = ?, updated_at = NOW() WHERE id = ?",
            [$name, $phone, $userId]
        );

        $_SESSION['user_name'] = $name;
        $_SESSION['profile_success'] = "Profile updated successfully.";
        $this->redirect('/profile');
    }

    public function updatePassword(Request $request): void {
        $userId = $_SESSION['user_id'];
        $oldPassword = (string)$request->input('old_password');
        $newPassword = (string)$request->input('new_password');
        $confirmPassword = (string)$request->input('confirm_password');

        $user = Database::fetch("SELECT password FROM users WHERE id = ?", [$userId]);

        if (!password_verify($oldPassword, $user['password'])) {
            $_SESSION['profile_error'] = "Current password is incorrect.";
            $this->redirect('/profile');
            return;
        }

        if (strlen($newPassword) < 6) {
            $_SESSION['profile_error'] = "New password must be at least 6 characters long.";
            $this->redirect('/profile');
            return;
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['profile_error'] = "New passwords do not match.";
            $this->redirect('/profile');
            return;
        }

        Database::query(
            "UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?",
            [password_hash($newPassword, PASSWORD_BCRYPT), $userId]
        );

        $_SESSION['profile_success'] = "Password changed successfully.";
        $this->redirect('/profile');
    }
}
