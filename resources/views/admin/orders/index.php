<?php
$pageTitle = "Orders Management - Admin Control Center";
$activeAdminNav = 'orders';
require __DIR__ . '/../layouts/admin_header.php';
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white">Orders Management</h1>
            <p class="text-xs text-gray-400 mt-1">Review customer orders, update statuses, and monitor API vs Manual delivery modes.</p>
        </div>

        <form action="/admin/orders" method="GET" class="flex items-center gap-2">
            <input type="text" name="q" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Search Order #, UID, or User" 
                   class="bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500 w-60">
            <button type="submit" class="px-3.5 py-2 bg-rose-600 hover:bg-rose-500 text-white rounded-xl text-xs font-bold transition">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>

    <!-- Filter Pills -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs">
        <a href="/admin/orders" class="px-3.5 py-1.5 rounded-full font-semibold <?= empty($activeStatus) ? 'bg-[#E11D48] text-white' : 'bg-[#0D111A] text-gray-400 hover:text-white border border-gray-800' ?>">All Orders</a>
        <a href="/admin/orders?status=pending" class="px-3.5 py-1.5 rounded-full font-semibold <?= ($activeStatus ?? '') === 'pending' ? 'bg-[#E11D48] text-white' : 'bg-[#0D111A] text-gray-400 hover:text-white border border-gray-800' ?>">Pending</a>
        <a href="/admin/orders?status=processing" class="px-3.5 py-1.5 rounded-full font-semibold <?= ($activeStatus ?? '') === 'processing' ? 'bg-[#E11D48] text-white' : 'bg-[#0D111A] text-gray-400 hover:text-white border border-gray-800' ?>">Processing</a>
        <a href="/admin/orders?status=completed" class="px-3.5 py-1.5 rounded-full font-semibold <?= ($activeStatus ?? '') === 'completed' ? 'bg-[#E11D48] text-white' : 'bg-[#0D111A] text-gray-400 hover:text-white border border-gray-800' ?>">Completed</a>
        <a href="/admin/orders?status=failed" class="px-3.5 py-1.5 rounded-full font-semibold <?= ($activeStatus ?? '') === 'failed' ? 'bg-[#E11D48] text-white' : 'bg-[#0D111A] text-gray-400 hover:text-white border border-gray-800' ?>">Failed/Cancelled</a>
    </div>

    <!-- Orders Table -->
    <div class="bg-[#0D111A] border border-gray-800 rounded-2xl overflow-hidden shadow-lg">
        <?php if (empty($orders)): ?>
            <div class="p-12 text-center text-xs text-gray-500">
                No orders match your criteria.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#121824] text-gray-400 uppercase text-[10px]">
                        <tr>
                            <th class="p-3.5">Order ID</th>
                            <th class="p-3.5">User</th>
                            <th class="p-3.5">Product & Qty</th>
                            <th class="p-3.5">Player UID</th>
                            <th class="p-3.5">Amount</th>
                            <th class="p-3.5">Mode</th>
                            <th class="p-3.5">Status</th>
                            <th class="p-3.5">Created At</th>
                            <th class="p-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        <?php foreach ($orders as $ord): ?>
                        <tr class="hover:bg-gray-800/30">
                            <td class="p-3.5 font-mono font-bold text-rose-400">#<?= htmlspecialchars($ord['order_number']) ?></td>
                            <td class="p-3.5 text-white">
                                <div class="font-medium"><?= htmlspecialchars($ord['user_name'] ?? 'User') ?></div>
                                <div class="text-[10px] text-gray-500"><?= htmlspecialchars($ord['user_email'] ?? '') ?></div>
                            </td>
                            <td class="p-3.5 text-gray-300 font-medium"><?= htmlspecialchars($ord['product_name']) ?> (x<?= $ord['quantity'] ?>)</td>
                            <td class="p-3.5 font-mono text-cyan-400 font-bold"><?= htmlspecialchars($ord['customer_ff_uid']) ?></td>
                            <td class="p-3.5 font-bold text-white">₹ <?= number_format($ord['total_amount'], 2) ?></td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase <?= $ord['service_mode'] === 'api' ? 'bg-cyan-950 text-cyan-400 border border-cyan-800' : 'bg-gray-800 text-gray-300' ?>">
                                    <?= htmlspecialchars($ord['service_mode'] ?? 'manual') ?>
                                </span>
                            </td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase
                                    <?= $ord['status'] === 'completed' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 
                                       ($ord['status'] === 'processing' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 
                                       ($ord['status'] === 'failed' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-amber-500/20 text-amber-400 border border-amber-500/30')) ?>">
                                    <?= htmlspecialchars($ord['status']) ?>
                                </span>
                            </td>
                            <td class="p-3.5 text-gray-500 text-[11px]"><?= date('d M, h:i A', strtotime($ord['created_at'])) ?></td>
                            <td class="p-3.5 text-right space-x-1">
                                <button class="status-modal-btn px-2.5 py-1 rounded bg-[#121824] hover:bg-gray-700 text-white font-semibold text-[11px] transition"
                                        data-id="<?= $ord['id'] ?>" data-number="<?= htmlspecialchars($ord['order_number']) ?>" data-status="<?= htmlspecialchars($ord['status']) ?>">
                                    Update Status
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

<!-- Status Update Panel (Normal document flow) -->
<div id="statusModal" class="hidden my-6 bg-[#0D111A] border-2 border-rose-500/60 rounded-3xl max-w-xl mx-auto p-6 space-y-4 shadow-2xl">
    <div class="flex items-center justify-between border-b border-gray-800 pb-3">
        <h3 class="font-bold text-white text-sm">Update Order Status: <span id="modalOrderNumber" class="text-rose-400 font-mono"></span></h3>
        <button type="button" id="closeStatusXBtn" class="text-gray-400 hover:text-white text-sm">&times;</button>
    </div>
    <input type="hidden" id="modalOrderId">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-300 mb-1">New Status</label>
            <select id="modalNewStatus" class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-rose-500">
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
                <option value="failed">Failed</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-300 mb-1">Admin Notes</label>
            <input type="text" id="modalNotes" placeholder="e.g. Completed via direct UID portal" 
                   class="w-full bg-[#121824] border border-gray-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-rose-500">
        </div>
    </div>

    <div class="flex justify-end gap-2 pt-2 border-t border-gray-800">
        <button id="cancelStatusBtn" class="px-4 py-2 rounded-xl bg-gray-800 text-gray-300 hover:text-white text-xs font-semibold">Cancel</button>
        <button id="saveStatusBtn" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shadow">Save Status</button>
    </div>
</div>

<script>
const modal = document.getElementById('statusModal');
document.querySelectorAll('.status-modal-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('modalOrderId').value = btn.getAttribute('data-id');
        document.getElementById('modalOrderNumber').textContent = '#' + btn.getAttribute('data-number');
        document.getElementById('modalNewStatus').value = btn.getAttribute('data-status');
        modal.classList.remove('hidden');
    });
});

document.getElementById('cancelStatusBtn')?.addEventListener('click', () => modal.classList.add('hidden'));
document.getElementById('closeStatusXBtn')?.addEventListener('click', () => modal.classList.add('hidden'));

document.getElementById('saveStatusBtn')?.addEventListener('click', async () => {
    const id = document.getElementById('modalOrderId').value;
    const status = document.getElementById('modalNewStatus').value;
    const notes = document.getElementById('modalNotes').value;

    const res = await fetch(`/admin/orders/${id}/status`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ status, notes })
    });
    const data = await res.json();
    if (data.success) {
        window.location.reload();
    } else {
        alert(data.message || 'Status update failed');
    }
});
</script>

<?php require __DIR__ . '/../layouts/admin_footer.php'; ?>
