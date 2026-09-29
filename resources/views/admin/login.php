<?php
$pageTitle = "Administrative Login - FF Panel Store";
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/css/app.css">
    <style>
      body { background-color: #070A0F; color: #F3F4F6; font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; overflow-x: hidden; }
    </style>
</head>
<body class="bg-[#070A0F] text-gray-100 min-h-screen flex items-center justify-center p-4">

<div class="max-w-md w-full bg-[#0D111A] border border-gray-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-2xl">
    <div class="text-center space-y-2">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-rose-600 via-rose-500 to-rose-400 flex items-center justify-center font-black text-white text-xl mx-auto shadow-lg shadow-rose-600/30">
            <i class="fa-solid fa-shield-halved text-base"></i>
        </div>
        <h1 class="text-2xl font-black text-white">Administrative Portal</h1>
        <p class="text-xs text-gray-400">Restricted access area for store operations and API control</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="text-xs text-rose-400 bg-rose-500/10 border border-rose-500/20 rounded-xl p-3 flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-rose-500 shrink-0"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <div id="adminLoginError" class="hidden text-xs text-rose-400 bg-rose-500/10 border border-rose-500/20 rounded-xl p-3 flex items-center gap-2">
        <i class="fa-solid fa-circle-exclamation text-rose-500 shrink-0"></i>
        <span id="adminLoginErrorText"></span>
    </div>

    <form id="adminLoginForm" action="/admin/login" method="POST" class="space-y-4">
        <?= csrf_field() ?>

        <div>
            <label for="adminEmail" class="block text-xs font-bold text-gray-300 mb-1">Admin Email</label>
            <div class="relative">
                <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
                <input type="email" id="adminEmail" name="email" required placeholder="admin@ffpanel.com" autofocus
                       class="w-full bg-[#121824] border border-gray-800 rounded-xl pl-9 pr-4 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 transition">
            </div>
        </div>

        <div>
            <label for="adminPassword" class="block text-xs font-bold text-gray-300 mb-1">Password</label>
            <div class="relative">
                <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
                <input type="password" id="adminPassword" name="password" required placeholder="••••••••" 
                       class="w-full bg-[#121824] border border-gray-800 rounded-xl pl-9 pr-4 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 transition">
            </div>
        </div>

        <button type="submit" id="adminSubmitBtn" class="w-full py-3 rounded-xl bg-gradient-to-r from-rose-600 via-rose-500 to-rose-600 hover:from-rose-500 text-white font-bold text-xs shadow-lg shadow-rose-600/30 transition flex items-center justify-center gap-2 cursor-pointer">
            <span>Authenticate to Control Center</span>
            <i class="fa-solid fa-arrow-right text-xs"></i>
        </button>
    </form>

    <div class="text-center pt-2 border-t border-gray-800/80">
        <a href="/" class="text-xs text-gray-500 hover:text-gray-300 transition">← Return to Storefront</a>
    </div>
</div>

<script>
document.getElementById('adminLoginForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const errBox = document.getElementById('adminLoginError');
    const errText = document.getElementById('adminLoginErrorText');
    const btn = document.getElementById('adminSubmitBtn');

    errBox.classList.add('hidden');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Authenticating...';

    const email = document.getElementById('adminEmail').value.trim();
    const password = document.getElementById('adminPassword').value;

    try {
        const res = await fetch('/admin/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ email, password })
        });
        const data = await res.json();
        if (data.success) {
            window.location.href = data.redirect || '/admin';
        } else {
            errText.textContent = data.message || 'Authorization failed';
            errBox.classList.remove('hidden');
            btn.disabled = false;
            btn.innerHTML = '<span>Authenticate to Control Center</span> <i class="fa-solid fa-arrow-right text-xs"></i>';
        }
    } catch (err) {
        document.getElementById('adminLoginForm').submit();
    }
});
</script>

</body>
</html>
