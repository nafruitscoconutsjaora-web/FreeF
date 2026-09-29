<?php
namespace App\Services;

use App\Core\Database;
use Exception;

class WalletService {
    /**
     * Credit user wallet safely within transaction
     */
    public function credit(int $userId, float $amount, string $source, ?string $referenceId, string $description): array {
        if ($amount <= 0) {
            throw new Exception("Credit amount must be greater than zero.");
        }

        Database::beginTransaction();
        try {
            // Prevent duplicate credit for same gateway reference ID
            if ($referenceId) {
                $existingTx = Database::fetch(
                    "SELECT id FROM wallet_transactions WHERE reference_id = ? AND status = 'completed' AND type = 'credit'",
                    [$referenceId]
                );
                if ($existingTx) {
                    Database::rollBack();
                    throw new Exception("Duplicate payment credit prevented for reference [{$referenceId}].");
                }
            }

            // Ensure wallet exists or create row
            $wallet = Database::fetch("SELECT * FROM wallets WHERE user_id = ? FOR UPDATE", [$userId]);
            if (!$wallet) {
                Database::insert("INSERT INTO wallets (user_id, balance, total_credited, total_spent) VALUES (?, 0, 0, 0)", [$userId]);
                $wallet = Database::fetch("SELECT * FROM wallets WHERE user_id = ? FOR UPDATE", [$userId]);
            }

            $opening = (float)$wallet['balance'];
            $closing = $opening + $amount;

            // Update wallet balance
            Database::query(
                "UPDATE wallets SET balance = ?, total_credited = total_credited + ?, updated_at = NOW() WHERE user_id = ?",
                [$closing, $amount, $userId]
            );

            // Record transaction
            $txId = Database::insert(
                "INSERT INTO wallet_transactions (wallet_id, user_id, type, amount, opening_balance, closing_balance, source, reference_id, description, status)
                 VALUES (?, ?, 'credit', ?, ?, ?, ?, ?, ?, 'completed')",
                [$wallet['id'], $userId, $amount, $opening, $closing, $source, $referenceId, $description]
            );

            Database::commit();

            return [
                'transaction_id' => $txId,
                'opening_balance' => $opening,
                'closing_balance' => $closing,
                'amount' => $amount
            ];
        } catch (Exception $e) {
            Database::rollBack();
            throw $e;
        }
    }

    /**
     * Debit user wallet safely within transaction
     */
    public function debit(int $userId, float $amount, string $source, ?string $referenceId, string $description): array {
        if ($amount <= 0) {
            throw new Exception("Debit amount must be greater than zero.");
        }

        Database::beginTransaction();
        try {
            $wallet = Database::fetch("SELECT * FROM wallets WHERE user_id = ? FOR UPDATE", [$userId]);
            if (!$wallet) {
                Database::rollBack();
                throw new Exception("User wallet not found.");
            }

            $opening = (float)$wallet['balance'];
            if ($opening < $amount) {
                Database::rollBack();
                throw new Exception("Insufficient wallet balance. Available: ₹" . number_format($opening, 2) . ", Required: ₹" . number_format($amount, 2));
            }

            $closing = $opening - $amount;

            Database::query(
                "UPDATE wallets SET balance = ?, total_spent = total_spent + ?, updated_at = NOW() WHERE user_id = ?",
                [$closing, $amount, $userId]
            );

            $txId = Database::insert(
                "INSERT INTO wallet_transactions (wallet_id, user_id, type, amount, opening_balance, closing_balance, source, reference_id, description, status)
                 VALUES (?, ?, 'debit', ?, ?, ?, ?, ?, ?, 'completed')",
                [$wallet['id'], $userId, $amount, $opening, $closing, $source, $referenceId, $description]
            );

            Database::commit();

            return [
                'transaction_id' => $txId,
                'opening_balance' => $opening,
                'closing_balance' => $closing,
                'amount' => $amount
            ];
        } catch (Exception $e) {
            Database::rollBack();
            throw $e;
        }
    }

    /**
     * Get user's current wallet balance
     */
    public function getBalance(int $userId): float {
        $wallet = Database::fetch("SELECT balance FROM wallets WHERE user_id = ?", [$userId]);
        return $wallet ? (float)$wallet['balance'] : 0.00;
    }
}
