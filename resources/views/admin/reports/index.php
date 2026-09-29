<?php
$pageTitle = "Sales & Profit Reports - Admin Control Center";
$activeAdminNav = 'reports';
require __DIR__ . '/../../layouts/admin_header.php';
?>

<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-black text-white">Sales & Revenue Reports</h1>
        <p class="text-xs text-gray-400 mt-1">Aggregated statistics calculated directly from verified database records.</p>
    </div>

    <!-- 2 Column Reports Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Daily Sales Breakdown -->
        <div class="bg-[#0D111A] border border-gray-800 rounded-3xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider">Daily Sales (Last 30 Days)</h3>
            
            <?php if (empty($dailySales)): ?>
                <div class="p-8 text-center text-xs text-gray-500">No verified sales in this period.</div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-[#121824] text-gray-400 uppercase text-[10px]">
                            <tr>
                                <th class="p-3">Date</th>
                                <th class="p-3">Orders</th>
                                <th class="p-3 text-right">Revenue (INR)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800 font-mono">
                            <?php foreach ($dailySales as $day): ?>
                            <tr class="hover:bg-gray-800/30">
                                <td class="p-3 text-white"><?= date('M d, Y', strtotime($day['sale_date'])) ?></td>
                                <td class="p-3 text-gray-400"><?= $day['count'] ?> orders</td>
                                <td class="p-3 text-right font-bold text-emerald-400">₹ <?= number_format($day['amount'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Top Selling Packages -->
        <div class="bg-[#0D111A] border border-gray-800 rounded-3xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider">Top Selling Free Fire Packages</h3>
            
            <?php if (empty($popularProducts)): ?>
                <div class="p-8 text-center text-xs text-gray-500">No product sales recorded yet.</div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-[#121824] text-gray-400 uppercase text-[10px]">
                            <tr>
                                <th class="p-3">Service</th>
                                <th class="p-3">Sold Qty</th>
                                <th class="p-3 text-right">Gross Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            <?php foreach ($popularProducts as $prod): ?>
                            <tr class="hover:bg-gray-800/30">
                                <td class="p-3 font-semibold text-white"><?= htmlspecialchars($prod['name']) ?></td>
                                <td class="p-3 font-mono text-gray-400"><?= $prod['order_count'] ?> sales</td>
                                <td class="p-3 text-right font-mono font-bold text-rose-400">₹ <?= number_format($prod['total_volume'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
