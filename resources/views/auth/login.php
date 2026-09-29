<?php
$pageTitle = "Login - FF Panel Store";
require __DIR__ . '/../layouts/header.php';
?>

<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-8 space-y-6">
        <div class="text-center space-y-1">
            <h1 class="text-2xl font-black text-white">Welcome Back</h1>
            <p class="text-xs text-gray-400">Login to your FF Panel Store account</p>
        </div>

        <form id="loginForm" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-300 mb-1">Email Address</label>
                <input type="email" id="email" required placeholder="name@example.com" 
                       class="w-full bg-[#111723] border border-gray-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-300 mb-1">Password</label>
                <input type="password" id="password" required placeholder="••••••••" 
                       class="w-full bg-[#111723] border border-gray-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500 transition">
            </div>

            <div id="loginError" class="hidden text-xs text-rose-400 bg-rose-500/10 border border-rose-500/20 rounded-xl p-3"></div>

            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 hover:to-rose-400 text-white font-bold text-sm transition shadow-lg shadow-rose-600/30">
                Sign In
            </button>
        </form>

        <div class="text-center text-xs text-gray-400">
            Don't have an account? <a href="/register" class="text-rose-400 hover:underline font-bold">Register</a>
        </div>
    </div>
</div>

<script>
document.getElementById('loginForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const errBox = document.getElementById('loginError');
    errBox.classList.add('hidden');
    try {
        const res = await fetch('/login', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                email: document.getElementById('email').value,
                password: document.getElementById('password').value
            })
        });
        const data = await res.json();
        if (data.success) {
            window.location.href = data.redirect || '/';
        } else {
            errBox.textContent = data.message || 'Login failed';
            errBox.classList.remove('hidden');
        }
    } catch (err) {
        errBox.textContent = 'Server communication error';
        errBox.classList.remove('hidden');
    }
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
