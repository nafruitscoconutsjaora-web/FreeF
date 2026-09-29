<?php
use App\Core\Router;
use App\Core\AuthMiddleware;
use App\Core\AdminMiddleware;
use App\Core\CsrfMiddleware;

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\CheckoutController;
use App\Controllers\WalletController;
use App\Controllers\OrderController;
use App\Controllers\SupportController;
use App\Controllers\ReferralController;
use App\Controllers\PaymentController;
use App\Controllers\UserController;
use App\Controllers\PageController;

use App\Controllers\Admin\AdminDashboardController;
use App\Controllers\Admin\AdminOrderController;
use App\Controllers\Admin\AdminProductController;
use App\Controllers\Admin\AdminProviderController;
use App\Controllers\Admin\AdminUserController;
use App\Controllers\Admin\AdminPaymentController;
use App\Controllers\Admin\AdminCouponController;
use App\Controllers\Admin\AdminSupportController;
use App\Controllers\Admin\AdminSettingsController;
use App\Controllers\Admin\AdminLogController;

// ==========================================
// 1. PUBLIC VISITOR FRONT STORE & STATIC
// ==========================================
Router::get('/', [HomeController::class, 'index']);
Router::get('/services', [ProductController::class, 'index']);
Router::get('/service/{slug}', [ProductController::class, 'detail']);
Router::post('/quick-recharge', [ProductController::class, 'quickRecharge']);

Router::get('/about', [PageController::class, 'about']);
Router::get('/contact', [PageController::class, 'contact']);
Router::get('/terms', [PageController::class, 'terms']);
Router::get('/privacy', [PageController::class, 'privacy']);
Router::get('/refund', [PageController::class, 'refund']);
Router::get('/maintenance', [PageController::class, 'maintenance']);

// Cart
Router::get('/cart', [CartController::class, 'index']);
Router::post('/cart/add', [CartController::class, 'add']);
Router::post('/cart/update', [CartController::class, 'update']);
Router::post('/cart/remove', [CartController::class, 'remove']);
Router::post('/cart/apply-coupon', [CartController::class, 'applyCoupon']);

// User Authentication
Router::get('/login', [AuthController::class, 'showLogin']);
Router::post('/login', [AuthController::class, 'login']);
Router::get('/register', [AuthController::class, 'showRegister']);
Router::post('/register', [AuthController::class, 'register']);
Router::get('/logout', [AuthController::class, 'logout']);

// ==========================================
// 2. USER PANEL (LOGGED-IN PROTECTED)
// ==========================================
Router::get('/dashboard', [UserController::class, 'dashboard'], [AuthMiddleware::class]);
Router::get('/checkout', [CheckoutController::class, 'index'], [AuthMiddleware::class]);
Router::post('/checkout/process', [CheckoutController::class, 'process'], [AuthMiddleware::class]);

Router::get('/wallet', [WalletController::class, 'index'], [AuthMiddleware::class]);
Router::post('/payment/razorpay/create-order', [PaymentController::class, 'createRazorpayOrder'], [AuthMiddleware::class]);
Router::post('/payment/razorpay/verify', [PaymentController::class, 'verifyPayment'], [AuthMiddleware::class]);

Router::get('/orders', [OrderController::class, 'index'], [AuthMiddleware::class]);
Router::get('/orders/{id}', [OrderController::class, 'detail'], [AuthMiddleware::class]);

Router::get('/referrals', [ReferralController::class, 'index'], [AuthMiddleware::class]);
Router::get('/coupons', [UserController::class, 'coupons'], [AuthMiddleware::class]);
Router::get('/notifications', [UserController::class, 'notifications'], [AuthMiddleware::class]);

Router::get('/support', [SupportController::class, 'index'], [AuthMiddleware::class]);
Router::post('/support/create', [SupportController::class, 'createTicket'], [AuthMiddleware::class]);
Router::get('/support/{id}', [SupportController::class, 'detail'], [AuthMiddleware::class]);
Router::post('/support/{id}/reply', [SupportController::class, 'reply'], [AuthMiddleware::class]);

Router::get('/profile', [UserController::class, 'profile'], [AuthMiddleware::class]);
Router::post('/profile/update', [UserController::class, 'updateProfile'], [AuthMiddleware::class]);
Router::post('/profile/password', [UserController::class, 'updatePassword'], [AuthMiddleware::class]);

// ==========================================
// 3. ADMIN PANEL (STRICT ADMIN MIDDLEWARE)
// ==========================================
Router::get('/admin/login', [AdminDashboardController::class, 'showLogin']);
Router::post('/admin/login', [AdminDashboardController::class, 'login']);
Router::get('/admin/logout', [AdminDashboardController::class, 'logout']);

Router::get('/admin', [AdminDashboardController::class, 'index'], [AdminMiddleware::class]);
Router::get('/admin/reports', [AdminDashboardController::class, 'reports'], [AdminMiddleware::class]);
Router::get('/admin/cron', [AdminDashboardController::class, 'cronRun'], [AdminMiddleware::class]);

// Admin Orders
Router::get('/admin/orders', [AdminOrderController::class, 'index'], [AdminMiddleware::class]);
Router::post('/admin/orders/{id}/status', [AdminOrderController::class, 'updateStatus'], [AdminMiddleware::class]);

// Admin Services & Categories
Router::get('/admin/products', [AdminProductController::class, 'index'], [AdminMiddleware::class]);
Router::get('/admin/services', [AdminProductController::class, 'index'], [AdminMiddleware::class]);
Router::post('/admin/products', [AdminProductController::class, 'create'], [AdminMiddleware::class]);
Router::post('/admin/products/{id}/delete', [AdminProductController::class, 'delete'], [AdminMiddleware::class]);

Router::get('/admin/categories', [AdminProductController::class, 'categories'], [AdminMiddleware::class]);
Router::post('/admin/categories/store', [AdminProductController::class, 'saveCategory'], [AdminMiddleware::class]);
Router::post('/admin/categories/{id}/delete', [AdminProductController::class, 'deleteCategory'], [AdminMiddleware::class]);

// Admin Providers
Router::get('/admin/providers', [AdminProviderController::class, 'index'], [AdminMiddleware::class]);
Router::post('/admin/providers', [AdminProviderController::class, 'create'], [AdminMiddleware::class]);
Router::post('/admin/providers/{id}/test', [AdminProviderController::class, 'testConnection'], [AdminMiddleware::class]);
Router::get('/admin/providers/{id}/services', [AdminProviderController::class, 'fetchServices'], [AdminMiddleware::class]);
Router::get('/admin/services/import', [AdminProviderController::class, 'importView'], [AdminMiddleware::class]);
Router::post('/admin/providers/import-service', [AdminProviderController::class, 'importService'], [AdminMiddleware::class]);

// Admin Users
Router::get('/admin/users', [AdminUserController::class, 'index'], [AdminMiddleware::class]);
Router::get('/admin/users/{id}', [AdminUserController::class, 'detail'], [AdminMiddleware::class]);
Router::post('/admin/users/{id}/balance', [AdminUserController::class, 'adjustBalance'], [AdminMiddleware::class]);
Router::post('/admin/users/{id}/status', [AdminUserController::class, 'updateStatus'], [AdminMiddleware::class]);

// Admin Payments & Wallet
Router::get('/admin/payments', [AdminPaymentController::class, 'index'], [AdminMiddleware::class]);
Router::get('/admin/wallet', [AdminPaymentController::class, 'walletLogs'], [AdminMiddleware::class]);

// Admin Coupons
Router::get('/admin/coupons', [AdminCouponController::class, 'index'], [AdminMiddleware::class]);
Router::post('/admin/coupons/store', [AdminCouponController::class, 'create'], [AdminMiddleware::class]);
Router::post('/admin/coupons/{id}/delete', [AdminCouponController::class, 'delete'], [AdminMiddleware::class]);

// Admin Support
Router::get('/admin/support', [AdminSupportController::class, 'index'], [AdminMiddleware::class]);
Router::get('/admin/support/{id}', [AdminSupportController::class, 'detail'], [AdminMiddleware::class]);
Router::post('/admin/support/{id}/reply', [AdminSupportController::class, 'reply'], [AdminMiddleware::class]);
Router::post('/admin/support/{id}/status', [AdminSupportController::class, 'updateStatus'], [AdminMiddleware::class]);

// Admin Settings & Logs
Router::get('/admin/settings', [AdminSettingsController::class, 'index'], [AdminMiddleware::class]);
Router::post('/admin/settings/save', [AdminSettingsController::class, 'save'], [AdminMiddleware::class]);
Router::get('/admin/logs', [AdminLogController::class, 'index'], [AdminMiddleware::class]);
