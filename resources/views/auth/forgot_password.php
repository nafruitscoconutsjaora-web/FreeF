<?php
$pageTitle = "Reset Password - FF Panel Store";
require __DIR__ . '/../layouts/public_header.php';
?>

<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-8 space-y-6 shadow-2xl">
        <div class="text-center space-y-1">
            <h1 class="text-2xl font-black text-white">Forgot Password</h1>
            <p class="text-xs text-gray-400">Enter your registered email to receive a password reset link</p>
        </div>

        <form id="forgotForm" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-300 mb-1">Email Address</label>
                <input type="email" id="forgotEmail" required placeholder="name@example.com" 
                       class="w-full bg-[#111723] border border-gray-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 transition">
            </div>

            <div id="forgotMsg" class="hidden text-xs rounded-xl p-3"></div>

            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs transition shadow-lg shadow-rose-600/30">
                Send Reset Link
            </button>
        </form>

        <div class="text-center text-xs text-gray-400">
            Remembered your credentials? <a href="/login" class="text-rose-400 hover:underline font-bold">Sign In</a>
        </div>
    </div>
</div>

<script>
document.getElementById('forgotForm')?.addEventListener('submit', (e) => {
    e.preventDefault();
    const box = document.getElementById('forgotMsg');
    box.className = 'text-xs text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-3 block';
    box.textContent = "A password reset confirmation link has been dispatched to your email address.";
});
</script>

<?php require __DIR__ . '/../layouts/public_footer.php'; ?>
