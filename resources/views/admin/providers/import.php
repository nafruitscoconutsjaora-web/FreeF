<?php
$pageTitle = "Service Import API - Admin Control Center";
$activeAdminNav = 'import';
require __DIR__ . '/../../layouts/admin_header.php';
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white">Import Services from Supplier API</h1>
            <p class="text-xs text-gray-400 mt-1">Automatically pull services from connected providers, calculate markup in INR, and import into store catalog.</p>
        </div>
        <a href="/admin/providers" class="text-xs text-rose-400 hover:underline">← Manage Providers</a>
    </div>

    <!-- Provider Selection & Currency Conversion Settings -->
    <div class="bg-[#0D111A] border border-gray-800 rounded-3xl p-6 space-y-6">
        <h3 class="text-sm font-bold text-white uppercase tracking-wider">1. Select API Supplier & Pricing Model</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-1">Select Provider *</label>
                <select id="importProvider" class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500">
                    <option value="">-- Choose Provider --</option>
                    <?php foreach ($providers as $prov): ?>
                        <option value="<?= $prov['id'] ?>"><?= htmlspecialchars($prov['name']) ?> (<?= $prov['currency'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-1">Assign to Category *</label>
                <select id="importCategory" class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-1">Profit Markup (%)</label>
                <input type="number" id="importMarkup" value="15" step="0.5" 
                       class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-1">Exchange Rate (USD to INR)</label>
                <input type="number" id="importExchange" value="85.50" step="0.05" 
                       class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500 font-mono">
            </div>
        </div>

        <div class="flex justify-end">
            <button type="button" id="loadServicesBtn" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow transition flex items-center gap-2">
                <i class="fa-solid fa-arrows-rotate"></i> Fetch Services from Provider
            </button>
        </div>
    </div>

    <!-- Provider Services List Table -->
    <div class="bg-[#0D111A] border border-gray-800 rounded-3xl p-6 space-y-4">
        <h3 class="text-sm font-bold text-white uppercase tracking-wider">2. Available Services to Import</h3>
        
        <div id="serviceListContainer" class="p-8 text-center text-xs text-gray-500">
            Select a provider above and click "Fetch Services" to load available Free Fire services.
        </div>
    </div>
</div>

<script>
document.getElementById('loadServicesBtn')?.addEventListener('click', async () => {
    const provId = document.getElementById('importProvider').value;
    if (!provId) {
        alert('Please choose a provider first.');
        return;
    }

    const container = document.getElementById('serviceListContainer');
    container.innerHTML = '<div class="text-rose-400 font-semibold py-8"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Querying provider API endpoint...</div>';

    try {
        const res = await fetch(`/admin/providers/${provId}/services`);
        const data = await res.json();
        
        if (!data.success || !data.services || data.services.length === 0) {
            container.innerHTML = '<div class="text-gray-400 py-8">No services returned by provider or connection timed out.</div>';
            return;
        }

        const markup = parseFloat(document.getElementById('importMarkup').value) || 15;
        const exchange = parseFloat(document.getElementById('importExchange').value) || 85.50;

        let html = `
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#121824] text-gray-400 uppercase text-[10px]">
                    <tr>
                        <th class="p-3">Service ID</th>
                        <th class="p-3">Service Name</th>
                        <th class="p-3">Supplier Cost</th>
                        <th class="p-3">Customer Price (INR)</th>
                        <th class="p-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
        `;

        data.services.forEach(s => {
            const rawRate = parseFloat(s.rate) || 0;
            const inrCost = rawRate * exchange;
            const sellPrice = (inrCost * (1 + (markup / 100))).toFixed(2);

            html += `
            <tr class="hover:bg-gray-800/30">
                <td class="p-3 font-mono font-bold text-gray-400">#${s.service || s.id}</td>
                <td class="p-3 font-semibold text-white">${s.name}</td>
                <td class="p-3 font-mono text-gray-400">$ ${rawRate.toFixed(4)}</td>
                <td class="p-3 font-mono font-bold text-rose-400">₹ ${sellPrice}</td>
                <td class="p-3 text-right">
                    <button type="button" class="import-one-btn px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs"
                            data-service-id="${s.service || s.id}"
                            data-name="${s.name.replace(/"/g, '&quot;')}"
                            data-rate="${rawRate}">
                        <i class="fa-solid fa-cloud-arrow-down mr-1"></i> Import to Store
                    </button>
                </td>
            </tr>
            `;
        });

        html += `</tbody></table></div>`;
        container.innerHTML = html;

        document.querySelectorAll('.import-one-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const svcId = btn.getAttribute('data-service-id');
                const name = btn.getAttribute('data-name');
                const rate = parseFloat(btn.getAttribute('data-rate'));
                const catId = document.getElementById('importCategory').value;

                btn.disabled = true;
                btn.textContent = 'Importing...';

                const importRes = await fetch('/admin/providers/import-service', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        provider_id: provId,
                        provider_service_id: svcId,
                        name: name,
                        category_id: catId,
                        provider_rate: rate,
                        markup_percent: markup,
                        exchange_rate: exchange
                    })
                });

                const importData = await importRes.json();
                if (importData.success) {
                    btn.classList.remove('bg-rose-600');
                    btn.classList.add('bg-emerald-600');
                    btn.innerHTML = '<i class="fa-solid fa-check"></i> Imported!';
                } else {
                    alert(importData.message || 'Import failed.');
                    btn.disabled = false;
                    btn.textContent = 'Import to Store';
                }
            });
        });

    } catch (err) {
        container.innerHTML = '<div class="text-rose-500 py-8">Failed to load services: ' + err.message + '</div>';
    }
});
</script>

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
