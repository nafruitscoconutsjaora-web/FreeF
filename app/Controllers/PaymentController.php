<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Services\RazorpayService;
use App\Services\WalletService;
use Exception;

class PaymentController extends Controller {
    private RazorpayService $razorpay;
    private WalletService $walletService;

    public function __construct() {
        $this->razorpay = new RazorpayService();
        $this->walletService = new WalletService();
    }

    /**
     * Create Razorpay order for wallet topup
     */
    public function createRazorpayOrder(Request $request): void {
        if (empty($_SESSION['user_id'])) {
            $this->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $amount = (float)$request->input('amount');
        if ($amount < 10.00) {
            $this->json(['success' => false, 'message' => 'Minimum recharge amount is ₹10.00'], 422);
        }

        if (!$this->razorpay->isConfigured()) {
            $this->json([
                'success' => false,
                'message' => 'Razorpay payment gateway is not configured yet. Please configure Razorpay Key ID and Secret in settings.'
            ], 503);
        }

        try {
            $receipt = 'RCPT-' . time() . '-' . $_SESSION['user_id'];
            $rzpOrder = $this->razorpay->createOrder($amount, $receipt, [
                'user_id' => $_SESSION['user_id'],
                'type' => 'wallet_topup'
            ]);

            // Save payment row in database
            Database::insert(
                "INSERT INTO payments (user_id, payment_method, razorpay_order_id, amount, currency, purpose, status)
                 VALUES (?, 'razorpay', ?, ?, 'INR', 'wallet_topup', 'created')",
                [$_SESSION['user_id'], $rzpOrder['id'], $amount]
            );

            $this->json([
                'success' => true,
                'order_id' => $rzpOrder['id'],
                'amount' => $rzpOrder['amount'],
                'currency' => 'INR',
                'key_id' => $this->razorpay->getKeyId(),
                'user' => $_SESSION['user']
            ]);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Verify payment signature and credit wallet
     */
    public function verifyPayment(Request $request): void {
        if (empty($_SESSION['user_id'])) {
            $this->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $razorpayOrderId = trim((string)$request->input('razorpay_order_id'));
        $razorpayPaymentId = trim((string)$request->input('razorpay_payment_id'));
        $razorpaySignature = trim((string)$request->input('razorpay_signature'));

        if (empty($razorpayOrderId) || empty($razorpayPaymentId) || empty($razorpaySignature)) {
            $this->json(['success' => false, 'message' => 'Incomplete payment parameters.'], 422);
        }

        // Verify cryptographic signature
        $isValid = $this->razorpay->verifySignature($razorpayOrderId, $razorpayPaymentId, $razorpaySignature);
        if (!$isValid) {
            Database::query(
                "UPDATE payments SET status = 'failed', error_description = 'Signature verification failed' WHERE razorpay_order_id = ?",
                [$razorpayOrderId]
            );
            $this->json(['success' => false, 'message' => 'Invalid payment signature.'], 400);
        }

        // Fetch payment record
        $payment = Database::fetch("SELECT * FROM payments WHERE razorpay_order_id = ?", [$razorpayOrderId]);
        if (!$payment) {
            $this->json(['success' => false, 'message' => 'Payment record not found.'], 404);
        }

        if ($payment['status'] === 'captured') {
            $this->json(['success' => true, 'message' => 'Payment already processed.']);
        }

        Database::beginTransaction();
        try {
            // Update payment record
            Database::query(
                "UPDATE payments SET razorpay_payment_id = ?, razorpay_signature = ?, status = 'captured', updated_at = NOW() WHERE id = ?",
                [$razorpayPaymentId, $razorpaySignature, $payment['id']]
            );

            // Credit wallet
            $this->walletService->credit(
                (int)$payment['user_id'],
                (float)$payment['amount'],
                'payment_gateway',
                $razorpayPaymentId,
                "Wallet Top-up via Razorpay (Payment ID: {$razorpayPaymentId})"
            );

            // Notify user
            Database::insert(
                "INSERT INTO notifications (user_id, title, message, type) VALUES (?, ?, ?, 'wallet')",
                [
                    $payment['user_id'],
                    "Wallet Recharged Successfully",
                    "₹" . number_format($payment['amount'], 2) . " has been credited to your wallet balance."
                ]
            );

            Database::commit();

            $this->json([
                'success' => true,
                'message' => 'Payment verified and ₹' . number_format($payment['amount'], 2) . ' credited to your wallet!'
            ]);
        } catch (Exception $e) {
            Database::rollBack();
            $this->json(['success' => false, 'message' => 'Error crediting wallet: ' . $e->getMessage()], 500);
        }
    }
}
