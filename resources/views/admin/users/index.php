<?php
$pageTitle = "User Accounts - Admin Control Center";
$activeAdminNav = 'users';
require __DIR__ . '/../../layouts/admin_header.php';
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white">Registered Users</h1>
            <p class="text-xs text-gray-400 mt-1">Manage player profiles, account statuses, and audit wallet balances.</p>
        </div>

        <form action="/admin/users" method="GET" class="flex items-center gap-2">
            <input type="text" name="q" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Search Name, Email, Phone..."
                   class="bg-[#121824] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-500 w-64">
            <button type="submit" class="px-3.5 py-2 bg-rose-600 hover:bg-rose-500 text-white rounded-xl text-xs font-bold transition">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-[#0D111A] border border-gray-800 rounded-2xl overflow-hidden shadow-lg">
        <?php if (empty($users)): ?>
            <div class="p-12 text-center text-xs text-gray-500">
                No users match your query.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#121824] text-gray-400 uppercase text-[10px]">
                        <tr>
                            <th class="p-3.5">User</th>
                            <th class="p-3.5">Phone / Contact</th>
                            <th class="p-3.5">Wallet Balance</th>
                            <th class="p-3.5">Total Orders</th>
                            <th class="p-3.5">Total Spent</th>
                            <th class="p-3.5">Status</th>
                            <th class="p-3.5">Joined Date</th>
                            <th class="p-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        <?php foreach ($users as $u): ?>
                        <tr class="hover:bg-gray-800/30">
                            <td class="p-3.5">
                                <div class="font-bold text-white"><?= htmlspecialchars($u['name']) ?></div>
                                <div class="text-[10px] text-gray-400"><?= htmlspecialchars($u['email']) ?></div>
                            </td>
                            <td class="p-3.5 text-gray-300"><?= htmlspecialchars($u['phone'] ?: 'N/A') ?></td>
                            <td class="p-3.5 font-mono font-bold text-emerald-400">₹ <?= number_format($u['wallet_balance'], 2) ?></td>
                            <td class="p-3.5 font-bold text-white"><?= (int)$u['total_orders'] ?></td>
                            <td class="p-3.5 font-mono text-rose-400">₹ <?= number_format($u['total_spent'], 2) ?></td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold <?= $u['status'] === 'active' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' ?>">
                                    <?= ucfirst($u['status']) ?>
                                </span>
                            </td>
                            <td class="p-3.5 text-gray-500"><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                            <td class="p-3.5 text-right">
                                <a href="/admin/users/<?= $u['id'] ?>" class="px-3 py-1.5 rounded-lg bg-[#121824] hover:bg-gray-700 text-white font-semibold text-xs border border-gray-700 inline-block">
                                    Manage →
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

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
