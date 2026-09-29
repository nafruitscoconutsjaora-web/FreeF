<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Services\CurrencyService;
use App\Services\ProviderApiService;
use Exception;

class AdminProviderController extends Controller {
    private ProviderApiService $apiService;
    private CurrencyService $currencyService;

    public function __construct() {
        $this->apiService = new ProviderApiService();
        $this->currencyService = new CurrencyService();
    }

    public function index(Request $request): void {
        $providers = Database::fetchAll("SELECT * FROM providers ORDER BY id DESC");
        $this->view('admin/providers/index', ['providers' => $providers]);
    }

    public function create(Request $request): void {
        $name = trim((string)$request->input('name'));
        $apiUrl = trim((string)$request->input('api_url'));
        $apiKey = trim((string)$request->input('api_key'));
        $currency = strtoupper(trim((string)$request->input('currency', 'USD')));

        if (empty($name) || empty($apiUrl) || empty($apiKey)) {
            $this->json(['success' => false, 'message' => 'Provider Name, API URL, and API Key are required.'], 422);
        }

        try {
            $id = Database::insert(
                "INSERT INTO providers (name, api_url, api_key, currency, status) VALUES (?, ?, ?, ?, 'active')",
                [$name, $apiUrl, $apiKey, $currency]
            );

            $this->json(['success' => true, 'message' => 'Provider added successfully.', 'provider_id' => $id]);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function testConnection(Request $request, array $params): void {
        $providerId = (int)($params['id'] ?? 0);
        try {
            $balance = $this->apiService->getBalance($providerId);
            $this->json(['success' => true, 'message' => "Connection successful! Balance: {$balance}"]);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => "Connection failed: " . $e->getMessage()], 400);
        }
    }

    public function fetchServices(Request $request, array $params): void {
        $providerId = (int)($params['id'] ?? 0);
        try {
            $services = $this->apiService->fetchServices($providerId);
            $this->json(['success' => true, 'services' => $services]);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function importService(Request $request): void {
        $providerId = (int)$request->input('provider_id');
        $providerServiceId = (string)$request->input('provider_service_id');
        $name = trim((string)$request->input('name'));
        $categoryId = (int)$request->input('category_id');
        $providerRate = (float)$request->input('provider_rate'); // e.g. 0.25 USD
        $markupPercent = (float)$request->input('markup_percent', 15.0);
        $exchangeRate = (float)$request->input('exchange_rate', 85.50);

        if ($providerId <= 0 || empty($providerServiceId) || empty($name) || $categoryId <= 0) {
            $this->json(['success' => false, 'message' => 'All fields are required.'], 422);
        }

        $customerPrice = $this->currencyService->calculateCustomerPrice(
            $providerRate,
            'USD',
            $exchangeRate,
            $markupPercent
        );

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-')) . '-' . rand(100, 999);

        Database::beginTransaction();
        try {
            // Create product in store catalog
            $productId = Database::insert(
                "INSERT INTO products (category_id, name, slug, short_description, price, service_mode, status)
                 VALUES (?, ?, ?, 'Imported Free Fire Service', ?, 'api', 'active')",
                [$categoryId, $name, $slug, $customerPrice]
            );

            // Create service mapping
            Database::insert(
                "INSERT INTO service_mappings (product_id, provider_id, provider_service_id, provider_rate, provider_currency, exchange_rate, markup_percentage, status)
                 VALUES (?, ?, ?, ?, 'USD', ?, ?, 'active')",
                [$productId, $providerId, $providerServiceId, $providerRate, $exchangeRate, $markupPercent]
            );

            // Log imported service
            Database::insert(
                "INSERT INTO imported_services (provider_id, provider_service_id, product_id, import_status)
                 VALUES (?, ?, ?, 'imported')",
                [$providerId, $providerServiceId, $productId]
            );

            Database::commit();

            $this->json([
                'success' => true,
                'message' => "Service '{$name}' imported! Customer Price: ₹{$customerPrice}",
                'product_id' => $productId
            ]);
        } catch (Exception $e) {
            Database::rollBack();
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function importView(Request $request): void {
        $providers = Database::fetchAll("SELECT * FROM providers WHERE status = 'active' ORDER BY id DESC");
        $categories = Database::fetchAll("SELECT * FROM categories ORDER BY sort_order ASC");

        $this->view('admin/providers/import', [
            'providers' => $providers,
            'categories' => $categories
        ]);
    }
}
