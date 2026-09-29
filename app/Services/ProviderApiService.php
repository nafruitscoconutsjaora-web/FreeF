<?php
namespace App\Services;

use App\Core\Database;
use Exception;

class ProviderApiService {
    /**
     * Send generic API request to third party provider
     */
    public function callApi(int $providerId, string $action, array $params = []): array {
        $provider = Database::fetch("SELECT * FROM providers WHERE id = ? AND status = 'active'", [$providerId]);
        if (!$provider) {
            throw new Exception("Active provider with ID [{$providerId}] not found.");
        }

        $apiUrl = $provider['api_url'];
        $apiKey = $provider['api_key'];

        $payload = array_merge([
            'key' => $apiKey,
            'action' => $action,
        ], $params);

        $startTime = microtime(true);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 35);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $response = curl_exec($ch);
        $executionTimeMs = (int)((microtime(true) - $startTime) * 1000);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        $status = 'success';
        if ($curlError || $httpCode >= 400) {
            $status = 'failed';
        }

        // Securely log API transaction in database
        Database::insert(
            "INSERT INTO api_logs (provider_id, endpoint, request_payload, response_code, response_body, execution_time_ms, status) 
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            [
                $providerId,
                $apiUrl . '?' . $action,
                json_encode(['action' => $action, 'params' => $params]),
                $httpCode,
                $response ?: $curlError,
                $executionTimeMs,
                $status
            ]
        );

        if ($curlError) {
            throw new Exception("Provider API network failure: {$curlError}");
        }

        $decoded = json_decode($response, true);
        if ($decoded === null) {
            throw new Exception("Provider returned an unparseable response: " . substr($response, 0, 150));
        }

        return $decoded;
    }

    /**
     * Check Provider Account Balance
     */
    public function getBalance(int $providerId): float {
        $res = $this->callApi($providerId, 'balance');
        $balance = (float)($res['balance'] ?? 0.0);
        Database::query("UPDATE providers SET balance = ?, last_balance_check = NOW() WHERE id = ?", [$balance, $providerId]);
        return $balance;
    }

    /**
     * Fetch Services Catalog from Provider
     */
    public function fetchServices(int $providerId): array {
        $res = $this->callApi($providerId, 'services');
        if (!is_array($res)) {
            throw new Exception("Provider returned invalid services list.");
        }
        return $res;
    }

    /**
     * Place order on external provider API
     */
    public function placeOrder(int $providerId, string $providerServiceId, string $linkOrUid, int $quantity = 1, array $extra = []): array {
        $params = array_merge([
            'service' => $providerServiceId,
            'link' => $linkOrUid,
            'quantity' => $quantity,
        ], $extra);

        $res = $this->callApi($providerId, 'add', $params);

        if (isset($res['error'])) {
            throw new Exception("Provider order error: " . $res['error']);
        }

        if (empty($res['order'])) {
            throw new Exception("Provider did not return an order ID.");
        }

        return $res; // ['order' => '12345']
    }

    /**
     * Check order status on provider
     */
    public function getOrderStatus(int $providerId, string $providerOrderId): array {
        $res = $this->callApi($providerId, 'status', ['order' => $providerOrderId]);
        return $res; // ['status' => 'Completed', 'charge' => '0.25', 'remains' => 0]
    }
}
