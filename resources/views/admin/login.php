<?php
$pageTitle = "Admin Authorization - FF Panel Store";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrative Login - FF Panel Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-[#070A0F] text-gray-100 min-h-screen flex items-center justify-center p-4">

<div class="max-w-md w-full bg-[#0D111A] border border-gray-800 rounded-3xl p-8 space-y-6 shadow-2xl">
    <div class="text-center space-y-2">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-rose-600 to-rose-400 flex items-center justify-center font-black text-white text-xl mx-auto shadow-lg shadow-rose-600/30">
            <i class="fa-solid fa-shield-halved text-base"></i>
        </div>
        <h1 class="text-2xl font-black text-white">Administrative Portal</h1>
        <p class="text-xs text-gray-400">Restricted access area for store operations and API control</p>
    </div>

    <form id="adminLoginForm" class="space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-300 mb-1">Admin Email</label>
            <input type="email" id="adminEmail" required placeholder="admin@ffpanel.com" 
                   class="w-full bg-[#121824] border border-gray-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 transition">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-300 mb-1">Password</label>
            <input type="password" id="adminPassword" required placeholder="••••••••" 
                   class="w-full bg-[#121824] border border-gray-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 transition">
        </div>

        <div id="adminLoginError" class="hidden text-xs text-rose-400 bg-rose-500/10 border border-rose-500/20 rounded-xl p-3"></div>

        <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs shadow-lg shadow-rose-600/30 transition">
            Authenticate to Control Center
        </button>
    </form>

    <div class="text-center">
        <a href="/" class="text-xs text-gray-500 hover:text-gray-300 transition">← Return to Storefront</a>
    </div>
</div>

<script>
document.getElementById('adminLoginForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const errBox = document.getElementById('adminLoginError');
    errBox.classList.add('hidden');
    try {
        const res = await fetch('/admin/login', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                email: document.getElementById('adminEmail').value,
                password: document.getElementById('adminPassword').value
            })
        });
        const data = await res.json();
        if (data.success) {
            window.location.href = data.redirect || '/admin';
        } else {
            errBox.textContent = data.message || 'Authorization failed';
            errBox.classList.remove('hidden');
        }
    } catch (err) {
        errBox.textContent = 'Server communication error';
        errBox.classList.remove('hidden');
    }
});
</script>

</body>
</html>
