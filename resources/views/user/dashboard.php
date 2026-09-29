<?php
$pageTitle = "My Dashboard - FF Panel Store";
$activeNav = 'dashboard';
require __DIR__ . '/../layouts/user_header.php';
?>

<div class="max-w-[1580px] mx-auto px-4 lg:px-8 py-8 space-y-8">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-[#1C0D1B] via-[#141221] to-[#0D1424] border border-rose-900/30 rounded-3xl p-6 sm:p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl">
        <div class="space-y-2">
            <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30 uppercase tracking-wider">
                User Portal
            </span>
            <h1 class="text-2xl sm:text-3xl font-black text-white">
                Welcome back, <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Player') ?>!
            </h1>
            <p class="text-xs text-gray-300">
                Manage your Free Fire instant top-ups, track order progress, and top up your wallet.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="/wallet" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs flex items-center gap-2 shadow-lg shadow-rose-600/30 transition">
                <i class="fa-solid fa-wallet"></i> Top Up Wallet
            </a>
            <a href="/services" class="px-5 py-2.5 rounded-xl bg-[#111723] hover:bg-gray-800 border border-gray-800 text-gray-200 text-xs font-semibold flex items-center gap-2 transition">
                <i class="fa-solid fa-gamepad text-rose-500"></i> Browse Services
            </a>
        </div>
    </div>

    <!-- Real Database Metric Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-[#0B0E14] border border-gray-800 rounded-2xl p-5 space-y-1">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Wallet Balance</span>
            <div class="text-2xl font-black text-white">₹ <?= number_format($stats['walletBalance'] ?? 0.00, 2) ?></div>
            <a href="/wallet" class="text-[11px] text-rose-400 hover:underline block pt-1">Add Balance →</a>
        </div>

        <div class="bg-[#0B0E14] border border-gray-800 rounded-2xl p-5 space-y-1">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Orders</span>
            <div class="text-2xl font-black text-white"><?= (int)($stats['totalOrders'] ?? 0) ?></div>
            <a href="/orders" class="text-[11px] text-gray-400 hover:underline block pt-1">View Order History →</a>
        </div>

        <div class="bg-[#0B0E14] border border-gray-800 rounded-2xl p-5 space-y-1">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Pending Orders</span>
            <div class="text-2xl font-black text-amber-400"><?= (int)($stats['pendingOrders'] ?? 0) ?></div>
            <span class="text-[11px] text-gray-500 block pt-1">In fulfillment queue</span>
        </div>

        <div class="bg-[#0B0E14] border border-gray-800 rounded-2xl p-5 space-y-1">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Completed Orders</span>
            <div class="text-2xl font-black text-emerald-400"><?= (int)($stats['completedOrders'] ?? 0) ?></div>
            <span class="text-[11px] text-gray-500 block pt-1">Instant delivery verified</span>
        </div>
    </div>

    <!-- Two Column: Recent Orders & Quick Recharge -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <!-- Recent Orders (2 cols) -->
        <div class="lg:col-span-2 bg-[#0B0E14] border border-gray-800 rounded-3xl p-6 space-y-4 shadow-md">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-white text-base">Recent Orders</h3>
                <a href="/orders" class="text-xs font-semibold text-rose-500 hover:underline">View All Orders →</a>
            </div>

            <?php if (empty($recentOrders)): ?>
                <div class="py-12 text-center text-gray-500 text-xs">
                    No orders placed yet. <a href="/services" class="text-rose-400 hover:underline font-bold">Browse services</a> to top up your first diamonds.
                </div>
            <?php else: ?>
                <div class="divide-y divide-gray-800">
                    <?php foreach ($recentOrders as $order): ?>
                    <div class="py-3.5 flex items-center justify-between gap-4">
                        <div class="space-y-0.5">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-white text-xs">#<?= htmlspecialchars($order['order_number']) ?></span>
                                <span class="text-[9px] font-bold uppercase px-2 py-0.5 rounded-full
                                    <?= $order['status'] === 'completed' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-amber-500/20 text-amber-400' ?>">
                                    <?= htmlspecialchars($order['status']) ?>
                                </span>
                            </div>
                            <span class="text-xs text-gray-300 block"><?= htmlspecialchars($order['product_name']) ?></span>
                            <span class="text-[10px] text-gray-500">UID: <span class="font-mono"><?= htmlspecialchars($order['customer_ff_uid']) ?></span> • <?= date('d M, h:i A', strtotime($order['created_at'])) ?></span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-black text-white block">₹ <?= number_format($order['total_amount'], 2) ?></span>
                            <a href="/orders/<?= urlencode($order['order_number']) ?>" class="text-[11px] text-rose-400 hover:underline">Details</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Quick Recharge & Referral Link Widget -->
        <div class="space-y-6">
            <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-6 space-y-4 shadow-md">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-rose-500"></i>
                    <h3 class="font-bold text-white text-sm">Quick Recharge</h3>
                </div>

                <form action="/quick-recharge" method="POST" class="space-y-3">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Free Fire UID</label>
                        <input type="text" name="uid" required placeholder="Player UID" value="<?= htmlspecialchars($_SESSION['user']['ff_uid'] ?? '') ?>"
                               class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-rose-500 font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Select Diamonds Pack</label>
                        <select name="product_id" required class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-rose-500">
                            <?php foreach ($availableRecharges ?? [] as $r): ?>
                                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?> - ₹ <?= number_format($r['price'], 2) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs shadow-md transition">
                        Recharge via Wallet
                    </button>
                </form>
            </div>

            <!-- Referral Card -->
            <div class="rounded-3xl p-6 bg-gradient-to-br from-[#2D101E] to-[#120B15] border border-rose-900/40 text-center space-y-3">
                <div class="w-10 h-10 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center font-bold text-base mx-auto">
                    <i class="fa-solid fa-crown"></i>
                </div>
                <h4 class="font-black text-white text-sm">Earn 5% on Every Referral</h4>
                <p class="text-xs text-gray-400">Share your referral link with guildmates and earn wallet balance whenever they recharge.</p>
                <a href="/referrals" class="inline-block w-full py-2 px-4 rounded-xl bg-gray-900/80 hover:bg-gray-800 border border-rose-800/40 text-white font-bold text-xs transition">
                    View Referral Center →
                </a>
            </div>
        </div>

    </div>
</div>

<?php require __DIR__ . '/../layouts/user_footer.php'; ?>
