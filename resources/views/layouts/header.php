<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'FF Panel Store - Free Fire Digital Store & Services') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
        theme: {
          extend: {
            colors: {
              ff: {
                dark: '#0B0E14',
                card: '#111723',
                cardBorder: '#1F2937',
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
      body { background-color: #080B11; color: #F3F4F6; font-family: system-ui, -apple-system, sans-serif; }
      .glow-border { box-shadow: 0 0 15px rgba(225, 29, 72, 0.15); }
    </style>
</head>
<body class="bg-[#080B11] text-gray-100 min-h-screen flex flex-col">

<!-- Top Navigation Bar -->
<header class="bg-[#0B0E14] border-b border-gray-800 px-4 sm:px-6 lg:px-8 relative w-full">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-3 h-14 sm:h-16 lg:h-[68px]">
        <!-- Logo -->
        <a href="/" class="flex items-center gap-2.5 sm:gap-3 shrink-0">
            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-gradient-to-tr from-rose-600 to-rose-400 flex items-center justify-center font-black text-white text-base sm:text-lg tracking-tighter shadow-md shadow-rose-600/30">
                FF
            </div>
            <div>
                <span class="text-sm sm:text-base font-bold text-white tracking-wide block leading-tight">FF Panel Store</span>
                <span class="text-[10px] sm:text-xs text-gray-400 block">Fast • Safe • Reliable</span>
            </div>
        </a>

        <!-- Search Bar -->
        <div class="hidden md:flex flex-1 max-w-sm relative">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
            <input type="text" placeholder="Search for services (e.g. Diamond, UID, ID, etc...)" 
                   class="w-full h-9 bg-[#111723] border border-gray-800 rounded-full pl-9 pr-4 text-xs text-gray-200 placeholder-gray-500 focus:outline-none focus:border-rose-500 transition-colors">
        </div>

        <!-- Center Nav Links -->
        <nav class="hidden xl:flex items-center gap-1.5">
            <a href="/" class="h-8 sm:h-9 px-3.5 rounded-full text-xs font-semibold bg-[#E11D48] text-white inline-flex items-center gap-1.5 shadow-sm">
                <i class="fa-solid fa-house text-xs"></i> Home
            </a>
            <a href="/services" class="px-4 py-1.5 rounded-full text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800/60 transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-layer-group text-xs"></i> Services
            </a>
            <a href="/orders" class="px-4 py-1.5 rounded-full text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800/60 transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-receipt text-xs"></i> Orders
            </a>
            <a href="/wallet" class="px-4 py-1.5 rounded-full text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800/60 transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-wallet text-xs"></i> Wallet
            </a>
            <a href="/referrals" class="px-4 py-1.5 rounded-full text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800/60 transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-user-plus text-xs"></i> Referral
            </a>
            <a href="/support" class="px-4 py-1.5 rounded-full text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800/60 transition-colors flex items-center gap-1.5">
                <i class="fa-regular fa-comment-dots text-xs"></i> Support
            </a>
        </nav>

        <!-- Right User / Wallet Area -->
        <div class="flex items-center gap-3">
            <!-- Notifications Bell -->
            <button class="w-9 h-9 rounded-full bg-[#111723] border border-gray-800 flex items-center justify-center text-gray-400 hover:text-white relative">
                <i class="fa-regular fa-bell text-sm"></i>
                <span class="absolute -top-1 -right-1 w-4 h-4 bg-rose-600 rounded-full text-[10px] font-bold text-white flex items-center justify-center">3</span>
            </button>

            <!-- Wallet Widget -->
            <a href="/wallet" class="hidden sm:flex items-center gap-2.5 bg-[#111723] border border-gray-800 rounded-xl px-3 py-1.5 hover:border-gray-700 transition">
                <i class="fa-solid fa-wallet text-rose-500 text-sm"></i>
                <div class="text-left leading-none">
                    <span class="text-xs font-bold text-white block">₹ <?= number_format($walletBalance ?? 0.00, 2) ?></span>
                    <span class="text-[10px] text-gray-400">Wallet Balance</span>
                </div>
            </a>

            <!-- User Profile / Auth Button -->
            <?php if (!empty($_SESSION['user'])): ?>
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-full bg-rose-600 text-white flex items-center justify-center font-bold text-sm">
                        <?= strtoupper(substr($_SESSION['user']['name'], 0, 1)) ?>
                    </div>
                    <div class="hidden sm:block text-left leading-tight">
                        <span class="text-xs font-semibold text-white block truncate max-w-[100px]"><?= htmlspecialchars($_SESSION['user']['name']) ?></span>
                        <span class="text-[10px] text-gray-400">User</span>
                    </div>
                    <a href="/logout" title="Logout" class="text-gray-400 hover:text-rose-400 text-xs ml-1">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </a>
                </div>
            <?php else: ?>
                <a href="/login" class="px-4 py-1.5 rounded-full text-xs font-bold bg-[#E11D48] text-white hover:bg-rose-700 transition shadow">
                    Login
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>
