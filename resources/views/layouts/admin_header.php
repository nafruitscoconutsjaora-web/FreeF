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
    <style>
      body { background-color: #070A0F; color: #F3F4F6; font-family: system-ui, -apple-system, sans-serif; overflow-x: hidden; }
      ::-webkit-scrollbar { width: 5px; height: 5px; }
      ::-webkit-scrollbar-thumb { background: #1F2937; border-radius: 9999px; }
    </style>
</head>
<body class="bg-[#070A0F] text-gray-100 min-h-screen flex flex-col antialiased">

<!-- Admin Top Navigation Bar -->
<header class="bg-[#0D111A] border-b border-gray-800 px-4 lg:px-8 py-3 relative">
    <div class="flex items-center justify-between gap-4">
        <!-- Brand & Badge -->
        <div class="flex items-center gap-3">
            <a href="/admin" class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-rose-600 to-rose-400 flex items-center justify-center font-black text-white text-base shadow-lg shadow-rose-600/30">
                    <i class="fa-solid fa-shield-halved text-sm"></i>
                </div>
                <div>
                    <span class="text-base font-black text-white tracking-wide block leading-tight">FF Panel Admin</span>
                    <span class="text-[10px] text-rose-400 font-bold uppercase tracking-wider block">Production Management</span>
                </div>
            </a>
            <span class="hidden md:inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 ml-2">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> DB & API Live
            </span>
        </div>

        <!-- Right Quick Admin Actions -->
        <div class="flex items-center gap-3">
            <a href="/" target="_blank" class="px-3 py-1.5 rounded-xl bg-[#121824] border border-gray-800 hover:border-gray-700 text-gray-300 hover:text-white text-xs font-semibold flex items-center gap-1.5 transition">
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> View Storefront
            </a>

            <div class="flex items-center gap-2 pl-2 border-l border-gray-800">
                <div class="w-8 h-8 rounded-full bg-rose-600 flex items-center justify-center text-white font-bold text-xs">
                    <?= strtoupper(substr($adminUser['name'], 0, 1)) ?>
                </div>
                <div class="hidden sm:block text-left leading-none">
                    <span class="text-xs font-bold text-white block"><?= htmlspecialchars($adminUser['name']) ?></span>
                    <span class="text-[10px] text-gray-400"><?= ucfirst($adminUser['role']) ?></span>
                </div>
                <a href="/admin/logout" title="Admin Logout" class="ml-2 w-8 h-8 rounded-xl bg-[#121824] border border-gray-800 text-gray-400 hover:text-rose-400 hover:border-gray-700 flex items-center justify-center text-xs transition">
                    <i class="fa-solid fa-power-off"></i>
                </a>
            </div>
        </div>
    </div>
</header>

<div class="flex flex-1">
    <!-- Admin Left Navigation Sidebar -->
    <?php require __DIR__ . '/admin_sidebar.php'; ?>

    <!-- Main Admin Content Area -->
    <main class="flex-1 p-4 lg:p-8 max-w-[1600px] w-full overflow-hidden">
