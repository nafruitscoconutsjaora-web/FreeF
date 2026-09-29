<?php
$pageTitle = "Dashboard - Admin Control Center";
$activeAdminNav = 'dashboard';
require __DIR__ . '/../layouts/admin_header.php';
?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-white">System Overview</h1>
            <p class="text-xs text-gray-400 mt-1">Live metrics from MySQL database and connected top-up provider gateways.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="/admin/services/import" class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs flex items-center gap-1.5 shadow">
                <i class="fa-solid fa-cloud-arrow-down text-xs"></i> Import Services
            </a>
            <a href="/admin/cron" class="px-3.5 py-2 rounded-xl bg-[#121824] border border-gray-800 hover:border-gray-700 text-gray-200 text-xs font-semibold flex items-center gap-1.5">
                <i class="fa-solid fa-clock-rotate-left text-cyan-400"></i> Cron Sync
            </a>
        </div>
    </div>

    <!-- SQL Stat Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-[#0D111A] border border-gray-800 rounded-2xl p-5 space-y-1">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Gross Sales (INR)</span>
            <div class="text-2xl font-black text-emerald-400">₹ <?= number_format($totalSales ?? 0.00, 2) ?></div>
            <span class="text-[11px] text-gray-500 block pt-1">Verified paid orders</span>
        </div>

        <div class="bg-[#0D111A] border border-gray-800 rounded-2xl p-5 space-y-1">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Orders</span>
            <div class="text-2xl font-black text-white"><?= (int)($totalOrders ?? 0) ?></div>
            <span class="text-[11px] text-gray-500 block pt-1">Pending: <?= (int)($pendingOrders ?? 0) ?> • Done: <?= (int)($completedOrders ?? 0) ?></span>
        </div>

        <div class="bg-[#0D111A] border border-gray-800 rounded-2xl p-5 space-y-1">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Registered Players</span>
            <div class="text-2xl font-black text-cyan-400"><?= (int)($totalUsers ?? 0) ?></div>
            <a href="/admin/users" class="text-[11px] text-rose-400 hover:underline block pt-1">Manage Users →</a>
        </div>

        <div class="bg-[#0D111A] border border-gray-800 rounded-2xl p-5 space-y-1">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Primary Provider Balance</span>
            <div class="text-2xl font-black text-yellow-400">
                <?= !empty($providers) ? '$ ' . number_format($providers[0]['balance'] ?? 0.0, 2) . ' ' . $providers[0]['currency'] : '$ 0.00' ?>
            </div>
            <a href="/admin/providers" class="text-[11px] text-gray-400 hover:underline block pt-1">View Providers →</a>
        </div>
    </div>

    <!-- Provider Connections & Recent Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Orders Table (2 cols) -->
        <div class="lg:col-span-2 bg-[#0D111A] border border-gray-800 rounded-2xl p-5 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-white text-sm">Recent Store Orders</h3>
                <a href="/admin/orders" class="text-xs text-rose-500 hover:underline">View All Orders →</a>
            </div>

            <?php if (empty($recentOrders)): ?>
                <div class="py-12 text-center text-xs text-gray-500">
                    No orders recorded in database yet.
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="text-gray-400 uppercase text-[10px] border-b border-gray-800 pb-2">
                            <tr>
                                <th class="pb-2">Order ID</th>
                                <th class="pb-2">Customer</th>
                                <th class="pb-2">Product</th>
                                <th class="pb-2">Amount</th>
                                <th class="pb-2">Status</th>
                                <th class="pb-2 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            <?php foreach ($recentOrders as $ro): ?>
                            <tr class="hover:bg-gray-800/30">
                                <td class="py-3 font-mono font-bold text-rose-400">#<?= htmlspecialchars($ro['order_number']) ?></td>
                                <td class="py-3 text-white"><?= htmlspecialchars($ro['user_name'] ?? 'Player') ?></td>
                                <td class="py-3 text-gray-300"><?= htmlspecialchars($ro['product_name']) ?></td>
                                <td class="py-3 font-bold text-white">₹ <?= number_format($ro['total_amount'], 2) ?></td>
                                <td class="py-3">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase
                                        <?= $ro['status'] === 'completed' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-400 border border-amber-500/30' ?>">
                                        <?= htmlspecialchars($ro['status']) ?>
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <a href="/admin/orders?q=<?= urlencode($ro['order_number']) ?>" class="px-2.5 py-1 rounded bg-[#121824] hover:bg-gray-700 text-gray-200 text-[11px] font-semibold">
                                        Manage
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Provider Status Card (1 col) -->
        <div class="bg-[#0D111A] border border-gray-800 rounded-2xl p-5 space-y-4">
            <h3 class="font-bold text-white text-sm">Provider API Integrations</h3>

            <?php if (empty($providers)): ?>
                <div class="py-8 text-center text-xs text-gray-500">
                    No active providers configured. <a href="/admin/providers" class="text-rose-400 hover:underline">Add Provider</a>
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($providers as $prov): ?>
                    <div class="bg-[#121824] p-3.5 rounded-xl border border-gray-800 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-white text-xs"><?= htmlspecialchars($prov['name']) ?></span>
                            <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Active</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-400">Account Balance:</span>
                            <span class="font-bold text-cyan-400"><?= number_format($prov['balance'], 2) ?> <?= htmlspecialchars($prov['currency']) ?></span>
                        </div>
                        <div class="text-[10px] text-gray-500">
                            Last checked: <?= $prov['last_balance_check'] ? date('d M, h:i A', strtotime($prov['last_balance_check'])) : 'Pending check' ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="border-t border-gray-800 pt-3">
                <a href="/admin/providers" class="text-xs text-rose-500 hover:underline font-semibold block text-center">
                    Provider Settings & API Keys →
                </a>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/admin_footer.php'; ?>
