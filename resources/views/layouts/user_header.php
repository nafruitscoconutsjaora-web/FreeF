<?php
/**
 * Logged-in User Header Layout
 * Strictly user navigation: Home, Services, Orders, Wallet, Referral, Coupons, Notifications, Support, Profile, Logout.
 * DO NOT SHOW ADMIN LINKS OR ADMIN SETTINGS.
 */
use App\Core\Database;

$userId = $_SESSION['user_id'] ?? null;
$currentUser = $_SESSION['user'] ?? ['name' => 'User', 'email' => ''];
$walletBal = 0.00;
$unreadNotifs = 0;

if ($userId) {
    try {
        $w = Database::fetch("SELECT balance FROM wallets WHERE user_id = ?", [$userId]);
        $walletBal = $w ? (float)$w['balance'] : 0.00;
        $n = Database::fetch("SELECT COUNT(*) as count FROM notifications WHERE (user_id = ? OR user_id IS NULL) AND is_read = 0", [$userId]);
        $unreadNotifs = $n ? (int)$n['count'] : 0;
    } catch (\Exception $e) {
        // Fallback gracefully
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'User Portal - FF Panel Store') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
        theme: {
          extend: {
            colors: {
              ff: {
                dark: '#080B11',
                card: '#0B0E14',
                surface: '#111723',
                accent: '#E11D48',
                accentHover: '#BE123C',
                pink: '#F43F5E',
              }
            }
          }
        }
      }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
      body { background-color: #080B11; color: #F3F4F6; font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; overflow-x: hidden; }
      .glow-border { box-shadow: 0 0 15px rgba(225, 29, 72, 0.15); }
    </style>
</head>
<body class="bg-[#080B11] text-gray-100 min-h-screen flex flex-col antialiased">

<!-- USER NAVBAR -->
<header class="bg-[#0B0E14] border-b border-gray-800/80 px-4 sm:px-6 lg:px-8 relative w-full">
    <div class="max-w-[1580px] mx-auto flex items-center justify-between gap-3 h-14 sm:h-16 lg:h-[68px]">
        <!-- Logo -->
        <a href="/dashboard" class="flex items-center gap-2.5 sm:gap-3 shrink-0">
            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-gradient-to-tr from-rose-600 via-rose-500 to-rose-400 flex items-center justify-center font-black text-white text-base sm:text-lg tracking-tighter shadow-md shadow-rose-600/30">
                FF
            </div>
            <div>
                <span class="text-sm sm:text-base font-black text-white tracking-wide block leading-tight">FF Panel Store</span>
                <span class="text-[10px] sm:text-[11px] text-gray-400 block font-medium">User Panel</span>
            </div>
        </a>

        <!-- User Center Navigation Links -->
        <nav class="hidden xl:flex items-center gap-1">
            <a href="/dashboard" class="h-8 sm:h-9 px-3.5 rounded-full text-xs font-semibold <?= ($activeNav ?? '') === 'dashboard' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/60' ?> transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-gauge text-[11px]"></i> Dashboard
            </a>
            <a href="/services" class="h-8 sm:h-9 px-3.5 rounded-full text-xs font-semibold <?= ($activeNav ?? '') === 'services' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/60' ?> transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-gamepad text-[11px]"></i> Services
            </a>
            <a href="/orders" class="h-8 sm:h-9 px-3.5 rounded-full text-xs font-semibold <?= ($activeNav ?? '') === 'orders' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/60' ?> transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-receipt text-[11px]"></i> Orders
            </a>
            <a href="/wallet" class="h-8 sm:h-9 px-3.5 rounded-full text-xs font-semibold <?= ($activeNav ?? '') === 'wallet' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/60' ?> transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-wallet text-[11px]"></i> Wallet
            </a>
            <a href="/referrals" class="h-8 sm:h-9 px-3.5 rounded-full text-xs font-semibold <?= ($activeNav ?? '') === 'referrals' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/60' ?> transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-user-plus text-[11px]"></i> Referral
            </a>
            <a href="/coupons" class="h-8 sm:h-9 px-3.5 rounded-full text-xs font-semibold <?= ($activeNav ?? '') === 'coupons' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/60' ?> transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-ticket text-[11px]"></i> Coupons
            </a>
            <a href="/support" class="h-8 sm:h-9 px-3.5 rounded-full text-xs font-semibold <?= ($activeNav ?? '') === 'support' ? 'bg-[#E11D48] text-white shadow' : 'text-gray-400 hover:text-white hover:bg-gray-800/60' ?> transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-headset text-[11px]"></i> Support
            </a>
        </nav>

        <!-- Right Side: Wallet, Notifications, Profile, Logout -->
        <div class="flex items-center gap-2 sm:gap-2.5">
            <!-- User Wallet Balance Badge -->
            <a href="/wallet" class="h-8 sm:h-9 flex items-center gap-1.5 sm:gap-2 bg-[#111723] border border-gray-800 rounded-xl px-2.5 sm:px-3 hover:border-rose-500/40 transition shrink-0">
                <i class="fa-solid fa-wallet text-rose-500 text-xs sm:text-sm"></i>
                <div class="text-left leading-none">
                    <span class="text-xs font-black text-white block">₹ <?= number_format($walletBal, 2) ?></span>
                    <span class="text-[9px] text-gray-400 hidden sm:block">Balance</span>
                </div>
            </a>

            <!-- Cart Button -->
            <a href="/cart" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-[#111723] border border-gray-800 flex items-center justify-center text-gray-300 hover:text-white relative transition shrink-0">
                <i class="fa-solid fa-cart-shopping text-xs"></i>
                <?php $cartCount = count($_SESSION['cart'] ?? []); if ($cartCount > 0): ?>
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-rose-600 rounded-full text-[9px] font-bold text-white flex items-center justify-center shadow"><?= $cartCount ?></span>
                <?php endif; ?>
            </a>

            <!-- Notifications Bell -->
            <a href="/notifications" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-[#111723] border border-gray-800 flex items-center justify-center text-gray-300 hover:text-white relative transition shrink-0">
                <i class="fa-regular fa-bell text-xs"></i>
                <?php if ($unreadNotifs > 0): ?>
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-rose-600 rounded-full text-[9px] font-bold text-white flex items-center justify-center shadow"><?= $unreadNotifs ?></span>
                <?php endif; ?>
            </a>

            <!-- Profile & Logout Dropdown -->
            <div class="flex items-center gap-1.5 sm:gap-2">
                <a href="/profile" class="flex items-center gap-2 pl-0.5" title="User Profile">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-rose-600 flex items-center justify-center text-white font-bold text-xs shadow-md shadow-rose-600/20 shrink-0">
                        <?= strtoupper(substr($currentUser['name'] ?? 'U', 0, 1)) ?>
                    </div>
                    <div class="hidden md:block text-left leading-tight">
                        <span class="text-xs font-bold text-white block truncate max-w-[85px]"><?= htmlspecialchars($currentUser['name'] ?? 'User') ?></span>
                        <span class="text-[9px] text-emerald-400">Online</span>
                    </div>
                </a>
                <a href="/logout" title="Sign Out" class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-[#111723] border border-gray-800 text-gray-400 hover:text-rose-400 hover:border-gray-700 flex items-center justify-center text-xs transition shrink-0">
                    <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                </a>
            </div>

            <!-- Mobile Hamburger Button -->
            <button type="button" id="mobileUserMenuBtn" aria-label="Toggle user menu" class="xl:hidden w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-[#111723] border border-gray-800 text-gray-300 hover:text-white flex items-center justify-center shrink-0">
                <i class="fa-solid fa-bars text-xs sm:text-sm"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div id="mobileUserMenu" class="hidden xl:hidden pt-3 pb-3 border-t border-gray-800/80 mt-1 space-y-1.5 animate-fadeIn">
        <a href="/dashboard" class="block px-3.5 py-2 rounded-xl text-xs font-semibold <?= ($activeNav ?? '') === 'dashboard' ? 'bg-rose-600 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800/40' ?> flex items-center gap-2">
            <i class="fa-solid fa-gauge w-4 text-center"></i> Dashboard Overview
        </a>
        <a href="/services" class="block px-3.5 py-2 rounded-xl text-xs font-semibold <?= ($activeNav ?? '') === 'services' ? 'bg-rose-600 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800/40' ?> flex items-center gap-2">
            <i class="fa-solid fa-gamepad w-4 text-center"></i> Store Catalog & Services
        </a>
        <a href="/orders" class="block px-3.5 py-2 rounded-xl text-xs font-semibold <?= ($activeNav ?? '') === 'orders' ? 'bg-rose-600 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800/40' ?> flex items-center gap-2">
            <i class="fa-solid fa-receipt w-4 text-center"></i> My Orders
        </a>
        <a href="/wallet" class="block px-3.5 py-2 rounded-xl text-xs font-semibold <?= ($activeNav ?? '') === 'wallet' ? 'bg-rose-600 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800/40' ?> flex items-center gap-2">
            <i class="fa-solid fa-wallet w-4 text-center"></i> Wallet & Add Funds
        </a>
        <a href="/referrals" class="block px-3.5 py-2 rounded-xl text-xs font-semibold <?= ($activeNav ?? '') === 'referrals' ? 'bg-rose-600 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800/40' ?> flex items-center gap-2">
            <i class="fa-solid fa-user-plus w-4 text-center"></i> Referral Program
        </a>
        <a href="/coupons" class="block px-3.5 py-2 rounded-xl text-xs font-semibold <?= ($activeNav ?? '') === 'coupons' ? 'bg-rose-600 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800/40' ?> flex items-center gap-2">
            <i class="fa-solid fa-ticket w-4 text-center"></i> Available Coupons
        </a>
        <a href="/support" class="block px-3.5 py-2 rounded-xl text-xs font-semibold <?= ($activeNav ?? '') === 'support' ? 'bg-rose-600 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800/40' ?> flex items-center gap-2">
            <i class="fa-solid fa-headset w-4 text-center"></i> Customer Support Tickets
        </a>
        <a href="/profile" class="block px-3.5 py-2 rounded-xl text-xs font-semibold <?= ($activeNav ?? '') === 'profile' ? 'bg-rose-600 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800/40' ?> flex items-center gap-2">
            <i class="fa-solid fa-user w-4 text-center"></i> My Profile & Security
        </a>
        <a href="/logout" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-rose-400 hover:bg-rose-500/10 flex items-center gap-2 pt-2 border-t border-gray-800/80">
            <i class="fa-solid fa-power-off w-4 text-center"></i> Sign Out
        </a>
    </div>
</header>

<script>
document.getElementById('mobileUserMenuBtn')?.addEventListener('click', () => {
    document.getElementById('mobileUserMenu')?.classList.toggle('hidden');
});
</script>
