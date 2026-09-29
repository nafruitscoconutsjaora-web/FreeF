<?php
namespace App\Services;

use App\Core\Database;
use Exception;

class OrderService {
    private WalletService $walletService;
    private ProviderApiService $providerService;

    public function __construct() {
        $this->walletService = new WalletService();
        $this->providerService = new ProviderApiService();
    }

    /**
     * Create and process order
     */
    public function createOrder(
        int $userId,
        int $productId,
        int $quantity,
        string $ffUid,
        string $paymentMethod = 'wallet',
        ?string $couponCode = null,
        ?string $paymentId = null
    ): array {
        // Fetch product
        $product = Database::fetch("SELECT * FROM products WHERE id = ? AND status = 'active'", [$productId]);
        if (!$product) {
            throw new Exception("Product is currently unavailable or inactive.");
        }

        if ($product['stock_status'] !== 'in_stock') {
            throw new Exception("This product is currently out of stock or in maintenance.");
        }

        if ($quantity <= 0) {
            throw new Exception("Invalid order quantity.");
        }

        $unitPrice = (float)$product['price'];
        $subtotal = $unitPrice * $quantity;
        $discountAmount = 0.00;
        $couponId = null;

        // Apply coupon if provided
        if (!empty($couponCode)) {
            $coupon = Database::fetch("SELECT * FROM coupons WHERE code = ? AND status = 'active'", [$couponCode]);
            if ($coupon) {
                if ($subtotal >= (float)$coupon['min_order_amount']) {
                    if ($coupon['discount_type'] === 'percentage') {
                        $discountAmount = ($subtotal * (float)$coupon['discount_value']) / 100;
                        if (!empty($coupon['max_discount_amount']) && $discountAmount > (float)$coupon['max_discount_amount']) {
                            $discountAmount = (float)$coupon['max_discount_amount'];
                        }
                    } else {
                        $discountAmount = (float)$coupon['discount_value'];
                    }
                    $discountAmount = min($discountAmount, $subtotal);
                    $couponId = $coupon['id'];
                }
            }
        }

        $totalAmount = max(0.00, $subtotal - $discountAmount);
        $orderNumber = 'FF-' . strtoupper(substr(uniqid(), -6)) . rand(100, 999);

        // Check payment method
        if ($paymentMethod === 'wallet') {
            // Debit wallet with transaction locking
            $this->walletService->debit(
                $userId,
                $totalAmount,
                'order_payment',
                $orderNumber,
                "Order #{$orderNumber}: {$product['name']} (UID: {$ffUid})"
            );
            $paymentStatus = 'paid';
        } else {
            $paymentStatus = 'pending';
        }

        // Check if service has API mapping
        $mapping = Database::fetch("SELECT * FROM service_mappings WHERE product_id = ? AND status = 'active'", [$productId]);
        $serviceMode = $mapping ? 'api' : 'manual';
        $providerId = $mapping ? $mapping['provider_id'] : null;
        $providerServiceId = $mapping ? $mapping['provider_service_id'] : null;
        $providerCost = $mapping ? $mapping['provider_rate'] : null;

        Database::beginTransaction();
        try {
            $orderId = Database::insert(
                "INSERT INTO orders (order_number, user_id, product_id, quantity, unit_price, subtotal, discount_amount, coupon_id, total_amount, payment_method, payment_status, payment_id, service_mode, provider_id, provider_service_id, provider_cost, customer_ff_uid, status)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')",
                [
                    $orderNumber,
                    $userId,
                    $productId,
                    $quantity,
                    $unitPrice,
                    $subtotal,
                    $discountAmount,
                    $couponId,
                    $totalAmount,
                    $paymentMethod,
                    $paymentStatus,
                    $paymentId,
                    $serviceMode,
                    $providerId,
                    $providerServiceId,
                    $providerCost,
                    $ffUid
                ]
            );

            // Insert order item
            Database::insert(
                "INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, total_price, ff_uid)
                 VALUES (?, ?, ?, ?, ?, ?, ?)",
                [$orderId, $productId, $product['name'], $quantity, $unitPrice, $totalAmount, $ffUid]
            );

            // Insert initial status history
            Database::insert(
                "INSERT INTO order_status_history (order_id, previous_status, new_status, changed_by, notes)
                 VALUES (?, NULL, 'pending', 'user', 'Order placed successfully')",
                [$orderId]
            );

            // Record coupon usage
            if ($couponId) {
                Database::insert(
                    "INSERT INTO coupon_usage (coupon_id, user_id, order_id, discount_given) VALUES (?, ?, ?, ?)",
                    [$couponId, $userId, $orderId, $discountAmount]
                );
                Database::query("UPDATE coupons SET used_count = used_count + 1 WHERE id = ?", [$couponId]);
            }

            // Create notification for customer
            Database::insert(
                "INSERT INTO notifications (user_id, title, message, type, action_url)
                 VALUES (?, ?, ?, 'order', ?)",
                [
                    $userId,
                    "Order Placed #{$orderNumber}",
                    "Your order for {$product['name']} has been submitted.",
                    "/orders/{$orderNumber}"
                ]
            );

            Database::commit();

            // If API mode and paid, trigger provider order
            if ($serviceMode === 'api' && $paymentStatus === 'paid' && $providerId && $providerServiceId) {
                try {
                    $providerResponse = $this->providerService->placeOrder($providerId, $providerServiceId, $ffUid, $quantity);
                    if (!empty($providerResponse['order'])) {
                        Database::query(
                            "UPDATE orders SET provider_order_id = ?, status = 'processing' WHERE id = ?",
                            [$providerResponse['order'], $orderId]
                        );
                        Database::insert(
                            "INSERT INTO order_status_history (order_id, previous_status, new_status, changed_by, notes)
                             VALUES (?, 'pending', 'processing', 'provider_api', 'Order submitted to provider API')",
                            [$orderId]
                        );
                    }
                } catch (Exception $e) {
                    // Order remains pending for admin inspection, do not crash order record
                    error_log("Failed to dispatch order #{$orderNumber} to provider: " . $e->getMessage());
                }
            }

            return [
                'order_id' => $orderId,
                'order_number' => $orderNumber,
                'total_amount' => $totalAmount,
                'status' => 'pending'
            ];
        } catch (Exception $e) {
            Database::rollBack();
            throw $e;
        }
    }
}
