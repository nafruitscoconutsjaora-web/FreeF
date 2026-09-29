<?php
$pageTitle = "Wallet Transactions Audit - Admin Control Center";
$activeAdminNav = 'wallet';
require __DIR__ . '/../../layouts/admin_header.php';
?>

<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-black text-white">Wallet Transactions Ledger</h1>
        <p class="text-xs text-gray-400 mt-1">Immutable financial ledger of all credits, debits, referral rewards, and order purchases.</p>
    </div>

    <!-- Wallet Transactions Table -->
    <div class="bg-[#0D111A] border border-gray-800 rounded-2xl overflow-hidden shadow-lg">
        <?php if (empty($logs)): ?>
            <div class="p-12 text-center text-xs text-gray-500">
                No wallet transactions recorded.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#121824] text-gray-400 uppercase text-[10px]">
                        <tr>
                            <th class="p-3.5">Ref #</th>
                            <th class="p-3.5">User</th>
                            <th class="p-3.5">Description</th>
                            <th class="p-3.5">Type</th>
                            <th class="p-3.5">Amount</th>
                            <th class="p-3.5">Balance After</th>
                            <th class="p-3.5">Date & Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        <?php foreach ($logs as $log): ?>
                        <tr class="hover:bg-gray-800/30">
                            <td class="p-3.5 font-mono text-gray-400"><?= htmlspecialchars($log['reference_id'] ?? 'WAL-' . $log['id']) ?></td>
                            <td class="p-3.5">
                                <span class="font-bold text-white"><?= htmlspecialchars($log['user_name']) ?></span>
                                <div class="text-[10px] text-gray-500"><?= htmlspecialchars($log['user_email']) ?></div>
                            </td>
                            <td class="p-3.5 text-gray-300"><?= htmlspecialchars($log['description']) ?></td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase <?= $log['type'] === 'credit' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' ?>">
                                    <?= $log['type'] ?>
                                </span>
                            </td>
                            <td class="p-3.5 font-mono font-bold <?= $log['type'] === 'credit' ? 'text-emerald-400' : 'text-rose-400' ?>">
                                <?= $log['type'] === 'credit' ? '+' : '-' ?> ₹ <?= number_format($log['amount'], 2) ?>
                            </td>
                            <td class="p-3.5 font-mono text-gray-300">₹ <?= number_format($log['balance_after'], 2) ?></td>
                            <td class="p-3.5 text-gray-500"><?= date('M d, Y h:i A', strtotime($log['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
