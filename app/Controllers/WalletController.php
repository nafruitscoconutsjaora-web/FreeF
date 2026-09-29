<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Services\WalletService;

class WalletController extends Controller {
    public function index(Request $request): void {
        $userId = $_SESSION['user_id'];
        $walletService = new WalletService();
        $balance = $walletService->getBalance($userId);

        $transactions = Database::fetchAll(
            "SELECT * FROM wallet_transactions WHERE user_id = ? ORDER BY id DESC LIMIT 50",
            [$userId]
        );

        $this->view('wallet/index', [
            'balance' => $balance,
            'transactions' => $transactions
        ]);
    }
}
