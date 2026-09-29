<?php
/**
 * Admin Sidebar Navigation
 */
$act = $activeAdminNav ?? 'dashboard';
?>
<aside class="w-64 bg-[#0D111A] border-r border-gray-800 p-4 space-y-6 hidden md:block shrink-0 min-h-[calc(100vh-60px)]">
    <!-- Main Menu -->
    <div class="space-y-1">
        <span class="text-[10px] font-black uppercase tracking-wider text-gray-500 px-3 block mb-1">Core Operations</span>
        <a href="/admin" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold <?= $act === 'dashboard' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' ?> transition">
            <i class="fa-solid fa-chart-pie w-4 text-center"></i> Dashboard
        </a>
        <a href="/admin/orders" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold <?= $act === 'orders' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' ?> transition">
            <i class="fa-solid fa-cart-shopping w-4 text-center"></i> Orders Management
        </a>
        <a href="/admin/services" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold <?= $act === 'services' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' ?> transition">
            <i class="fa-solid fa-gamepad w-4 text-center"></i> Catalog & Services
        </a>
        <a href="/admin/services/import" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold <?= $act === 'import' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' ?> transition">
            <i class="fa-solid fa-file-import w-4 text-center"></i> Service Import API
        </a>
        <a href="/admin/providers" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold <?= $act === 'providers' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' ?> transition">
            <i class="fa-solid fa-server w-4 text-center"></i> Provider APIs & Balance
        </a>
    </div>

    <!-- User & Financial Management -->
    <div class="space-y-1">
        <span class="text-[10px] font-black uppercase tracking-wider text-gray-500 px-3 block mb-1">Financial & Users</span>
        <a href="/admin/users" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold <?= $act === 'users' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' ?> transition">
            <i class="fa-solid fa-users w-4 text-center"></i> User Accounts
        </a>
        <a href="/admin/payments" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold <?= $act === 'payments' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' ?> transition">
            <i class="fa-solid fa-credit-card w-4 text-center"></i> Razorpay Payments
        </a>
        <a href="/admin/wallet" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold <?= $act === 'wallet' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' ?> transition">
            <i class="fa-solid fa-wallet w-4 text-center"></i> Wallet Transactions
        </a>
        <a href="/admin/coupons" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold <?= $act === 'coupons' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' ?> transition">
            <i class="fa-solid fa-ticket w-4 text-center"></i> Coupons & Discounts
        </a>
        <a href="/admin/referrals" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold <?= $act === 'referrals' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' ?> transition">
            <i class="fa-solid fa-user-plus w-4 text-center"></i> Referral Programs
        </a>
    </div>

    <!-- Communication & System -->
    <div class="space-y-1">
        <span class="text-[10px] font-black uppercase tracking-wider text-gray-500 px-3 block mb-1">Tools & Maintenance</span>
        <a href="/admin/support" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold <?= $act === 'support' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' ?> transition">
            <i class="fa-solid fa-headset w-4 text-center"></i> Support Tickets
        </a>
        <a href="/admin/notifications" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold <?= $act === 'notifications' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' ?> transition">
            <i class="fa-solid fa-bullhorn w-4 text-center"></i> Send Announcements
        </a>
        <a href="/admin/reports" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold <?= $act === 'reports' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' ?> transition">
            <i class="fa-solid fa-chart-line w-4 text-center"></i> Sales & Profit Reports
        </a>
        <a href="/admin/cron" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold <?= $act === 'cron' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' ?> transition">
            <i class="fa-solid fa-clock-rotate-left w-4 text-center"></i> Cron & Automations
        </a>
        <a href="/admin/settings" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold <?= $act === 'settings' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' ?> transition">
            <i class="fa-solid fa-sliders w-4 text-center"></i> Store Settings
        </a>
        <a href="/admin/logs" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold <?= $act === 'logs' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' ?> transition">
            <i class="fa-solid fa-list-check w-4 text-center"></i> Audit & API Logs
        </a>
    </div>

    <!-- Sign Out Button -->
    <div class="pt-4 border-t border-gray-800">
        <a href="/admin/logout" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-rose-400 hover:bg-rose-500/10 transition">
            <i class="fa-solid fa-power-off w-4 text-center"></i> Logout
        </a>
    </div>
</aside>
