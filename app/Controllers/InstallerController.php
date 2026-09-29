<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use PDO;
use PDOException;
use Exception;

class InstallerController extends Controller {

    private string $lockFileConfig;
    private string $lockFileStorage;

    public function __construct() {
        $this->lockFileConfig = dirname(__DIR__, 2) . '/config/installed.lock';
        $this->lockFileStorage = dirname(__DIR__, 2) . '/storage/installed.lock';
    }

    /**
     * Check if application is already installed
     */
    private function isInstalled(): bool {
        return file_exists($this->lockFileConfig) || file_exists($this->lockFileStorage);
    }

    /**
     * Main installer landing / step coordinator
     */
    public function index(Request $request): void {
        if ($this->isInstalled()) {
            $this->view('installer/installed', [
                'pageTitle' => 'FF Panel Store - Already Installed',
            ]);
            return;
        }

        // Run system requirements audit
        $requirements = $this->checkRequirements();

        // Detect default host URL
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost:3000';
        $currentUrl = $protocol . '://' . $host;

        // Auto-generate secure 32-character application key
        $generatedKey = 'base64:' . base64_encode(random_bytes(24));

        $this->view('installer/index', [
            'pageTitle' => 'FF Panel Store - Web Installer',
            'requirements' => $requirements,
            'currentUrl' => $currentUrl,
            'generatedKey' => $generatedKey,
        ]);
    }

    /**
     * Step 1: System requirements audit
     */
    private function checkRequirements(): array {
        $phpVersion = PHP_VERSION;
        $phpOk = version_compare($phpVersion, '8.0.0', '>=');

        $extensions = [
            'pdo' => [
                'name' => 'PDO Extension',
                'status' => extension_loaded('pdo'),
                'required' => true,
            ],
            'pdo_mysql' => [
                'name' => 'PDO MySQL Driver (pdo_mysql)',
                'status' => extension_loaded('pdo_mysql'),
                'required' => true,
            ],
            'json' => [
                'name' => 'JSON Extension',
                'status' => extension_loaded('json'),
                'required' => true,
            ],
            'curl' => [
                'name' => 'cURL Extension (API Top-Ups)',
                'status' => extension_loaded('curl'),
                'required' => true,
            ],
            'openssl' => [
                'name' => 'OpenSSL Extension (Security & Hashing)',
                'status' => extension_loaded('openssl'),
                'required' => true,
            ],
            'mbstring' => [
                'name' => 'Multibyte String (mbstring)',
                'status' => extension_loaded('mbstring'),
                'required' => false,
            ],
        ];

        // Permissions check
        $rootDir = dirname(__DIR__, 2);
        $configDir = $rootDir . '/config';
        $storageDir = $rootDir . '/storage';

        $configWritable = is_writable($configDir) || is_writable($rootDir);
        $storageWritable = is_writable($storageDir) || is_writable($rootDir);

        $directories = [
            'config' => [
                'path' => 'config/',
                'status' => $configWritable,
                'required' => true,
            ],
            'storage' => [
                'path' => 'storage/',
                'status' => $storageWritable,
                'required' => true,
            ],
        ];

        $allPassed = $phpOk && $configWritable && $storageWritable;
        foreach ($extensions as $ext) {
            if ($ext['required'] && !$ext['status']) {
                $allPassed = false;
                break;
            }
        }

        return [
            'phpVersion' => $phpVersion,
            'phpOk' => $phpOk,
            'extensions' => $extensions,
            'directories' => $directories,
            'allPassed' => $allPassed,
        ];
    }

    /**
     * Step 2: Test Database Connection (AJAX / POST)
     */
    public function testDatabase(Request $request): void {
        if ($this->isInstalled()) {
            $this->json(['success' => false, 'message' => 'Application is already installed.'], 403);
            return;
        }

        $host = trim((string)$request->input('db_host', '127.0.0.1'));
        $port = trim((string)$request->input('db_port', '3306'));
        $database = trim((string)$request->input('db_name', 'ff_panel_store'));
        $username = trim((string)$request->input('db_user', 'root'));
        $password = (string)$request->input('db_pass', '');

        if (empty($host) || empty($database) || empty($username)) {
            $this->json(['success' => false, 'message' => 'Please provide host, database name, and username.'], 422);
            return;
        }

        try {
            // First attempt connecting directly to database
            $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 3,
            ]);

            $this->json([
                'success' => true,
                'message' => "Successfully connected to MySQL database '{$database}'!",
            ]);
        } catch (PDOException $e) {
            // Try connecting to server without db to see if database needs creation
            try {
                $serverDsn = "mysql:host={$host};port={$port};charset=utf8mb4";
                $serverPdo = new PDO($serverDsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_TIMEOUT => 3,
                ]);

                $this->json([
                    'success' => true,
                    'message' => "Connected to MySQL server! Database '{$database}' will be automatically created during installation.",
                    'needs_create' => true,
                ]);
            } catch (PDOException $e2) {
                $errorMsg = $e2->getMessage();
                // Never expose passwords in logs or output
                $cleanMsg = preg_replace('/password.*?;/i', '', $errorMsg);
                $this->json([
                    'success' => false,
                    'message' => 'Connection failed: ' . htmlspecialchars($cleanMsg),
                ], 400);
            }
        }
    }

    /**
     * Steps 3-7: Complete Installation Process
     */
    public function processInstallation(Request $request): void {
        if ($this->isInstalled()) {
            $this->json(['success' => false, 'message' => 'Application is already installed.'], 403);
            return;
        }

        // Inputs
        $dbHost = trim((string)$request->input('db_host', '127.0.0.1'));
        $dbPort = trim((string)$request->input('db_port', '3306'));
        $dbName = trim((string)$request->input('db_name', 'ff_panel_store'));
        $dbUser = trim((string)$request->input('db_user', 'root'));
        $dbPass = (string)$request->input('db_pass', '');

        $appName = trim((string)$request->input('app_name', 'FF Panel Store'));
        $appUrl = trim((string)$request->input('app_url', 'http://localhost:3000'));
        $appEnv = trim((string)$request->input('app_env', 'production'));
        $appKey = trim((string)$request->input('app_key', ''));
        $appTimezone = trim((string)$request->input('app_timezone', 'Asia/Kolkata'));
        $appCurrency = trim((string)$request->input('app_currency', 'INR'));

        $adminName = trim((string)$request->input('admin_name', 'Super Admin'));
        $adminEmail = trim((string)$request->input('admin_email', ''));
        $adminPass = (string)$request->input('admin_password', '');
        $adminPassConfirm = (string)$request->input('admin_password_confirmation', '');

        $razorpayKeyId = trim((string)$request->input('razorpay_key_id', ''));
        $razorpayKeySecret = trim((string)$request->input('razorpay_key_secret', ''));

        // Validations
        if (empty($dbHost) || empty($dbName) || empty($dbUser)) {
            $this->json(['success' => false, 'message' => 'Database configuration is incomplete.'], 422);
            return;
        }

        if (empty($adminEmail) || !filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
            $this->json(['success' => false, 'message' => 'Please provide a valid administrator email.'], 422);
            return;
        }

        if (strlen($adminPass) < 8) {
            $this->json(['success' => false, 'message' => 'Admin password must be at least 8 characters long.'], 422);
            return;
        }

        if ($adminPass !== $adminPassConfirm) {
            $this->json(['success' => false, 'message' => 'Admin passwords do not match.'], 422);
            return;
        }

        if (empty($appKey)) {
            $appKey = 'base64:' . base64_encode(random_bytes(24));
        }

        $rootDir = dirname(__DIR__, 2);

        // 1. Establish PDO Connection & Ensure Database Exists
        try {
            $serverDsn = "mysql:host={$dbHost};port={$dbPort};charset=utf8mb4";
            $pdo = new PDO($serverDsn, $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            $pdo->exec("USE `{$dbName}`;");
        } catch (PDOException $e) {
            $this->json(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()], 400);
            return;
        }

        // 2. Run Database Schema (DDL)
        $schemaFile = $rootDir . '/database/schema.sql';
        if (!file_exists($schemaFile)) {
            $this->json(['success' => false, 'message' => 'Schema file database/schema.sql not found.'], 500);
            return;
        }

        $schemaSql = file_get_contents($schemaFile);
        if ($schemaSql === false) {
            $this->json(['success' => false, 'message' => 'Could not read database/schema.sql.'], 500);
            return;
        }

        try {
            // Execute statements
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
            $pdo->exec($schemaSql);
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
        } catch (PDOException $e) {
            $this->json(['success' => false, 'message' => 'Schema migration error: ' . $e->getMessage()], 500);
            return;
        }

        // 3. Run Genuine Initial Seed (Settings, Categories, Hero Banner - NO fake users/orders)
        $seedsFile = $rootDir . '/database/seeds.sql';
        if (file_exists($seedsFile)) {
            $seedsSql = file_get_contents($seedsFile);
            if ($seedsSql) {
                try {
                    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
                    $pdo->exec($seedsSql);
                    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
                } catch (PDOException $e) {
                    // Non-fatal if seeds partially exist
                    error_log("Seed warning during installation: " . $e->getMessage());
                }
            }
        }

        // 4. Update Settings with Custom Application Settings
        try {
            $updateSetting = $pdo->prepare("INSERT INTO `settings` (`setting_key`, `setting_value`, `description`) 
                VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`)");

            $updateSetting->execute(['store_name', $appName, 'Brand store name']);
            $updateSetting->execute(['store_currency', $appCurrency, 'Default store currency']);
            if (!empty($razorpayKeyId)) {
                $updateSetting->execute(['razorpay_key_id', $razorpayKeyId, 'Razorpay API Key ID']);
            }
            if (!empty($razorpayKeySecret)) {
                $updateSetting->execute(['razorpay_key_secret', $razorpayKeySecret, 'Razorpay API Key Secret']);
            }
        } catch (PDOException $e) {
            error_log("Settings update warning: " . $e->getMessage());
        }

        // 5. Create Administrator Account
        try {
            $hashedPassword = password_hash($adminPass, PASSWORD_BCRYPT);
            
            // Check if admin email already exists
            $checkStmt = $pdo->prepare("SELECT id FROM admins WHERE email = ?");
            $checkStmt->execute([$adminEmail]);
            $existingAdmin = $checkStmt->fetch();

            if ($existingAdmin) {
                $updateAdmin = $pdo->prepare("UPDATE admins SET name = ?, password = ?, role = 'super_admin', status = 'active' WHERE id = ?");
                $updateAdmin->execute([$adminName, $hashedPassword, $existingAdmin['id']]);
            } else {
                $insertAdmin = $pdo->prepare("INSERT INTO admins (name, email, password, role, status) VALUES (?, ?, ?, 'super_admin', 'active')");
                $insertAdmin->execute([$adminName, $adminEmail, $hashedPassword]);
            }
        } catch (PDOException $e) {
            $this->json(['success' => false, 'message' => 'Failed to create administrator account: ' . $e->getMessage()], 500);
            return;
        }

        // 6. Write .env configuration file
        $envContent = "# ============================================================\n"
            . "# FF PANEL STORE - PRODUCTION ENVIRONMENT CONFIGURATION\n"
            . "# Automatically generated by FF Panel Store Web Installer\n"
            . "# Generated on: " . date('Y-m-d H:i:s') . "\n"
            . "# ============================================================\n\n"
            . "APP_NAME=\"{$appName}\"\n"
            . "APP_ENV=\"{$appEnv}\"\n"
            . "APP_KEY=\"{$appKey}\"\n"
            . "APP_URL=\"{$appUrl}\"\n"
            . "APP_TIMEZONE=\"{$appTimezone}\"\n\n"
            . "# DATABASE CONFIGURATION\n"
            . "DB_HOST=\"{$dbHost}\"\n"
            . "DB_PORT=\"{$dbPort}\"\n"
            . "DB_DATABASE=\"{$dbName}\"\n"
            . "DB_USERNAME=\"{$dbUser}\"\n"
            . "DB_PASSWORD=\"{$dbPass}\"\n\n"
            . "# PAYMENT GATEWAY (Razorpay Server-Side Only)\n"
            . "RAZORPAY_KEY_ID=\"{$razorpayKeyId}\"\n"
            . "RAZORPAY_KEY_SECRET=\"{$razorpayKeySecret}\"\n"
            . "RAZORPAY_WEBHOOK_SECRET=\"\"\n\n"
            . "# STORE SETTINGS\n"
            . "STORE_CURRENCY=\"{$appCurrency}\"\n"
            . "STORE_CURRENCY_SYMBOL=\"" . ($appCurrency === 'INR' ? '₹' : '$') . "\"\n"
            . "USD_INR_EXCHANGE_RATE=\"85.50\"\n"
            . "DEFAULT_MARKUP_PERCENT=\"15.00\"\n";

        @file_put_contents($rootDir . '/.env', $envContent);

        // 7. Write Lock Files to permanently seal the installer
        $lockPayload = json_encode([
            'installed_at' => date('c'),
            'app_name' => $appName,
            'app_url' => $appUrl,
            'admin_email' => $adminEmail,
            'db_name' => $dbName,
            'db_host' => $dbHost,
            'version' => '1.0.0',
        ], JSON_PRETTY_PRINT);

        @file_put_contents($this->lockFileConfig, $lockPayload);
        @file_put_contents($this->lockFileStorage, $lockPayload);

        $this->json([
            'success' => true,
            'message' => 'Installation completed successfully.',
            'redirect' => '/install/finish',
            'website_url' => '/',
            'admin_url' => '/admin/login',
        ]);
    }

    /**
     * Finish Screen
     */
    public function finish(Request $request): void {
        $this->view('installer/finish', [
            'pageTitle' => 'Installation Completed - FF Panel Store',
            'websiteUrl' => '/',
            'adminUrl' => '/admin/login',
        ]);
    }
}
