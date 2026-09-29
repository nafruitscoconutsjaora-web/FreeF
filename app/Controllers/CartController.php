<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Services\OrderService;
use Exception;

class CartController extends Controller {
    public function index(Request $request): void {
        $cart = $_SESSION['cart'] ?? [];
        $items = [];
        $total = 0.00;

        foreach ($cart as $productId => $item) {
            $product = Database::fetch("SELECT * FROM products WHERE id = ? AND status = 'active'", [$productId]);
            if ($product) {
                $sub = (float)$product['price'] * (int)$item['quantity'];
                $total += $sub;
                $items[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'ff_uid' => $item['ff_uid'] ?? '',
                    'subtotal' => $sub
                ];
            }
        }

        $this->view('cart/index', [
            'items' => $items,
            'total' => $total,
            'appliedCoupon' => $_SESSION['applied_coupon'] ?? null
        ]);
    }

    public function add(Request $request): void {
        $productId = (int)$request->input('product_id');
        $quantity = max(1, (int)$request->input('quantity', 1));
        $ffUid = trim((string)$request->input('ff_uid', ''));

        $product = Database::fetch("SELECT * FROM products WHERE id = ? AND status = 'active'", [$productId]);
        if (!$product) {
            $this->json(['success' => false, 'message' => 'Product not found.'], 404);
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $_SESSION['cart'][$productId] = [
            'product_id' => $productId,
            'quantity' => $quantity,
            'ff_uid' => $ffUid,
        ];

        $this->json([
            'success' => true,
            'message' => "{$product['name']} added to cart!",
            'cart_count' => count($_SESSION['cart'])
        ]);
    }

    public function update(Request $request): void {
        $productId = (int)$request->input('product_id');
        $quantity = (int)$request->input('quantity');

        if (isset($_SESSION['cart'][$productId])) {
            if ($quantity <= 0) {
                unset($_SESSION['cart'][$productId]);
            } else {
                $_SESSION['cart'][$productId]['quantity'] = $quantity;
            }
        }

        $this->json(['success' => true, 'cart_count' => count($_SESSION['cart'] ?? [])]);
    }

    public function remove(Request $request): void {
        $productId = (int)$request->input('product_id');
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
        $this->json(['success' => true, 'cart_count' => count($_SESSION['cart'] ?? [])]);
    }

    public function applyCoupon(Request $request): void {
        $code = strtoupper(trim((string)$request->input('code')));
        $coupon = Database::fetch("SELECT * FROM coupons WHERE code = ? AND status = 'active'", [$code]);

        if (!$coupon) {
            $this->json(['success' => false, 'message' => 'Invalid or expired promo code.'], 404);
        }

        $_SESSION['applied_coupon'] = [
            'id' => $coupon['id'],
            'code' => $coupon['code'],
            'discount_type' => $coupon['discount_type'],
            'discount_value' => (float)$coupon['discount_value'],
            'min_order_amount' => (float)$coupon['min_order_amount'],
            'max_discount_amount' => $coupon['max_discount_amount'] ? (float)$coupon['max_discount_amount'] : null,
        ];

        $this->json(['success' => true, 'message' => "Coupon '{$code}' applied!"]);
    }
}
