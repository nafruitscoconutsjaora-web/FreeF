<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Services\OrderService;
use App\Services\RazorpayService;
use App\Services\WalletService;
use Exception;

class CheckoutController extends Controller {
    public function index(Request $request): void {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            $this->redirect('/cart');
        }

        $userId = $_SESSION['user_id'];
        $walletService = new WalletService();
        $walletBalance = $walletService->getBalance($userId);

        $items = [];
        $subtotal = 0.00;
        foreach ($cart as $productId => $item) {
            $product = Database::fetch("SELECT * FROM products WHERE id = ? AND status = 'active'", [$productId]);
            if ($product) {
                $sub = (float)$product['price'] * (int)$item['quantity'];
                $subtotal += $sub;
                $items[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'ff_uid' => $item['ff_uid'] ?? '',
                    'subtotal' => $sub
                ];
            }
        }

        $discount = 0.00;
        $appliedCoupon = $_SESSION['applied_coupon'] ?? null;
        if ($appliedCoupon && $subtotal >= $appliedCoupon['min_order_amount']) {
            if ($appliedCoupon['discount_type'] === 'percentage') {
                $discount = ($subtotal * $appliedCoupon['discount_value']) / 100;
                if ($appliedCoupon['max_discount_amount'] && $discount > $appliedCoupon['max_discount_amount']) {
                    $discount = $appliedCoupon['max_discount_amount'];
                }
            } else {
                $discount = $appliedCoupon['discount_value'];
            }
        }

        $finalTotal = max(0.00, $subtotal - $discount);

        $this->view('checkout/index', [
            'items' => $items,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'finalTotal' => $finalTotal,
            'walletBalance' => $walletBalance,
            'appliedCoupon' => $appliedCoupon,
        ]);
    }

    public function process(Request $request): void {
        $paymentMethod = $request->input('payment_method'); // 'wallet' or 'razorpay'
        $cart = $_SESSION['cart'] ?? [];
        $userId = $_SESSION['user_id'];

        if (empty($cart)) {
            $this->json(['success' => false, 'message' => 'Your cart is empty.'], 400);
        }

        $orderService = new OrderService();
        $appliedCoupon = $_SESSION['applied_coupon'] ?? null;
        $couponCode = $appliedCoupon ? $appliedCoupon['code'] : null;

        $createdOrders = [];

        try {
            foreach ($cart as $productId => $item) {
                $uid = trim((string)$item['ff_uid']);
                if (empty($uid)) {
                    $uid = trim((string)$request->input('default_ff_uid'));
                }

                if (empty($uid)) {
                    throw new Exception("Free Fire Player UID is required for all products in your order.");
                }

                $res = $orderService->createOrder(
                    $userId,
                    (int)$productId,
                    (int)$item['quantity'],
                    $uid,
                    $paymentMethod,
                    $couponCode
                );

                $createdOrders[] = $res;
            }

            // Clear cart upon successful order creation
            unset($_SESSION['cart']);
            unset($_SESSION['applied_coupon']);

            $this->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'orders' => $createdOrders,
                'redirect' => '/orders'
            ]);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
