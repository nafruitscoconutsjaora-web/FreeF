<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'FF Panel Store - Already Installed') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
      body { background-color: #080B11; color: #F3F4F6; font-family: system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body class="bg-[#080B11] text-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-[#0D111A] border border-gray-800 rounded-3xl p-6 sm:p-8 text-center space-y-6 shadow-2xl">
        <div class="w-16 h-16 mx-auto rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl shadow-lg">
            <i class="fa-solid fa-lock"></i>
        </div>

        <div class="space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 uppercase tracking-wider">
                <i class="fa-solid fa-shield-halved"></i> Installation Locked
            </span>
            <h1 class="text-xl sm:text-2xl font-black text-white">Application is already installed.</h1>
            <p class="text-xs sm:text-sm text-gray-400 leading-relaxed">
                For security reasons, the web installer has been permanently locked to prevent accidental database overwrite or unauthorized reconfiguration.
            </p>
        </div>

        <div class="p-4 bg-[#121824] rounded-2xl border border-gray-800/80 text-left space-y-2 text-xs text-gray-300">
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Security Marker:</span>
                <span class="font-mono text-emerald-400 font-bold">installed.lock</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Database Engine:</span>
                <span class="font-medium text-white">MySQL / MariaDB</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Store Status:</span>
                <span class="font-semibold text-emerald-400">Online & Live</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
            <a href="/" class="h-10 px-4 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs inline-flex items-center justify-center gap-2 shadow-lg shadow-rose-600/30 transition">
                <i class="fa-solid fa-house text-xs"></i> Visit Website
            </a>
            <a href="/admin/login" class="h-10 px-4 rounded-xl bg-[#121824] border border-gray-800 hover:border-gray-700 text-gray-200 hover:text-white font-bold text-xs inline-flex items-center justify-center gap-2 transition">
                <i class="fa-solid fa-shield-halved text-rose-500 text-xs"></i> Open Admin Panel
            </a>
        </div>
    </div>
</body>
</html>
