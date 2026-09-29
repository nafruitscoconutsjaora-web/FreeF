<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;

class ReferralController extends Controller {
    public function index(Request $request): void {
        $userId = $_SESSION['user_id'];
        $user = Database::fetch("SELECT referral_code FROM users WHERE id = ?", [$userId]);

        $referrals = Database::fetchAll(
            "SELECT r.*, u.name as referred_user_name, u.created_at as joined_at
             FROM referrals r 
             JOIN users u ON r.referred_user_id = u.id 
             WHERE r.referrer_id = ? 
             ORDER BY r.id DESC",
            [$userId]
        );

        $totalEarnings = Database::fetch(
            "SELECT COALESCE(SUM(total_earned), 0) as total FROM referrals WHERE referrer_id = ?",
            [$userId]
        )['total'];

        $config = require __DIR__ . '/../../config/app.php';
        $referralLink = rtrim($config['url'], '/') . '/register?ref=' . $user['referral_code'];

        $this->view('referral/index', [
            'referralCode' => $user['referral_code'],
            'referralLink' => $referralLink,
            'referrals' => $referrals,
            'totalEarnings' => (float)$totalEarnings,
        ]);
    }
}
