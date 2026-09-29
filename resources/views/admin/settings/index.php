<?php
$pageTitle = "Store Settings - Admin Control Center";
$activeAdminNav = 'settings';
require __DIR__ . '/../../layouts/admin_header.php';
?>

<div class="space-y-6 max-w-4xl mx-auto">
    <div>
        <h1 class="text-2xl font-black text-white">Store & System Settings</h1>
        <p class="text-xs text-gray-400 mt-1">Configure global platform options, Razorpay API credentials, and default currency rates.</p>
    </div>

    <?php if (!empty($success)): ?>
    <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold">
        <i class="fa-solid fa-circle-check mr-1.5"></i> <?= htmlspecialchars($success) ?>
    </div>
    <?php endif; ?>

    <form action="/admin/settings/save" method="POST" class="bg-[#0D111A] border border-gray-800 rounded-3xl p-6 sm:p-8 space-y-6">
        <!-- Brand & Store Details -->
        <div class="space-y-4">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-gray-800 pb-2">General Configuration</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Store Name</label>
                    <input type="text" name="store_name" value="<?= htmlspecialchars($settings['store_name'] ?? 'FF Panel Store') ?>"
                           class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Base Currency</label>
                    <input type="text" name="currency" value="<?= htmlspecialchars($settings['currency'] ?? 'INR') ?>" readonly
                           class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-gray-400 font-mono">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Default Profit Markup (%)</label>
                    <input type="number" step="0.5" name="default_markup_percentage" value="<?= htmlspecialchars($settings['default_markup_percentage'] ?? '15') ?>"
                           class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Automatic API Order Routing</label>
                    <select name="auto_api_delivery" class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white">
                        <option value="1" <?= ($settings['auto_api_delivery'] ?? '1') === '1' ? 'selected' : '' ?>>Enabled (Auto-send to Provider)</option>
                        <option value="0" <?= ($settings['auto_api_delivery'] ?? '1') === '0' ? 'selected' : '' ?>>Disabled (Manual Approval)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Payment Gateway Settings -->
        <div class="space-y-4 pt-4 border-t border-gray-800">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-gray-800 pb-2">Razorpay Gateway Integration</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Razorpay Key ID</label>
                    <input type="text" name="razorpay_key_id" value="<?= htmlspecialchars($settings['razorpay_key_id'] ?? '') ?>" placeholder="rzp_test_..."
                           class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Razorpay Key Secret</label>
                    <input type="password" name="razorpay_key_secret" value="<?= htmlspecialchars($settings['razorpay_key_secret'] ?? '') ?>" placeholder="••••••••••••"
                           class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 font-mono">
                </div>
            </div>
        </div>

        <!-- Contact & Support -->
        <div class="space-y-4 pt-4 border-t border-gray-800">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-gray-800 pb-2">Customer Support Channels</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Support Email</label>
                    <input type="email" name="support_email" value="<?= htmlspecialchars($settings['support_email'] ?? 'support@ffpanelstore.com') ?>"
                           class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">WhatsApp Helpline Number</label>
                    <input type="text" name="support_whatsapp" value="<?= htmlspecialchars($settings['support_whatsapp'] ?? '+91 9876543210') ?>"
                           class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500">
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-gray-800">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs shadow-lg transition">
                Save System Settings
            </button>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
