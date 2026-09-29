<?php
$pageTitle = "My Orders - FF Panel Store";
require __DIR__ . '/../layouts/user_header.php';
?>

<div class="max-w-5xl mx-auto px-4 py-8 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-white">Order History</h1>
            <p class="text-xs text-gray-400 mt-1">Track your Free Fire recharges and instant service deliveries</p>
        </div>
    </div>

    <!-- Orders List -->
    <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-6">
        <?php if (empty($orders)): ?>
            <div class="py-12 text-center text-gray-500 text-sm">
                No orders found. Check out our services catalog to place your first recharge!
            </div>
        <?php else: ?>
            <div class="divide-y divide-gray-800">
                <?php foreach ($orders as $o): ?>
                <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-white text-sm">#<?= htmlspecialchars($o['order_number']) ?></span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase
                                <?= $o['status'] === 'completed' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 
                                   ($o['status'] === 'processing' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 
                                   ($o['status'] === 'failed' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-amber-500/20 text-amber-400 border border-amber-500/30')) ?>">
                                <?= htmlspecialchars($o['status']) ?>
                            </span>
                        </div>
                        <div class="text-xs text-gray-300 font-medium"><?= htmlspecialchars($o['product_name']) ?> (Qty: <?= $o['quantity'] ?>)</div>
                        <div class="text-[11px] text-gray-500">Player UID: <span class="text-gray-300 font-mono"><?= htmlspecialchars($o['customer_ff_uid']) ?></span> • <?= date('d M Y, h:i A', strtotime($o['created_at'])) ?></div>
                    </div>

                    <div class="flex items-center justify-between sm:justify-end gap-4">
                        <div class="text-right">
                            <span class="text-base font-black text-white">₹ <?= number_format($o['total_amount'], 2) ?></span>
                            <span class="text-[10px] text-gray-400 block"><?= ucfirst($o['payment_method']) ?> (<?= ucfirst($o['payment_status']) ?>)</span>
                        </div>
                        <a href="/orders/<?= urlencode($o['order_number']) ?>" class="px-3.5 py-1.5 rounded-xl bg-gray-800 hover:bg-gray-700 text-xs font-semibold text-white transition">
                            Details
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layouts/user_footer.php'; ?>
