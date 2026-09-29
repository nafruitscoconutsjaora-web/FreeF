<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use Exception;

class AdminProductController extends Controller {
    public function index(Request $request): void {
        $products = Database::fetchAll(
            "SELECT p.*, c.name as category_name 
             FROM products p 
             JOIN categories c ON p.category_id = c.id 
             ORDER BY p.sort_order ASC, p.id DESC"
        );
        $categories = Database::fetchAll("SELECT * FROM categories ORDER BY sort_order ASC");

        $this->view('admin/products/index', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function create(Request $request): void {
        $name = trim((string)$request->input('name'));
        $categoryId = (int)$request->input('category_id');
        $price = (float)$request->input('price');
        $originalPrice = (float)$request->input('original_price', 0) ?: null;
        $shortDesc = trim((string)$request->input('short_description', ''));
        $badge = trim((string)$request->input('badge', ''));
        $serviceMode = (string)$request->input('service_mode', 'manual');
        $isPopular = (int)$request->input('is_popular', 0);
        $isQuick = (int)$request->input('is_quick_recharge', 0);

        if (empty($name) || $categoryId <= 0 || $price <= 0) {
            $this->json(['success' => false, 'message' => 'Valid name, category, and price are required.'], 422);
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-')) . '-' . rand(10, 99);

        try {
            $id = Database::insert(
                "INSERT INTO products (category_id, name, slug, short_description, price, original_price, badge, service_mode, is_popular, is_quick_recharge, status)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')",
                [$categoryId, $name, $slug, $shortDesc, $price, $originalPrice, $badge, $serviceMode, $isPopular, $isQuick]
            );

            $this->json(['success' => true, 'message' => 'Product created successfully', 'product_id' => $id]);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request, array $params): void {
        $id = (int)($params['id'] ?? 0);
        Database::query("DELETE FROM products WHERE id = ?", [$id]);
        $this->json(['success' => true, 'message' => 'Product deleted successfully']);
    }

    public function categories(Request $request): void {
        $categories = Database::fetchAll(
            "SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id) as product_count 
             FROM categories c 
             ORDER BY c.sort_order ASC, c.id DESC"
        );

        $this->view('admin/categories/index', ['categories' => $categories]);
    }

    public function saveCategory(Request $request): void {
        $name = trim((string)$request->input('name'));
        $icon = trim((string)$request->input('icon', 'fa-solid fa-gem'));
        $sortOrder = (int)$request->input('sort_order', 0);

        if (empty($name)) {
            $this->json(['success' => false, 'message' => 'Category name is required.'], 422);
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

        Database::insert(
            "INSERT INTO categories (name, slug, icon, sort_order, status) VALUES (?, ?, ?, ?, 'active')",
            [$name, $slug, $icon, $sortOrder]
        );

        $this->json(['success' => true, 'message' => 'Category added successfully.']);
    }

    public function deleteCategory(Request $request, array $params): void {
        $id = (int)($params['id'] ?? 0);
        Database::query("DELETE FROM categories WHERE id = ?", [$id]);
        $this->json(['success' => true, 'message' => 'Category deleted.']);
    }
}
