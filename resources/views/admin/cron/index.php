<?php
$pageTitle = "Cron & Automations - Admin Control Center";
$activeAdminNav = 'cron';
require __DIR__ . '/../../layouts/admin_header.php';
?>

<div class="space-y-6 max-w-4xl mx-auto">
    <div>
        <h1 class="text-2xl font-black text-white">Cron & Background Automations</h1>
        <p class="text-xs text-gray-400 mt-1">Status of scheduled tasks: automatic order fulfillment, provider balance checks, and status sync.</p>
    </div>

    <!-- Active Tasks Card -->
    <div class="bg-[#0D111A] border border-gray-800 rounded-3xl p-6 space-y-4">
        <h3 class="text-sm font-bold text-white uppercase tracking-wider">Scheduled Tasks Status</h3>

        <div class="space-y-3">
            <div class="p-4 bg-[#121824] rounded-2xl border border-gray-800 flex items-center justify-between">
                <div>
                    <div class="font-bold text-white text-xs">cron/sync_orders.php</div>
                    <p class="text-[11px] text-gray-400 mt-0.5">Polls provider API for pending/processing order statuses every 1 minute.</p>
                </div>
                <div class="text-right">
                    <span class="text-xs font-mono font-bold text-rose-400"><?= (int)($pendingSyncOrders ?? 0) ?> orders awaiting sync</span>
                    <span class="block text-[10px] text-emerald-400">● Configured (Every 1m)</span>
                </div>
            </div>

            <div class="p-4 bg-[#121824] rounded-2xl border border-gray-800 flex items-center justify-between">
                <div>
                    <div class="font-bold text-white text-xs">cron/check_balance.php</div>
                    <p class="text-[11px] text-gray-400 mt-0.5">Queries upstream API suppliers and updates active balance caches every 15 minutes.</p>
                </div>
                <div class="text-right">
                    <span class="text-xs font-mono font-bold text-white"><?= count($providers ?? []) ?> active suppliers</span>
                    <span class="block text-[10px] text-emerald-400">● Configured (Every 15m)</span>
                </div>
            </div>

            <div class="p-4 bg-[#121824] rounded-2xl border border-gray-800 flex items-center justify-between">
                <div>
                    <div class="font-bold text-white text-xs">cron/sync_services.php</div>
                    <p class="text-[11px] text-gray-400 mt-0.5">Syncs supplier rate changes and updates customer pricing with markup daily.</p>
                </div>
                <div class="text-right">
                    <span class="block text-[10px] text-emerald-400">● Configured (Daily midnight)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- cPanel / Server Crontab Instructions -->
    <div class="bg-[#0D111A] border border-gray-800 rounded-3xl p-6 space-y-3">
        <h3 class="text-sm font-bold text-white uppercase tracking-wider">Server Crontab Setup Command</h3>
        <p class="text-xs text-gray-400">Add the following crontab entry to your VPS or cPanel Cron Jobs interface:</p>
        <div class="p-4 bg-[#080B11] border border-gray-850 rounded-xl font-mono text-xs text-rose-400 select-all">
            * * * * * php /path-to-ff-store/cron/sync_orders.php >> /dev/null 2>&1
        </div>
    </div>
</div>

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
