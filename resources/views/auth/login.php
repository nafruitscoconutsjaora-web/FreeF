<?php
$pageTitle = "Sign In - FF Panel Store";
require __DIR__ . '/../layouts/auth_header.php';
?>

<div class="max-w-md w-full my-auto">
    <div class="bg-[#0B0E14] border border-gray-800/80 rounded-3xl p-6 sm:p-8 space-y-6 shadow-2xl">
        <div class="text-center space-y-1.5">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-rose-600 via-rose-500 to-rose-400 flex items-center justify-center font-black text-white text-lg mx-auto shadow-lg shadow-rose-600/30">
                <i class="fa-solid fa-arrow-right-to-bracket text-base"></i>
            </div>
            <h1 class="text-2xl font-black text-white">Welcome Back</h1>
            <p class="text-xs text-gray-400">Sign in to manage your orders and game wallet</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="text-xs text-rose-400 bg-rose-500/10 border border-rose-500/20 rounded-xl p-3 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-rose-500 shrink-0"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <div id="loginError" class="hidden text-xs text-rose-400 bg-rose-500/10 border border-rose-500/20 rounded-xl p-3 flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-rose-500 shrink-0"></i>
            <span id="loginErrorText"></span>
        </div>

        <form id="loginForm" action="/login" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label for="email" class="block text-xs font-bold text-gray-300 mb-1">Email Address</label>
                <div class="relative">
                    <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
                    <input type="email" id="email" name="email" required placeholder="name@example.com" autofocus
                           class="w-full bg-[#111723] border border-gray-800 rounded-xl pl-9 pr-4 py-2.5 text-xs sm:text-sm text-white focus:outline-none focus:border-rose-500 transition">
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-xs font-bold text-gray-300">Password</label>
                    <a href="/forgot-password" class="text-[11px] text-gray-500 hover:text-rose-400 transition">Forgot?</a>
                </div>
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
                    <input type="password" id="password" name="password" required placeholder="••••••••" 
                           class="w-full bg-[#111723] border border-gray-800 rounded-xl pl-9 pr-4 py-2.5 text-xs sm:text-sm text-white focus:outline-none focus:border-rose-500 transition">
                </div>
            </div>

            <button type="submit" id="loginSubmitBtn" class="w-full py-3 rounded-xl bg-gradient-to-r from-rose-600 via-rose-500 to-rose-600 hover:from-rose-500 hover:to-rose-400 text-white font-bold text-xs sm:text-sm transition shadow-lg shadow-rose-600/30 flex items-center justify-center gap-2 cursor-pointer">
                <span>Sign In</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </form>

        <div class="pt-2 border-t border-gray-800/80 text-center text-xs text-gray-400">
            Don't have an account? <a href="/register" class="text-rose-400 hover:text-rose-300 hover:underline font-bold transition">Create Account</a>
        </div>
    </div>
</div>

<script>
document.getElementById('loginForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const errBox = document.getElementById('loginError');
    const errText = document.getElementById('loginErrorText');
    const btn = document.getElementById('loginSubmitBtn');
    
    errBox.classList.add('hidden');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Signing In...';

    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;

    try {
        const res = await fetch('/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ email, password })
        });
        const data = await res.json();
        if (data.success) {
            window.location.href = data.redirect || '/dashboard';
        } else {
            errText.textContent = data.message || 'Invalid email or password.';
            errBox.classList.remove('hidden');
            btn.disabled = false;
            btn.innerHTML = '<span>Sign In</span> <i class="fa-solid fa-arrow-right text-xs"></i>';
        }
    } catch (err) {
        // Fallback to normal form submit if fetch fails
        document.getElementById('loginForm').submit();
    }
});
</script>

<?php require __DIR__ . '/../layouts/auth_footer.php'; ?>
