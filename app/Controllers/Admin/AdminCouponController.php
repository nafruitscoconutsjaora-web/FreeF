<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;

class AdminCouponController extends Controller {
    public function index(Request $request): void {
        $coupons = Database::fetchAll("SELECT * FROM coupons ORDER BY id DESC");
        $this->view('admin/coupons/index', ['coupons' => $coupons]);
    }

    public function create(Request $request): void {
        $code = strtoupper(trim((string)$request->input('code')));
        $type = (string)$request->input('discount_type', 'percentage');
        $value = (float)$request->input('discount_value', 0);
        $minOrder = (float)$request->input('min_order_amount', 0);
        $maxDiscount = (float)$request->input('max_discount_amount', 0) ?: null;
        $usageLimit = (int)$request->input('usage_limit', 100);
        $expiresAt = $request->input('expires_at') ? date('Y-m-d H:i:s', strtotime($request->input('expires_at'))) : null;

        if (empty($code) || $value <= 0) {
            $this->json(['success' => false, 'message' => 'Valid code and discount value required.'], 422);
        }

        $existing = Database::fetch("SELECT id FROM coupons WHERE code = ?", [$code]);
        if ($existing) {
            $this->json(['success' => false, 'message' => 'Coupon code already exists.'], 422);
        }

        Database::insert(
            "INSERT INTO coupons (code, discount_type, discount_value, min_order_amount, max_discount_amount, usage_limit, expires_at, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, 'active')",
            [$code, $type, $value, $minOrder, $maxDiscount, $usageLimit, $expiresAt]
        );

        $this->json(['success' => true, 'message' => 'Coupon created successfully.']);
    }

    public function delete(Request $request, array $params): void {
        $couponId = (int)($params['id'] ?? 0);
        Database::query("DELETE FROM coupons WHERE id = ?", [$couponId]);
        $this->json(['success' => true, 'message' => 'Coupon deleted.']);
    }
}
