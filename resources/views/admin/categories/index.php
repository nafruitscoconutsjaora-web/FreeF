<?php
$pageTitle = "Categories Management - Admin Control Center";
$activeAdminNav = 'services';
require __DIR__ . '/../../layouts/admin_header.php';
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white">Categories Management</h1>
            <p class="text-xs text-gray-400 mt-1">Organize Free Fire services into Diamonds, Memberships, Elite Passes, Characters, and Bundles.</p>
        </div>

        <button type="button" id="openCatModalBtn"
                class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs flex items-center gap-2 shadow transition">
            <i class="fa-solid fa-plus"></i> Add Category
        </button>
    </div>

    <!-- Add Category Panel (Normal Document Flow) -->
    <div id="catPanel" class="hidden bg-[#0D111A] border-2 border-rose-500/50 rounded-3xl p-6 max-w-md mx-auto space-y-4 shadow-2xl">
        <div class="flex items-center justify-between border-b border-gray-800 pb-3">
            <h3 class="font-bold text-white text-sm">Add New Store Category</h3>
            <button type="button" id="closeCatPanelBtn" class="text-gray-400 hover:text-white text-sm">&times;</button>
        </div>

        <form id="catForm" class="space-y-3">
            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-1">Category Name *</label>
                <input type="text" id="catName" required placeholder="e.g. Special Airdrops"
                       class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-1">FontAwesome Icon Class</label>
                <input type="text" id="catIcon" placeholder="fa-solid fa-gift" value="fa-solid fa-gem"
                       class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500 font-mono">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-1">Display Sort Order</label>
                <input type="number" id="catSort" value="0"
                       class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500">
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t border-gray-800">
                <button type="button" id="cancelCatBtn" class="px-4 py-2 rounded-xl bg-gray-800 text-gray-300 hover:text-white text-xs font-semibold">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow">Save Category</button>
            </div>
        </form>
    </div>

    <!-- Categories List -->
    <div class="bg-[#0D111A] border border-gray-800 rounded-2xl overflow-hidden shadow-lg">
        <?php if (empty($categories)): ?>
            <div class="p-12 text-center text-xs text-gray-500">
                No categories configured.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#121824] text-gray-400 uppercase text-[10px]">
                        <tr>
                            <th class="p-3.5">Icon</th>
                            <th class="p-3.5">Name</th>
                            <th class="p-3.5">URL Slug</th>
                            <th class="p-3.5">Services Count</th>
                            <th class="p-3.5">Sort Order</th>
                            <th class="p-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        <?php foreach ($categories as $c): ?>
                        <tr class="hover:bg-gray-800/30">
                            <td class="p-3.5 text-rose-500 text-base">
                                <i class="<?= htmlspecialchars($c['icon'] ?? 'fa-solid fa-gem') ?>"></i>
                            </td>
                            <td class="p-3.5 font-bold text-white"><?= htmlspecialchars($c['name']) ?></td>
                            <td class="p-3.5 font-mono text-gray-400">/services?category=<?= htmlspecialchars($c['slug']) ?></td>
                            <td class="p-3.5 font-bold text-rose-400"><?= (int)($c['product_count'] ?? 0) ?> items</td>
                            <td class="p-3.5 text-gray-400"><?= $c['sort_order'] ?></td>
                            <td class="p-3.5 text-right">
                                <button type="button" class="del-cat-btn px-2.5 py-1 rounded-lg bg-rose-600/20 text-rose-400 hover:bg-rose-600/30 text-xs font-semibold"
                                        data-id="<?= $c['id'] ?>">
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
const cPanel = document.getElementById('catPanel');
document.getElementById('openCatModalBtn')?.addEventListener('click', () => cPanel.classList.remove('hidden'));
document.getElementById('closeCatPanelBtn')?.addEventListener('click', () => cPanel.classList.add('hidden'));
document.getElementById('cancelCatBtn')?.addEventListener('click', () => cPanel.classList.add('hidden'));

document.getElementById('catForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const res = await fetch('/admin/categories/store', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            name: document.getElementById('catName').value,
            icon: document.getElementById('catIcon').value,
            sort_order: document.getElementById('catSort').value,
        })
    });
    const data = await res.json();
    if (data.success) {
        window.location.reload();
    } else {
        alert(data.message || 'Error saving category');
    }
});

document.querySelectorAll('.del-cat-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        if (!confirm('Are you sure you want to delete this category?')) return;
        const id = btn.getAttribute('data-id');
        const res = await fetch(`/admin/categories/${id}/delete`, { method: 'POST' });
        const data = await res.json();
        if (data.success) {
            window.location.reload();
        }
    });
});
</script>

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
