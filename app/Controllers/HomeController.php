<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;

class HomeController extends Controller {
    public function index(Request $request): void {
        // Fetch active hero banners for public storefront
        $banners = Database::fetchAll("SELECT * FROM banners WHERE status = 'active' ORDER BY sort_order ASC LIMIT 5");

        // Fetch active categories
        $categories = Database::fetchAll("SELECT * FROM categories WHERE status = 'active' ORDER BY sort_order ASC");

        // Fetch active store products
        $products = Database::fetchAll(
            "SELECT p.*, c.name as category_name, c.slug as category_slug 
             FROM products p 
             JOIN categories c ON p.category_id = c.id 
             WHERE p.status = 'active' 
             ORDER BY p.is_popular DESC, p.sort_order ASC 
             LIMIT 24"
        );

        // Render pure public storefront
        $this->view('home', [
            'banners' => $banners,
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}
