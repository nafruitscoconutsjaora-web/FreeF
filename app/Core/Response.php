<?php
namespace App\Core;

class Response {
    public static function json(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function redirect(string $url, int $statusCode = 302): void {
        http_response_code($statusCode);
        header("Location: {$url}");
        exit;
    }

    public static function abort(int $code, string $message = ''): void {
        http_response_code($code);
        $file = __DIR__ . "/../../resources/views/errors/{$code}.php";
        if (file_exists($file)) {
            require $file;
        } else {
            echo "<h1>Error {$code}</h1><p>" . htmlspecialchars($message) . "</p>";
        }
        exit;
    }
}
