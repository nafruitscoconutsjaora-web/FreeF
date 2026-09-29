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
<header class="bg-[#0B0E14] border-b border-gray-800/80 px-4 sm:px-6 lg:px-8 relative w-full">
    <div class="max-w-[1580px] mx-auto flex items-center justify-between gap-3 h-14 sm:h-16 lg:h-[68px]">
        <!-- Logo -->
        <a href="/" class="flex items-center gap-2.5 sm:gap-3 shrink-0">
            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-gradient-to-tr from-rose-600 via-rose-500 to-rose-400 flex items-center justify-center font-black text-white text-base sm:text-lg tracking-tighter shadow-md shadow-rose-600/30">
                FF
            </div>
            <div>
                <span class="text-sm sm:text-base font-black text-white tracking-wide block leading-tight">FF Panel Store</span>
                <span class="text-[10px] sm:text-[11px] text-gray-400 block font-medium">Fast • Safe • Reliable</span>
            </div>
        </a>

        <!-- Search Bar (Public Service Discovery) -->
        <div class="hidden lg:flex flex-1 max-w-sm relative">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
            <form action="/services" method="GET" class="w-full">
                <input type="text" name="q" placeholder="Search diamonds, pass, UID..." 
                       class="w-full h-9 bg-[#111723] border border-gray-800 rounded-full pl-9 pr-4 text-xs text-gray-200 placeholder-gray-500 focus:outline-none focus:border-rose-500 transition-colors">
            </form>
        </div>

        <!-- Public Navigation Links Only -->
        <nav class="hidden md:flex items-center gap-1">
            <a href="/" class="h-8 sm:h-9 px-3.5 rounded-full text-xs font-semibold inline-flex items-center gap-1.5 <?= ($activeNav ?? '') === 'home' ? 'bg-[#E11D48] text-white shadow-sm' : 'text-gray-300 hover:text-white hover:bg-gray-800/60' ?> transition">
                <i class="fa-solid fa-house text-[11px]"></i> Home
            </a>
            <a href="/services" class="h-8 sm:h-9 px-3.5 rounded-full text-xs font-semibold inline-flex items-center gap-1.5 <?= ($activeNav ?? '') === 'services' ? 'bg-[#E11D48] text-white shadow-sm' : 'text-gray-300 hover:text-white hover:bg-gray-800/60' ?> transition">
                <i class="fa-solid fa-layer-group text-[11px]"></i> Services
            </a>
            <a href="/services" class="h-8 sm:h-9 px-3.5 rounded-full text-xs font-semibold inline-flex items-center gap-1.5 text-gray-300 hover:text-white hover:bg-gray-800/60 transition">
                <i class="fa-solid fa-fire text-rose-500 text-[11px]"></i> Categories
            </a>
            <a href="/about" class="h-8 sm:h-9 px-3.5 rounded-full text-xs font-semibold inline-flex items-center gap-1.5 <?= ($activeNav ?? '') === 'about' ? 'bg-[#E11D48] text-white shadow-sm' : 'text-gray-300 hover:text-white hover:bg-gray-800/60' ?> transition">
                <i class="fa-solid fa-circle-info text-[11px]"></i> About
            </a>
            <a href="/contact" class="h-8 sm:h-9 px-3.5 rounded-full text-xs font-semibold inline-flex items-center gap-1.5 <?= ($activeNav ?? '') === 'contact' ? 'bg-[#E11D48] text-white shadow-sm' : 'text-gray-300 hover:text-white hover:bg-gray-800/60' ?> transition">
                <i class="fa-solid fa-envelope text-[11px]"></i> Contact
            </a>
            <a href="/install" class="h-8 sm:h-9 px-3 rounded-full text-xs font-medium inline-flex items-center gap-1 text-gray-400 hover:text-rose-400 hover:bg-gray-800/40 transition" title="Web Installer">
                <i class="fa-solid fa-screwdriver-wrench text-[10px]"></i> Installer
            </a>
        </nav>

        <!-- Right Side: Clean Public CTA -->
        <div class="flex items-center gap-2 sm:gap-2.5">
            <!-- Cart Button (Public) -->
            <a href="/cart" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-[#111723] border border-gray-800 flex items-center justify-center text-gray-300 hover:text-white hover:border-gray-700 transition relative shrink-0">
                <i class="fa-solid fa-cart-shopping text-xs"></i>
                <?php if (!empty($_SESSION['cart'])): ?>
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-rose-600 rounded-full text-[9px] font-bold text-white flex items-center justify-center shadow">
                        <?= count($_SESSION['cart']) ?>
                    </span>
                <?php endif; ?>
            </a>

            <?php if ($isLoggedIn): ?>
                <!-- Clean Link to User Dashboard (NO wallet balance, NO notification count, NO profile dropdown on landing) -->
                <a href="/dashboard" class="h-8 sm:h-9 px-3.5 sm:px-4 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 hover:to-rose-400 transition shadow-md shadow-rose-600/30 inline-flex items-center gap-1.5 shrink-0">
                    <i class="fa-solid fa-chart-pie text-xs"></i> <span>Dashboard</span>
                </a>
            <?php else: ?>
                <!-- Logged Out Visitor Buttons -->
                <a href="/login" class="h-8 sm:h-9 px-3 sm:px-3.5 rounded-xl text-xs font-bold text-gray-200 hover:text-white bg-[#111723] border border-gray-800 hover:border-gray-700 transition inline-flex items-center shrink-0">
                    Sign In
                </a>
                <a href="/register" class="h-8 sm:h-9 px-3 sm:px-4 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 hover:to-rose-400 transition shadow-md shadow-rose-600/30 inline-flex items-center gap-1 shrink-0">
                    <span>Get Started</span> <i class="fa-solid fa-arrow-right text-[10px] ml-0.5 hidden xs:inline"></i>
                </a>
            <?php endif; ?>

            <!-- Mobile Hamburger Button -->
            <button type="button" id="mobilePublicMenuBtn" aria-label="Toggle menu" class="md:hidden w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-[#111723] border border-gray-800 text-gray-300 hover:text-white flex items-center justify-center shrink-0">
                <i class="fa-solid fa-bars text-xs sm:text-sm"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Drawer (Normal document flow toggle) -->
    <div id="mobilePublicMenu" class="hidden md:hidden pt-3 pb-3 border-t border-gray-800/80 mt-1 space-y-1.5 animate-fadeIn">
        <a href="/" class="block px-3.5 py-2 rounded-xl text-xs font-semibold <?= ($activeNav ?? '') === 'home' ? 'bg-rose-600 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800/40' ?>">Home</a>
        <a href="/services" class="block px-3.5 py-2 rounded-xl text-xs font-semibold <?= ($activeNav ?? '') === 'services' ? 'bg-rose-600 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800/40' ?>">Services & Diamond Top-Ups</a>
        <a href="/services" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-gray-300 hover:text-white hover:bg-gray-800/40">Categories</a>
        <a href="/about" class="block px-3.5 py-2 rounded-xl text-xs font-semibold <?= ($activeNav ?? '') === 'about' ? 'bg-rose-600 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800/40' ?>">About Store</a>
        <a href="/contact" class="block px-3.5 py-2 rounded-xl text-xs font-semibold <?= ($activeNav ?? '') === 'contact' ? 'bg-rose-600 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800/40' ?>">Contact & 24/7 Support</a>
        <a href="/install" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-rose-400 hover:text-white hover:bg-gray-800/40">Web Installer (/install)</a>
        <?php if ($isLoggedIn): ?>
            <a href="/dashboard" class="block px-3.5 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-rose-600 to-rose-500 mt-2 text-center">Open My Dashboard →</a>
        <?php else: ?>
            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-gray-800/80">
                <a href="/login" class="text-center py-2 rounded-xl bg-[#111723] border border-gray-800 text-xs font-bold text-white">Sign In</a>
                <a href="/register" class="text-center py-2 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 text-xs font-bold text-white">Get Started</a>
            </div>
        <?php endif; ?>
    </div>
</header>

<script>
document.getElementById('mobilePublicMenuBtn')?.addEventListener('click', () => {
    document.getElementById('mobilePublicMenu')?.classList.toggle('hidden');
});
</script>
