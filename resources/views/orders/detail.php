<?php
$pageTitle = "Order Details #" . htmlspecialchars($order['order_number']) . " - FF Panel Store";
$activeNav = 'orders';
require __DIR__ . '/../layouts/user_header.php';

$statusColors = [
    'pending' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/30',
    'processing' => 'bg-sky-500/10 text-sky-400 border-sky-500/30',
    'completed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
    'cancelled' => 'bg-gray-800 text-gray-400 border-gray-700',
    'failed' => 'bg-rose-500/10 text-rose-400 border-rose-500/30',
];
?>

<div class="max-w-4xl mx-auto px-4 lg:px-8 py-8 space-y-6">
    <div class="flex items-center justify-between">
        <a href="/orders" class="text-xs text-rose-400 hover:underline flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Back to Orders
        </a>
        <span class="px-3.5 py-1 rounded-full text-xs font-bold border <?= $statusColors[$order['status']] ?? 'bg-gray-800 text-gray-300' ?>">
            ● <?= ucfirst($order['status']) ?>
        </span>
    </div>

    <!-- Main Card -->
    <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-6 sm:p-8 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-800 pb-6">
            <div>
                <span class="text-xs text-gray-500 block">Free Fire Order Reference</span>
                <h1 class="text-2xl font-black text-white font-mono mt-0.5">#<?= htmlspecialchars($order['order_number']) ?></h1>
                <span class="text-xs text-gray-400 mt-1 block">Placed on <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></span>
            </div>
            <div class="text-left sm:text-right">
                <span class="text-xs text-gray-500 block">Total Charged</span>
                <span class="text-3xl font-black text-rose-400 font-mono">₹ <?= number_format($order['total_amount'], 2) ?></span>
                <span class="text-xs text-emerald-400 block mt-0.5">Payment: <?= ucfirst($order['payment_status']) ?> (<?= strtoupper($order['payment_method']) ?>)</span>
            </div>
        </div>

        <!-- Order Information Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Product Details -->
            <div class="p-5 bg-[#121824] rounded-2xl border border-gray-800 space-y-3">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Recharge Package</span>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#0B0E14] flex items-center justify-center text-rose-500 text-xl shrink-0">
                        <i class="fa-solid fa-gem"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white"><?= htmlspecialchars($order['product_name']) ?></h4>
                        <span class="text-xs text-gray-400">Quantity: <?= $order['quantity'] ?> units</span>
                    </div>
                </div>
            </div>

            <!-- Free Fire Player UID -->
            <div class="p-5 bg-[#121824] rounded-2xl border border-gray-800 space-y-3">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Delivery Destination</span>
                <div>
                    <span class="text-xs text-gray-400 block">Player UID:</span>
                    <span class="text-lg font-mono font-bold text-white"><?= htmlspecialchars($order['customer_ff_uid']) ?></span>
                </div>
                <div class="text-xs text-gray-400">
                    Mode: <span class="text-rose-400 font-semibold"><?= strtoupper($order['delivery_mode'] ?? 'AUTO') ?></span>
                </div>
            </div>
        </div>

        <?php if (!empty($order['admin_notes'])): ?>
        <div class="p-4 rounded-2xl bg-gray-900/60 border border-gray-800 text-xs text-gray-300">
            <span class="font-bold text-rose-400 block mb-1">Operator / System Note:</span>
            <?= htmlspecialchars($order['admin_notes']) ?>
        </div>
        <?php endif; ?>

        <!-- Progress Timeline / History -->
        <div class="space-y-4 pt-4 border-t border-gray-800">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status History & Audit</h3>
            
            <?php if (empty($history)): ?>
                <div class="text-xs text-gray-500">Order recorded into database. Awaiting top-up execution.</div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($history as $h): ?>
                    <div class="flex items-start gap-3 text-xs">
                        <div class="w-2 h-2 rounded-full bg-rose-500 mt-1.5 shrink-0"></div>
                        <div>
                            <span class="font-semibold text-white">Status updated to <?= ucfirst($h['new_status']) ?></span>
                            <?php if (!empty($h['notes'])): ?>
                                <p class="text-gray-400"><?= htmlspecialchars($h['notes']) ?></p>
                            <?php endif; ?>
                            <span class="text-[10px] text-gray-500"><?= date('M d, Y h:i A', strtotime($h['created_at'])) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/user_footer.php'; ?>
