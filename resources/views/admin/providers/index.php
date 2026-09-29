<?php
$pageTitle = "Provider APIs - Admin Control Center";
$activeAdminNav = 'providers';
require __DIR__ . '/../../layouts/admin_header.php';
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white">Provider APIs & Gateways</h1>
            <p class="text-xs text-gray-400 mt-1">Connect upstream Free Fire service suppliers and gaming top-up APIs.</p>
        </div>

        <button type="button" id="openProviderModalBtn"
                class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs flex items-center gap-2 shadow transition">
            <i class="fa-solid fa-plus"></i> Add Provider API
        </button>
    </div>

    <!-- Add Provider Panel (Normal Document Flow) -->
    <div id="providerPanel" class="hidden bg-[#0D111A] border-2 border-rose-500/50 rounded-3xl p-6 max-w-xl mx-auto space-y-4 shadow-2xl">
        <div class="flex items-center justify-between border-b border-gray-800 pb-3">
            <h3 class="font-bold text-white text-sm">Add New API Provider</h3>
            <button type="button" id="closeProviderPanelBtn" class="text-gray-400 hover:text-white text-sm">&times;</button>
        </div>

        <form id="providerForm" class="space-y-3">
            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-1">Provider Name *</label>
                <input type="text" id="provName" required placeholder="e.g. Peak Gaming API"
                       class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-1">API Endpoint URL *</label>
                <input type="url" id="provUrl" required placeholder="https://provider.com/api/v2"
                       class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500 font-mono">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">API Key / Token *</label>
                    <input type="password" id="provKey" required placeholder="Secret API Key"
                           class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Currency</label>
                    <select id="provCurrency" class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500">
                        <option value="USD">USD ($)</option>
                        <option value="INR">INR (₹)</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t border-gray-800">
                <button type="button" id="cancelProviderBtn" class="px-4 py-2 rounded-xl bg-gray-800 text-gray-300 hover:text-white text-xs font-semibold">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow">Save Provider</button>
            </div>
        </form>
    </div>

    <!-- Providers Table -->
    <div class="bg-[#0D111A] border border-gray-800 rounded-2xl overflow-hidden shadow-lg">
        <?php if (empty($providers)): ?>
            <div class="p-12 text-center text-xs text-gray-500">
                <i class="fa-solid fa-server text-4xl mb-3 text-gray-600 block"></i>
                No providers connected yet. Click "Add Provider API" to connect your first supplier.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#121824] text-gray-400 uppercase text-[10px]">
                        <tr>
                            <th class="p-3.5">Provider Name</th>
                            <th class="p-3.5">Endpoint URL</th>
                            <th class="p-3.5">Currency</th>
                            <th class="p-3.5">Supplier Balance</th>
                            <th class="p-3.5">Last Sync</th>
                            <th class="p-3.5">Status</th>
                            <th class="p-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        <?php foreach ($providers as $prov): ?>
                        <tr class="hover:bg-gray-800/30">
                            <td class="p-3.5 font-bold text-white"><?= htmlspecialchars($prov['name']) ?></td>
                            <td class="p-3.5 font-mono text-gray-400 max-w-xs truncate"><?= htmlspecialchars($prov['api_url']) ?></td>
                            <td class="p-3.5 font-semibold text-rose-400"><?= htmlspecialchars($prov['currency']) ?></td>
                            <td class="p-3.5 font-mono font-bold text-emerald-400">
                                <?= $prov['currency'] === 'USD' ? '$' : '₹' ?> <?= number_format($prov['balance'] ?? 0.00, 2) ?>
                            </td>
                            <td class="p-3.5 text-gray-500"><?= $prov['last_balance_check'] ? date('M d, H:i', strtotime($prov['last_balance_check'])) : 'Never' ?></td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold <?= $prov['status'] === 'active' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' ?>">
                                    <?= ucfirst($prov['status']) ?>
                                </span>
                            </td>
                            <td class="p-3.5 text-right space-x-2">
                                <button type="button" class="test-conn-btn px-2.5 py-1.5 rounded-lg bg-sky-500/20 hover:bg-sky-500/30 text-sky-400 font-semibold text-xs border border-sky-500/30"
                                        data-id="<?= $prov['id'] ?>">
                                    <i class="fa-solid fa-plug"></i> Test API
                                </button>
                                <a href="/admin/services/import" class="px-2.5 py-1.5 rounded-lg bg-rose-600/20 hover:bg-rose-600/30 text-rose-400 font-semibold text-xs border border-rose-500/30 inline-block">
                                    <i class="fa-solid fa-cloud-arrow-down"></i> Import
                                </a>
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
const pPanel = document.getElementById('providerPanel');
document.getElementById('openProviderModalBtn')?.addEventListener('click', () => pPanel.classList.remove('hidden'));
document.getElementById('closeProviderPanelBtn')?.addEventListener('click', () => pPanel.classList.add('hidden'));
document.getElementById('cancelProviderBtn')?.addEventListener('click', () => pPanel.classList.add('hidden'));

document.getElementById('providerForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const res = await fetch('/admin/providers', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            name: document.getElementById('provName').value,
            api_url: document.getElementById('provUrl').value,
            api_key: document.getElementById('provKey').value,
            currency: document.getElementById('provCurrency').value,
        })
    });
    const data = await res.json();
    if (data.success) {
        alert(data.message);
        window.location.reload();
    } else {
        alert(data.message || 'Error creating provider');
    }
});

document.querySelectorAll('.test-conn-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const id = btn.getAttribute('data-id');
        btn.textContent = 'Testing...';
        const res = await fetch(`/admin/providers/${id}/test`, { method: 'POST' });
        const data = await res.json();
        alert(data.message);
        btn.innerHTML = '<i class="fa-solid fa-plug"></i> Test API';
    });
});
</script>

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
