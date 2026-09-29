<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Installation Completed - FF Panel Store') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
      body { background-color: #080B11; color: #F3F4F6; font-family: system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body class="bg-[#080B11] text-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-[#0D111A] border border-gray-800 rounded-3xl p-6 sm:p-8 text-center space-y-6 shadow-2xl">
        <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center text-3xl shadow-lg shadow-emerald-500/20">
            <i class="fa-solid fa-circle-check"></i>
        </div>

        <div class="space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 uppercase tracking-wider">
                <i class="fa-solid fa-check"></i> Ready For Production
            </span>
            <h1 class="text-xl sm:text-2xl font-black text-white">Installation completed successfully.</h1>
            <p class="text-xs sm:text-sm text-gray-400 leading-relaxed">
                Your FF Panel Store database schema, system settings, administrator credentials, and security markers have been securely established.
            </p>
        </div>

        <div class="p-4 bg-[#121824] rounded-2xl border border-gray-800/80 text-left space-y-2.5 text-xs">
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Installation Status:</span>
                <span class="text-emerald-400 font-bold flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Success
                </span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Website URL:</span>
                <a href="<?= htmlspecialchars($websiteUrl ?? '/') ?>" class="text-rose-400 hover:underline font-mono">
                    <?= htmlspecialchars($websiteUrl ?? '/') ?>
                </a>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Admin Login URL:</span>
                <a href="<?= htmlspecialchars($adminUrl ?? '/admin/login') ?>" class="text-rose-400 hover:underline font-mono">
                    <?= htmlspecialchars($adminUrl ?? '/admin/login') ?>
                </a>
            </div>
            <div class="flex items-center justify-between border-t border-gray-800 pt-2 text-[11px]">
                <span class="text-gray-500">Security Lock:</span>
                <span class="text-gray-400 font-mono">config/installed.lock</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
            <a href="<?= htmlspecialchars($websiteUrl ?? '/') ?>" class="h-10 px-4 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs inline-flex items-center justify-center gap-2 shadow-lg shadow-rose-600/30 transition">
                <i class="fa-solid fa-store text-xs"></i> Visit Website
            </a>
            <a href="<?= htmlspecialchars($adminUrl ?? '/admin/login') ?>" class="h-10 px-4 rounded-xl bg-[#121824] border border-gray-800 hover:border-gray-700 text-gray-200 hover:text-white font-bold text-xs inline-flex items-center justify-center gap-2 transition">
                <i class="fa-solid fa-shield-halved text-rose-500 text-xs"></i> Open Admin Panel
            </a>
        </div>
    </div>
</body>
</html>
