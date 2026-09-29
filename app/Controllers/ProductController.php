<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Services\OrderService;
use Exception;

class ProductController extends Controller {
    public function index(Request $request): void {
        $categorySlug = $request->input('category');
        $search = trim((string)$request->input('q'));

        $query = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                  FROM products p 
                  JOIN categories c ON p.category_id = c.id 
                  WHERE p.status = 'active'";
        $params = [];

        if (!empty($categorySlug)) {
            $query .= " AND c.slug = ?";
            $params[] = $categorySlug;
        }

        if (!empty($search)) {
            $query .= " AND (p.name LIKE ? OR p.short_description LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $query .= " ORDER BY p.sort_order ASC";
        $products = Database::fetchAll($query, $params);
        $categories = Database::fetchAll("SELECT * FROM categories WHERE status = 'active' ORDER BY sort_order ASC");

        $this->view('products/index', [
            'products' => $products,
            'categories' => $categories,
            'activeCategory' => $categorySlug,
            'searchQuery' => $search,
        ]);
    }

    public function detail(Request $request, array $params): void {
        $slug = $params['slug'] ?? '';
        $product = Database::fetch(
            "SELECT p.*, c.name as category_name, c.slug as category_slug 
             FROM products p 
             JOIN categories c ON p.category_id = c.id 
             WHERE p.slug = ? AND p.status = 'active'",
            [$slug]
        );

        if (!$product) {
            $this->redirect('/services');
        }

        $relatedProducts = Database::fetchAll(
            "SELECT * FROM products WHERE category_id = ? AND id != ? AND status = 'active' LIMIT 4",
            [$product['category_id'], $product['id']]
        );

        $this->view('products/detail', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }

    /**
     * Process Quick Recharge submission
     */
    public function quickRecharge(Request $request): void {
        if (empty($_SESSION['user_id'])) {
            $this->json(['success' => false, 'message' => 'Please login or register to complete recharge.'], 401);
        }

        $uid = trim((string)$request->input('uid'));
        $productId = (int)$request->input('product_id');

        if (empty($uid)) {
            $this->json(['success' => false, 'message' => 'Please enter a valid Free Fire Player UID.'], 422);
        }

        if ($productId <= 0) {
            $this->json(['success' => false, 'message' => 'Please select a recharge pack.'], 422);
        }

        try {
            $orderService = new OrderService();
            $result = $orderService->createOrder(
                $_SESSION['user_id'],
                $productId,
                1,
                $uid,
                'wallet'
            );

            $this->json([
                'success' => true,
                'message' => 'Recharge order submitted successfully!',
                'order' => $result
            ]);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
