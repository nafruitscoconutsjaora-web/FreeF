<?php
$pageTitle = "Coupons & Discounts - Admin Control Center";
$activeAdminNav = 'coupons';
require __DIR__ . '/../../layouts/admin_header.php';
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white">Coupons & Promotional Codes</h1>
            <p class="text-xs text-gray-400 mt-1">Create discount codes for store promotions and diamond recharge campaigns.</p>
        </div>

        <button type="button" id="openCouponModalBtn"
                class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs flex items-center gap-2 shadow transition">
            <i class="fa-solid fa-plus"></i> Create Coupon
        </button>
    </div>

    <!-- Create Coupon Panel (Normal Document Flow) -->
    <div id="couponPanel" class="hidden bg-[#0D111A] border-2 border-rose-500/50 rounded-3xl p-6 max-w-lg mx-auto space-y-4 shadow-2xl">
        <div class="flex items-center justify-between border-b border-gray-800 pb-3">
            <h3 class="font-bold text-white text-sm">Create New Coupon Code</h3>
            <button type="button" id="closeCouponPanelBtn" class="text-gray-400 hover:text-white text-sm">&times;</button>
        </div>

        <form id="couponForm" class="space-y-3">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Coupon Code *</label>
                    <input type="text" id="cpnCode" required placeholder="e.g. FREEFIRE10"
                           class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white uppercase font-mono focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Discount Type</label>
                    <select id="cpnType" class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white">
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed INR (₹)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Discount Value *</label>
                    <input type="number" id="cpnValue" step="0.5" required placeholder="10"
                           class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Min Order Amount (INR)</label>
                    <input type="number" id="cpnMin" step="1" value="0"
                           class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Usage Limit</label>
                    <input type="number" id="cpnLimit" value="100"
                           class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Expiration Date</label>
                    <input type="date" id="cpnExpires"
                           class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t border-gray-800">
                <button type="button" id="cancelCouponBtn" class="px-4 py-2 rounded-xl bg-gray-800 text-gray-300 hover:text-white text-xs font-semibold">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow">Save Coupon</button>
            </div>
        </form>
    </div>

    <!-- Coupons Table -->
    <div class="bg-[#0D111A] border border-gray-800 rounded-2xl overflow-hidden shadow-lg">
        <?php if (empty($coupons)): ?>
            <div class="p-12 text-center text-xs text-gray-500">
                No active coupon codes found.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#121824] text-gray-400 uppercase text-[10px]">
                        <tr>
                            <th class="p-3.5">Code</th>
                            <th class="p-3.5">Discount</th>
                            <th class="p-3.5">Min Order</th>
                            <th class="p-3.5">Used / Limit</th>
                            <th class="p-3.5">Expires At</th>
                            <th class="p-3.5">Status</th>
                            <th class="p-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        <?php foreach ($coupons as $cpn): ?>
                        <tr class="hover:bg-gray-800/30">
                            <td class="p-3.5 font-mono font-bold text-rose-400"><?= htmlspecialchars($cpn['code']) ?></td>
                            <td class="p-3.5 font-bold text-white">
                                <?= $cpn['discount_type'] === 'percentage' ? $cpn['discount_value'] . '%' : '₹ ' . number_format($cpn['discount_value'], 2) ?>
                            </td>
                            <td class="p-3.5 text-gray-300">₹ <?= number_format($cpn['min_order_amount'], 2) ?></td>
                            <td class="p-3.5 font-mono text-gray-400"><?= $cpn['times_used'] ?> / <?= $cpn['usage_limit'] ?></td>
                            <td class="p-3.5 text-gray-400"><?= $cpn['expires_at'] ? date('M d, Y', strtotime($cpn['expires_at'])) : 'Never' ?></td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold <?= $cpn['status'] === 'active' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-gray-700 text-gray-400' ?>">
                                    <?= ucfirst($cpn['status']) ?>
                                </span>
                            </td>
                            <td class="p-3.5 text-right">
                                <button type="button" class="del-cpn-btn px-2.5 py-1 rounded-lg bg-rose-600/20 text-rose-400 hover:bg-rose-600/30 text-xs font-semibold"
                                        data-id="<?= $cpn['id'] ?>">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
const cpPanel = document.getElementById('couponPanel');
document.getElementById('openCouponModalBtn')?.addEventListener('click', () => cpPanel.classList.remove('hidden'));
document.getElementById('closeCouponPanelBtn')?.addEventListener('click', () => cpPanel.classList.add('hidden'));
document.getElementById('cancelCouponBtn')?.addEventListener('click', () => cpPanel.classList.add('hidden'));

document.getElementById('couponForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const res = await fetch('/admin/coupons/store', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            code: document.getElementById('cpnCode').value,
            discount_type: document.getElementById('cpnType').value,
            discount_value: document.getElementById('cpnValue').value,
            min_order_amount: document.getElementById('cpnMin').value,
            usage_limit: document.getElementById('cpnLimit').value,
            expires_at: document.getElementById('cpnExpires').value,
        })
    });
    const data = await res.json();
    if (data.success) {
        window.location.reload();
    } else {
        alert(data.message || 'Error creating coupon');
    }
});

document.querySelectorAll('.del-cpn-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        if (!confirm('Are you sure you want to delete this coupon?')) return;
        const id = btn.getAttribute('data-id');
        const res = await fetch(`/admin/coupons/${id}/delete`, { method: 'POST' });
        const data = await res.json();
        if (data.success) {
            window.location.reload();
        }
    });
});
</script>

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
