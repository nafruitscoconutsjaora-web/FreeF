<?php
$pageTitle = "Catalog & Services - Admin Control Center";
$activeAdminNav = 'services';
require __DIR__ . '/../layouts/admin_header.php';
?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-white">Services & Products</h1>
            <p class="text-xs text-gray-400 mt-1">Manage store items, prices, category assignments, and delivery modes.</p>
        </div>
        <button id="openNewServiceBtn" class="px-4 py-2 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs flex items-center gap-1.5 shadow">
            <i class="fa-solid fa-plus"></i> Add Service
        </button>
    </div>

    <!-- Products List -->
    <div class="bg-[#0D111A] border border-gray-800 rounded-2xl overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#121824] text-gray-400 uppercase text-[10px]">
                    <tr>
                        <th class="p-3.5">ID</th>
                        <th class="p-3.5">Service Name</th>
                        <th class="p-3.5">Category</th>
                        <th class="p-3.5">Selling Price</th>
                        <th class="p-3.5">Original Price</th>
                        <th class="p-3.5">Badge</th>
                        <th class="p-3.5">Mode</th>
                        <th class="p-3.5">Status</th>
                        <th class="p-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    <?php foreach ($products as $pr): ?>
                    <tr class="hover:bg-gray-800/30">
                        <td class="p-3.5 font-mono text-gray-400"><?= $pr['id'] ?></td>
                        <td class="p-3.5 font-bold text-white"><?= htmlspecialchars($pr['name']) ?></td>
                        <td class="p-3.5 text-gray-300"><?= htmlspecialchars($pr['category_name'] ?? 'General') ?></td>
                        <td class="p-3.5 font-bold text-emerald-400">₹ <?= number_format($pr['price'], 2) ?></td>
                        <td class="p-3.5 text-gray-500"><?= !empty($pr['original_price']) ? '₹ ' . number_format($pr['original_price'], 2) : '—' ?></td>
                        <td class="p-3.5">
                            <?= !empty($pr['badge']) ? '<span class="px-2 py-0.5 rounded text-[9px] font-bold bg-rose-950 text-rose-400 border border-rose-800">' . htmlspecialchars($pr['badge']) . '</span>' : '—' ?>
                        </td>
                        <td class="p-3.5">
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase <?= $pr['service_mode'] === 'api' ? 'bg-cyan-950 text-cyan-400 border border-cyan-800' : 'bg-gray-800 text-gray-300' ?>">
                                <?= htmlspecialchars($pr['service_mode']) ?>
                            </span>
                        </td>
                        <td class="p-3.5">
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase <?= $pr['status'] === 'active' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-gray-800 text-gray-400' ?>">
                                <?= htmlspecialchars($pr['status']) ?>
                            </span>
                        </td>
                        <td class="p-3.5 text-right">
                            <button class="delete-service-btn text-gray-500 hover:text-rose-400 text-xs p-1" data-id="<?= $pr['id'] ?>" title="Delete Service">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Service Panel (Normal document flow) -->
<div id="serviceModal" class="my-6 bg-[#0D111A] border-2 border-rose-500/60 rounded-3xl max-w-xl mx-auto p-6 space-y-4 shadow-2xl hidden">
    <div class="flex items-center justify-between border-b border-gray-800 pb-3">
        <h3 class="font-bold text-white text-base">Add New Store Service</h3>
        <button type="button" id="closeSvcXBtn" class="text-gray-400 hover:text-white text-sm">&times;</button>
    </div>

    <form id="addServiceForm" class="space-y-3">
        <div>
            <label class="block text-xs font-semibold text-gray-300 mb-1">Service Name *</label>
            <input type="text" id="svcName" required placeholder="e.g. 5600 Diamonds" 
                   class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500">
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-300 mb-1">Category *</label>
            <select id="svcCategory" required class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-2">
            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-1">Price (INR) *</label>
                <input type="number" id="svcPrice" step="0.5" required placeholder="799.00" 
                       class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-1">Original Price</label>
                <input type="number" id="svcOrigPrice" step="0.5" placeholder="999.00" 
                       class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-300 mb-1">Promotional Badge</label>
            <input type="text" id="svcBadge" placeholder="e.g. Popular, Hot Deal" 
                   class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500">
        </div>

        <div class="flex gap-2 pt-2 border-t border-gray-800">
            <button type="button" id="closeSvcModalBtn" class="flex-1 py-2.5 rounded-xl bg-gray-800 text-gray-300 hover:text-white text-xs font-semibold">Cancel</button>
            <button type="submit" class="flex-1 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shadow">Create Service</button>
        </div>
    </form>
</div>

<script>
const modal = document.getElementById('serviceModal');
document.getElementById('openNewServiceBtn')?.addEventListener('click', () => modal.classList.remove('hidden'));
document.getElementById('closeSvcModalBtn')?.addEventListener('click', () => modal.classList.add('hidden'));
document.getElementById('closeSvcXBtn')?.addEventListener('click', () => modal.classList.add('hidden'));

document.getElementById('addServiceForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const res = await fetch('/admin/products', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            name: document.getElementById('svcName').value,
            category_id: document.getElementById('svcCategory').value,
            price: document.getElementById('svcPrice').value,
            original_price: document.getElementById('svcOrigPrice').value,
            badge: document.getElementById('svcBadge').value,
            service_mode: 'manual'
        })
    });
    const data = await res.json();
    if (data.success) {
        window.location.reload();
    } else {
        alert(data.message || 'Creation failed');
    }
});

document.querySelectorAll('.delete-service-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        if (!confirm('Are you sure you want to delete this product?')) return;
        const id = btn.getAttribute('data-id');
        const res = await fetch(`/admin/products/${id}/delete`, { method: 'POST' });
        const data = await res.json();
        if (data.success) {
            window.location.reload();
        }
    });
});
</script>

<?php require __DIR__ . '/../layouts/admin_footer.php'; ?>
