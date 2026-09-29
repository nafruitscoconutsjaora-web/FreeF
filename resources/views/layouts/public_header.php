<?php
/**
 * Public Visitor Header Layout
 * ONLY public links: Home, Services, Categories, About, Contact, Login, Get Started
 * STRICTLY NO WALLET BALANCE, NO NOTIFICATIONS, NO USER AVATAR/PROFILE DROPDOWN, NO LOGOUT.
 */
$isLoggedIn = !empty($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'FF Panel Store - Free Fire Top Up & Game Services') ?></title>
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
      .glow-red { box-shadow: 0 0 20px rgba(225, 29, 72, 0.25); }
    </style>
</head>
<body class="bg-[#080B11] text-gray-100 min-h-screen flex flex-col antialiased">

<!-- PUBLIC NAVBAR -->
<header class="bg-[#0B0E14] border-b border-gray-800/80 px-4 lg:px-8 py-3.5 relative">
    <div class="max-w-[1580px] mx-auto flex items-center justify-between gap-4">
        <!-- Logo -->
        <a href="/" class="flex items-center gap-3 shrink-0">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-rose-600 via-rose-500 to-rose-400 flex items-center justify-center font-black text-white text-xl tracking-tighter shadow-lg shadow-rose-600/30">
                FF
            </div>
            <div>
                <span class="text-lg font-black text-white tracking-wide block leading-tight">FF Panel Store</span>
                <span class="text-[11px] text-gray-400 block font-medium">Fast • Safe • Reliable</span>
            </div>
        </a>

        <!-- Search Bar (Public Service Discovery) -->
        <div class="hidden lg:flex flex-1 max-w-sm relative">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
            <form action="/services" method="GET" class="w-full">
                <input type="text" name="q" placeholder="Search diamonds, pass, UID..." 
                       class="w-full bg-[#111723] border border-gray-800 rounded-full pl-9 pr-4 py-2 text-xs text-gray-200 placeholder-gray-500 focus:outline-none focus:border-rose-500 transition-colors">
            </form>
        </div>

        <!-- Public Navigation Links Only -->
        <nav class="hidden md:flex items-center gap-1.5">
            <a href="/" class="px-3.5 py-1.5 rounded-full text-xs font-semibold <?= ($activeNav ?? '') === 'home' ? 'bg-[#E11D48] text-white shadow-sm' : 'text-gray-300 hover:text-white hover:bg-gray-800/60' ?> transition">
                <i class="fa-solid fa-house mr-1 text-[11px]"></i> Home
            </a>
            <a href="/services" class="px-3.5 py-1.5 rounded-full text-xs font-semibold <?= ($activeNav ?? '') === 'services' ? 'bg-[#E11D48] text-white shadow-sm' : 'text-gray-300 hover:text-white hover:bg-gray-800/60' ?> transition">
                <i class="fa-solid fa-layer-group mr-1 text-[11px]"></i> Services
            </a>
            <a href="/services" class="px-3.5 py-1.5 rounded-full text-xs font-semibold text-gray-300 hover:text-white hover:bg-gray-800/60 transition">
                <i class="fa-solid fa-fire mr-1 text-[11px] text-rose-500"></i> Categories
            </a>
            <a href="/about" class="px-3.5 py-1.5 rounded-full text-xs font-semibold <?= ($activeNav ?? '') === 'about' ? 'bg-[#E11D48] text-white shadow-sm' : 'text-gray-300 hover:text-white hover:bg-gray-800/60' ?> transition">
                <i class="fa-solid fa-circle-info mr-1 text-[11px]"></i> About
            </a>
            <a href="/contact" class="px-3.5 py-1.5 rounded-full text-xs font-semibold <?= ($activeNav ?? '') === 'contact' ? 'bg-[#E11D48] text-white shadow-sm' : 'text-gray-300 hover:text-white hover:bg-gray-800/60' ?> transition">
                <i class="fa-solid fa-envelope mr-1 text-[11px]"></i> Contact
            </a>
        </nav>

        <!-- Right Side: Clean Public CTA -->
        <div class="flex items-center gap-2.5">
            <!-- Cart Button (Public) -->
            <a href="/cart" class="w-9 h-9 rounded-full bg-[#111723] border border-gray-800 flex items-center justify-center text-gray-300 hover:text-white hover:border-gray-700 transition relative">
                <i class="fa-solid fa-cart-shopping text-xs"></i>
                <?php if (!empty($_SESSION['cart'])): ?>
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-rose-600 rounded-full text-[9px] font-bold text-white flex items-center justify-center shadow">
                        <?= count($_SESSION['cart']) ?>
                    </span>
                <?php endif; ?>
            </a>

            <?php if ($isLoggedIn): ?>
                <!-- Clean Link to User Dashboard (NO wallet balance, NO notification count, NO profile dropdown on landing) -->
                <a href="/dashboard" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 hover:to-rose-400 transition shadow-md shadow-rose-600/30 flex items-center gap-1.5">
                    <i class="fa-solid fa-chart-pie text-xs"></i> My Dashboard
                </a>
            <?php else: ?>
                <!-- Logged Out Visitor Buttons -->
                <a href="/login" class="px-3.5 py-2 rounded-xl text-xs font-bold text-gray-200 hover:text-white bg-[#111723] border border-gray-800 hover:border-gray-700 transition">
                    Sign In
                </a>
                <a href="/register" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 hover:to-rose-400 transition shadow-md shadow-rose-600/30 flex items-center gap-1">
                    Get Started <i class="fa-solid fa-arrow-right text-[10px] ml-0.5"></i>
                </a>
            <?php endif; ?>

            <!-- Mobile Hamburger Button -->
            <button type="button" id="mobilePublicMenuBtn" class="md:hidden w-9 h-9 rounded-xl bg-[#111723] border border-gray-800 text-gray-300 hover:text-white flex items-center justify-center">
                <i class="fa-solid fa-bars text-sm"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Drawer (Normal document flow toggle) -->
    <div id="mobilePublicMenu" class="hidden md:hidden pt-4 pb-2 border-t border-gray-800/80 mt-3 space-y-2">
        <a href="/" class="block px-3 py-2 rounded-xl text-xs font-semibold text-white bg-rose-600/20 text-rose-400">Home</a>
        <a href="/services" class="block px-3 py-2 rounded-xl text-xs font-semibold text-gray-300 hover:text-white hover:bg-gray-800/40">Services</a>
        <a href="/services" class="block px-3 py-2 rounded-xl text-xs font-semibold text-gray-300 hover:text-white hover:bg-gray-800/40">Categories</a>
        <a href="/about" class="block px-3 py-2 rounded-xl text-xs font-semibold text-gray-300 hover:text-white hover:bg-gray-800/40">About Us</a>
        <a href="/contact" class="block px-3 py-2 rounded-xl text-xs font-semibold text-gray-300 hover:text-white hover:bg-gray-800/40">Contact Support</a>
        <?php if ($isLoggedIn): ?>
            <a href="/dashboard" class="block px-3 py-2 rounded-xl text-xs font-bold text-white bg-rose-600 mt-2">Go to My Dashboard →</a>
        <?php else: ?>
            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-gray-800">
                <a href="/login" class="text-center py-2 rounded-xl bg-[#111723] border border-gray-800 text-xs font-bold text-white">Sign In</a>
                <a href="/register" class="text-center py-2 rounded-xl bg-rose-600 text-xs font-bold text-white">Get Started</a>
            </div>
        <?php endif; ?>
    </div>
</header>

<script>
document.getElementById('mobilePublicMenuBtn')?.addEventListener('click', () => {
    document.getElementById('mobilePublicMenu')?.classList.toggle('hidden');
});
</script>
