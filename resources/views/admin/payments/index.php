<?php
$pageTitle = "Razorpay & Gateway Payments - Admin Control Center";
$activeAdminNav = 'payments';
require __DIR__ . '/../../layouts/admin_header.php';
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white">Payment Transactions</h1>
            <p class="text-xs text-gray-400 mt-1">Real-time gateway records verified by Razorpay signature HMAC.</p>
        </div>

        <div class="flex items-center gap-3">
            <div class="bg-[#0D111A] border border-gray-800 rounded-xl px-4 py-2 text-right">
                <span class="text-[10px] uppercase font-bold text-gray-400 block">Verified Revenue</span>
                <span class="text-base font-black text-emerald-400 font-mono">₹ <?= number_format($totalRevenue ?? 0.00, 2) ?></span>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-[#0D111A] border border-gray-800 rounded-2xl overflow-hidden shadow-lg">
        <?php if (empty($payments)): ?>
            <div class="p-12 text-center text-xs text-gray-500">
                No payment transactions recorded yet.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#121824] text-gray-400 uppercase text-[10px]">
                        <tr>
                            <th class="p-3.5">Payment ID</th>
                            <th class="p-3.5">User</th>
                            <th class="p-3.5">Order Ref</th>
                            <th class="p-3.5">Gateway Transaction ID</th>
                            <th class="p-3.5">Amount</th>
                            <th class="p-3.5">Status</th>
                            <th class="p-3.5">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        <?php foreach ($payments as $pay): ?>
                        <tr class="hover:bg-gray-800/30">
                            <td class="p-3.5 font-mono text-gray-400">#PAY-<?= $pay['id'] ?></td>
                            <td class="p-3.5">
                                <div class="font-bold text-white"><?= htmlspecialchars($pay['user_name'] ?? 'Guest') ?></div>
                                <div class="text-[10px] text-gray-400"><?= htmlspecialchars($pay['user_email'] ?? '') ?></div>
                            </td>
                            <td class="p-3.5 font-mono font-bold text-rose-400">#<?= htmlspecialchars($pay['order_number'] ?? 'N/A') ?></td>
                            <td class="p-3.5 font-mono text-gray-300"><?= htmlspecialchars($pay['transaction_id'] ?? $pay['gateway_order_id']) ?></td>
                            <td class="p-3.5 font-mono font-bold text-emerald-400">₹ <?= number_format($pay['amount'], 2) ?></td>
                            <td class="p-3.5">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold <?= $pay['status'] === 'completed' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-yellow-500/20 text-yellow-400' ?>">
                                    <?= ucfirst($pay['status']) ?>
                                </span>
                            </td>
                            <td class="p-3.5 text-gray-500"><?= date('M d, Y h:i A', strtotime($pay['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
