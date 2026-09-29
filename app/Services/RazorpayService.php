<?php
namespace App\Services;

use Exception;

class RazorpayService {
    private string $keyId;
    private string $keySecret;
    private string $baseUrl = 'https://api.razorpay.com/v1';

    public function __construct() {
        $config = require __DIR__ . '/../../config/razorpay.php';
        $this->keyId = $config['key_id'];
        $this->keySecret = $config['key_secret'];
    }

    public function isConfigured(): bool {
        return !empty($this->keyId) && !empty($this->keySecret) && $this->keyId !== 'rzp_test_placeholder_key';
    }

    public function getKeyId(): string {
        return $this->keyId;
    }

    /**
     * Create Razorpay Order
     * @param float $amount Amount in INR
     * @param string $receipt Unique receipt identifier
     * @param array $notes Metadata
     * @return array
     */
    public function createOrder(float $amount, string $receipt, array $notes = []): array {
        if (!$this->isConfigured()) {
            throw new Exception("Razorpay credentials are not configured in system settings.");
        }

        $payload = [
            'amount' => (int)round($amount * 100), // Amount in paise
            'currency' => 'INR',
            'receipt' => $receipt,
            'payment_capture' => 1,
            'notes' => $notes
        ];

        return $this->sendRequest('/orders', 'POST', $payload);
    }

    /**
     * Verify payment signature cryptographically
     */
    public function verifySignature(string $razorpayOrderId, string $razorpayPaymentId, string $razorpaySignature): bool {
        if (empty($this->keySecret)) {
            return false;
        }
        $expectedSignature = hash_hmac('sha256', $razorpayOrderId . '|' . $razorpayPaymentId, $this->keySecret);
        return hash_equals($expectedSignature, $razorpaySignature);
    }

    /**
     * Fetch payment details from Razorpay
     */
    public function fetchPayment(string $paymentId): array {
        return $this->sendRequest("/payments/{$paymentId}", 'GET');
    }

    private function sendRequest(string $endpoint, string $method = 'GET', array $data = []): array {
        $ch = curl_init();
        $url = $this->baseUrl . $endpoint;

        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($this->keyId . ':' . $this->keySecret)
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("Razorpay cURL error: " . $error);
        }

        $result = json_decode($response, true);
        if ($httpCode >= 400) {
            $msg = $result['error']['description'] ?? "Razorpay API error (Code: {$httpCode})";
            throw new Exception($msg);
        }

        return $result ?: [];
    }
}
