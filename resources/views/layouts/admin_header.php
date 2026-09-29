<?php
/**
 * Admin Panel Header Layout
 * Strictly Admin Navigation: Completely separated from Public and User panels.
 */
$adminUser = $_SESSION['admin'] ?? ['name' => 'Admin', 'role' => 'super_admin'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin Control Center - FF Panel Store') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
        theme: {
          extend: {
            colors: {
              ff: {
                dark: '#070A0F',
                card: '#0D111A',
                surface: '#121824',
                accent: '#E11D48',
                accentHover: '#BE123C',
              }
            }
          }
        }
      }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/css/app.css">
    <style>
      body { background-color: #070A0F; color: #F3F4F6; font-family: system-ui, -apple-system, sans-serif; overflow-x: hidden; }
      ::-webkit-scrollbar { width: 5px; height: 5px; }
      ::-webkit-scrollbar-thumb { background: #1F2937; border-radius: 9999px; }
    </style>
</head>
<body class="bg-[#070A0F] text-gray-100 min-h-screen flex flex-col antialiased">

<!-- Admin Top Navigation Bar -->
<header class="bg-[#0D111A] border-b border-gray-800 px-4 sm:px-6 lg:px-8 relative w-full">
    <div class="flex items-center justify-between gap-3 h-14 sm:h-16">
        <!-- Brand & Badge -->
        <div class="flex items-center gap-2.5 sm:gap-3">
            <!-- Mobile Sidebar Toggle -->
            <button type="button" id="adminMobileSidebarBtn" aria-label="Toggle admin sidebar" class="md:hidden w-8 h-8 rounded-xl bg-[#121824] border border-gray-800 text-gray-300 hover:text-white flex items-center justify-center shrink-0">
                <i class="fa-solid fa-bars text-xs"></i>
            </button>

            <a href="/admin" class="flex items-center gap-2.5 shrink-0">
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-gradient-to-tr from-rose-600 to-rose-400 flex items-center justify-center font-black text-white text-sm sm:text-base shadow-md shadow-rose-600/30">
                    <i class="fa-solid fa-shield-halved text-xs sm:text-sm"></i>
                </div>
                <div>
                    <span class="text-sm sm:text-base font-black text-white tracking-wide block leading-tight">FF Panel Admin</span>
                    <span class="text-[9px] sm:text-[10px] text-rose-400 font-bold uppercase tracking-wider block">Production Management</span>
                </div>
            </a>
            <span class="hidden lg:inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 ml-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> DB & API Live
            </span>
        </div>

        <!-- Right Quick Admin Actions -->
        <div class="flex items-center gap-2 sm:gap-3">
            <a href="/install" class="h-8 sm:h-9 px-2.5 sm:px-3 rounded-xl bg-[#121824] border border-gray-800 hover:border-gray-700 text-gray-300 hover:text-white text-xs font-semibold inline-flex items-center gap-1.5 transition shrink-0" title="Web Installer">
                <i class="fa-solid fa-screwdriver-wrench text-[10px] text-rose-400"></i> <span class="hidden sm:inline">Installer</span>
            </a>

            <a href="/" target="_blank" class="h-8 sm:h-9 px-2.5 sm:px-3 rounded-xl bg-[#121824] border border-gray-800 hover:border-gray-700 text-gray-300 hover:text-white text-xs font-semibold inline-flex items-center gap-1.5 transition shrink-0">
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> <span class="hidden sm:inline">Storefront</span>
            </a>

            <div class="flex items-center gap-1.5 sm:gap-2 pl-2 border-l border-gray-800">
                <div class="w-8 h-8 rounded-full bg-rose-600 flex items-center justify-center text-white font-bold text-xs shrink-0">
                    <?= strtoupper(substr($adminUser['name'], 0, 1)) ?>
                </div>
                <div class="hidden md:block text-left leading-none">
                    <span class="text-xs font-bold text-white block truncate max-w-[90px]"><?= htmlspecialchars($adminUser['name']) ?></span>
                    <span class="text-[10px] text-gray-400"><?= ucfirst($adminUser['role']) ?></span>
                </div>
                <a href="/admin/logout" title="Admin Logout" class="w-8 h-8 rounded-xl bg-[#121824] border border-gray-800 text-gray-400 hover:text-rose-400 hover:border-gray-700 flex items-center justify-center text-xs transition shrink-0">
                    <i class="fa-solid fa-power-off text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</header>

<script>
document.getElementById('adminMobileSidebarBtn')?.addEventListener('click', () => {
    const sidebar = document.getElementById('adminSidebar');
    if (sidebar) {
        sidebar.classList.toggle('hidden');
        sidebar.classList.toggle('w-full');
    }
});
</script>

<div class="flex flex-1">
    <!-- Admin Left Navigation Sidebar -->
    <?php require __DIR__ . '/admin_sidebar.php'; ?>

    <!-- Main Admin Content Area -->
    <main class="flex-1 p-4 lg:p-8 max-w-[1600px] w-full overflow-hidden">
