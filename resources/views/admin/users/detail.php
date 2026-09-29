<?php
$pageTitle = "User Profile: " . htmlspecialchars($user['name']) . " - Admin Control Center";
$activeAdminNav = 'users';
require __DIR__ . '/../../layouts/admin_header.php';
?>

<div class="space-y-6 max-w-6xl mx-auto">
    <div class="flex items-center justify-between">
        <a href="/admin/users" class="text-xs text-rose-400 hover:underline">← Back to Users</a>
        <div class="flex items-center gap-2">
            <span class="text-xs text-gray-400">Status:</span>
            <select id="userStatusSelect" class="bg-[#121824] border border-gray-800 rounded-lg px-2.5 py-1 text-xs text-white">
                <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="suspended" <?= $user['status'] === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                <option value="banned" <?= $user['status'] === 'banned' ? 'selected' : '' ?>>Banned</option>
            </select>
            <button type="button" id="saveUserStatusBtn" class="px-3 py-1 bg-gray-800 hover:bg-gray-700 text-white rounded-lg text-xs font-semibold">Update</button>
        </div>
    </div>

    <!-- User Header Card -->
    <div class="bg-[#0D111A] border border-gray-800 rounded-3xl p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-rose-600 to-rose-400 flex items-center justify-center font-black text-2xl text-white">
                <?= strtoupper(substr($user['name'], 0, 1)) ?>
            </div>
            <div>
                <h1 class="text-xl font-black text-white"><?= htmlspecialchars($user['name']) ?></h1>
                <p class="text-xs text-gray-400"><?= htmlspecialchars($user['email']) ?> • Phone: <?= htmlspecialchars($user['phone'] ?: 'None') ?></p>
                <div class="text-[11px] text-gray-500 mt-1">Player UID: <?= htmlspecialchars($user['ff_player_uid'] ?? 'Not set') ?> • Referral Code: <span class="font-mono text-rose-400"><?= htmlspecialchars($user['referral_code']) ?></span></div>
            </div>
        </div>

        <div class="p-4 bg-[#121824] rounded-2xl border border-gray-800 flex items-center justify-between md:justify-end gap-6">
            <div>
                <span class="text-[10px] uppercase font-bold text-gray-400 block">Wallet Balance</span>
                <span class="text-2xl font-black text-emerald-400 font-mono">₹ <?= number_format($user['wallet_balance'], 2) ?></span>
            </div>
            <button type="button" id="openAdjustModalBtn" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow">
                Adjust Balance
            </button>
        </div>
    </div>

    <!-- Balance Adjustment Panel (Normal Document Flow) -->
    <div id="adjustPanel" class="hidden bg-[#0D111A] border-2 border-rose-500/50 rounded-3xl p-6 max-w-md mx-auto space-y-4 shadow-2xl">
        <div class="flex items-center justify-between border-b border-gray-800 pb-3">
            <h3 class="font-bold text-white text-sm">Adjust User Wallet Balance</h3>
            <button type="button" id="closeAdjustPanelBtn" class="text-gray-400 hover:text-white text-sm">&times;</button>
        </div>

        <form id="adjustForm" class="space-y-3">
            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-1">Adjustment Type</label>
                <select id="adjType" class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white">
                    <option value="credit">Credit Balance (+)</option>
                    <option value="debit">Debit Balance (-)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-1">Amount (INR) *</label>
                <input type="number" id="adjAmount" step="0.5" required placeholder="100.00"
                       class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-1">Admin Reason / Reference</label>
                <input type="text" id="adjReason" placeholder="e.g. Manual top-up compensation"
                       class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500">
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t border-gray-800">
                <button type="button" id="cancelAdjustBtn" class="px-4 py-2 rounded-xl bg-gray-800 text-gray-300 hover:text-white text-xs font-semibold">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow">Execute Adjustment</button>
            </div>
        </form>
    </div>

    <!-- Orders & Wallet Logs Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders -->
        <div class="bg-[#0D111A] border border-gray-800 rounded-3xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider">User's Orders</h3>
            <?php if (empty($orders)): ?>
                <div class="p-8 text-center text-xs text-gray-500">No orders placed by this user yet.</div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($orders as $ord): ?>
                    <div class="p-3 bg-[#121824] rounded-xl border border-gray-800 flex items-center justify-between text-xs">
                        <div>
                            <span class="font-mono font-bold text-rose-400">#<?= htmlspecialchars($ord['order_number']) ?></span>
                            <div class="text-white font-semibold mt-0.5"><?= htmlspecialchars($ord['product_name']) ?></div>
                            <span class="text-[10px] text-gray-500">UID: <?= htmlspecialchars($ord['customer_ff_uid']) ?></span>
                        </div>
                        <div class="text-right">
                            <div class="font-mono font-bold text-white">₹ <?= number_format($ord['total_amount'], 2) ?></div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold <?= $ord['status'] === 'completed' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-yellow-500/20 text-yellow-400' ?>">
                                <?= ucfirst($ord['status']) ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Wallet History -->
        <div class="bg-[#0D111A] border border-gray-800 rounded-3xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider">Wallet Audit Log</h3>
            <?php if (empty($walletLogs)): ?>
                <div class="p-8 text-center text-xs text-gray-500">No transactions recorded.</div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($walletLogs as $log): ?>
                    <div class="p-3 bg-[#121824] rounded-xl border border-gray-800 flex items-center justify-between text-xs">
                        <div>
                            <span class="font-semibold text-white"><?= htmlspecialchars($log['description']) ?></span>
                            <div class="text-[10px] text-gray-500 font-mono"><?= $log['reference_id'] ?> • <?= date('M d, H:i', strtotime($log['created_at'])) ?></div>
                        </div>
                        <div class="text-right font-mono">
                            <div class="font-bold <?= $log['type'] === 'credit' ? 'text-emerald-400' : 'text-rose-400' ?>">
                                <?= $log['type'] === 'credit' ? '+' : '-' ?> ₹ <?= number_format($log['amount'], 2) ?>
                            </div>
                            <span class="text-[10px] text-gray-400">Bal: ₹ <?= number_format($log['balance_after'], 2) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
const aPanel = document.getElementById('adjustPanel');
document.getElementById('openAdjustModalBtn')?.addEventListener('click', () => aPanel.classList.remove('hidden'));
document.getElementById('closeAdjustPanelBtn')?.addEventListener('click', () => aPanel.classList.add('hidden'));
document.getElementById('cancelAdjustBtn')?.addEventListener('click', () => aPanel.classList.add('hidden'));

document.getElementById('adjustForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const res = await fetch('/admin/users/<?= $user['id'] ?>/balance', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            type: document.getElementById('adjType').value,
            amount: document.getElementById('adjAmount').value,
            reason: document.getElementById('adjReason').value,
        })
    });
    const data = await res.json();
    if (data.success) {
        alert(data.message);
        window.location.reload();
    } else {
        alert(data.message || 'Adjustment failed');
    }
});

document.getElementById('saveUserStatusBtn')?.addEventListener('click', async () => {
    const status = document.getElementById('userStatusSelect').value;
    const res = await fetch('/admin/users/<?= $user['id'] ?>/status', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ status })
    });
    const data = await res.json();
    alert(data.message);
});
</script>

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
