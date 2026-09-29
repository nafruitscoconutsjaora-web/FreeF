<?php
/**
 * Dedicated Authentication Header Layout (/login, /register)
 * Clean, focused authentication screen header.
 * STRICTLY NO: Notification bell, private count, wallet balance, user profile dropdown, private widgets.
 */
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'FF Panel Store - Secure Authentication') ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/css/app.css">
    <style>
      body { background-color: #080B11; color: #F3F4F6; font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; overflow-x: hidden; }
    </style>
</head>
<body class="bg-[#080B11] text-gray-100 min-h-screen flex flex-col antialiased">

<!-- Clean Authentication Top Navigation -->
<header class="bg-[#0B0E14] border-b border-gray-800/80 px-4 sm:px-6 lg:px-8 relative w-full">
    <div class="max-w-[1580px] mx-auto flex items-center justify-between gap-3 h-14 sm:h-16 lg:h-[68px]">
        <!-- Brand Logo -->
        <a href="/" class="flex items-center gap-2.5 sm:gap-3 shrink-0 group">
            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-gradient-to-tr from-rose-600 via-rose-500 to-rose-400 flex items-center justify-center font-black text-white text-base sm:text-lg tracking-tighter shadow-md shadow-rose-600/30 group-hover:scale-105 transition transform">
                FF
            </div>
            <div>
                <span class="text-sm sm:text-base font-black text-white tracking-wide block leading-tight">FF Panel Store</span>
                <span class="text-[10px] sm:text-[11px] text-gray-400 block font-medium">Fast • Safe • Reliable</span>
            </div>
        </a>

        <!-- Storefront Return Link -->
        <div class="flex items-center gap-3">
            <a href="/" class="px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-xl text-xs font-semibold text-gray-300 hover:text-white bg-[#111723] hover:bg-gray-800/80 border border-gray-800 transition inline-flex items-center gap-1.5 shadow-sm">
                <i class="fa-solid fa-arrow-left text-[11px]"></i>
                <span>Storefront</span>
            </a>
        </div>
    </div>
</header>

<main class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8">
