<?php
/**
 * FF Panel Store - Application Entry Point
 */

declare(strict_types=1);

session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
]);

// Generate CSRF token if missing
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Load .env variables if present
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            putenv("{$name}={$value}");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Simple autoloader for App namespace
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// Load helper functions
require_once __DIR__ . '/../app/helpers.php';

use App\Core\Request;
use App\Core\Router;
use App\Core\Response;

// Load route definitions
require_once __DIR__ . '/../routes/web.php';

// Dispatch request with robust error handling
try {
    $request = new Request();
    Router::dispatch($request);
} catch (\Throwable $e) {
    error_log("Application Exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());
    if (getenv('APP_ENV') === 'local' || getenv('APP_DEBUG') === 'true') {
        echo "<!DOCTYPE html><html><head><title>System Error</title><link rel='stylesheet' href='/assets/css/app.css'><link rel='stylesheet' href='/css/app.css'></head><body class='bg-[#080B11] text-gray-100 p-8 font-sans'><div class='max-w-3xl mx-auto bg-[#0B0E14] border border-rose-500/40 p-6 rounded-2xl space-y-4'><h1 class='text-xl font-bold text-rose-500'>Application Error</h1><p class='text-sm text-gray-300 font-mono'>" . htmlspecialchars($e->getMessage()) . "</p><pre class='text-xs text-gray-500 overflow-x-auto p-4 bg-black/60 rounded-xl'>" . htmlspecialchars($e->getTraceAsString()) . "</pre></div></body></html>";
    } else {
        Response::abort(500, "A temporary server issue occurred. Please try again shortly.");
    }
}
