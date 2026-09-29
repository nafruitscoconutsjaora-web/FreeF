<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;

class AdminSettingsController extends Controller {
    public function index(Request $request): void {
        $settings = Database::fetchAll("SELECT * FROM system_settings");
        $config = [];
        foreach ($settings as $s) {
            $config[$s['setting_key']] = $s['setting_value'];
        }

        $this->view('admin/settings/index', [
            'settings' => $config,
            'success' => $_SESSION['settings_success'] ?? null
        ]);
        unset($_SESSION['settings_success']);
    }

    public function save(Request $request): void {
        $inputs = [
            'store_name' => $request->input('store_name', 'FF Panel Store'),
            'currency' => $request->input('currency', 'INR'),
            'maintenance_mode' => $request->input('maintenance_mode', '0'),
            'auto_api_delivery' => $request->input('auto_api_delivery', '1'),
            'razorpay_key_id' => $request->input('razorpay_key_id', ''),
            'razorpay_key_secret' => $request->input('razorpay_key_secret', ''),
            'default_markup_percentage' => $request->input('default_markup_percentage', '15'),
            'support_email' => $request->input('support_email', 'support@ffpanelstore.com'),
            'support_whatsapp' => $request->input('support_whatsapp', ''),
        ];

        foreach ($inputs as $key => $val) {
            Database::query(
                "INSERT INTO system_settings (setting_key, setting_value, updated_at) 
                 VALUES (?, ?, NOW()) 
                 ON DUPLICATE KEY UPDATE setting_value = ?, updated_at = NOW()",
                [$key, $val, $val]
            );
        }

        $_SESSION['settings_success'] = "Settings saved successfully.";
        $this->redirect('/admin/settings');
    }
}
