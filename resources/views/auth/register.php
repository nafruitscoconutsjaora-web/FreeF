<?php
$pageTitle = "Create Account - FF Panel Store";
require __DIR__ . '/../layouts/auth_header.php';
?>

<div class="max-w-md w-full my-auto">
    <div class="bg-[#0B0E14] border border-gray-800/80 rounded-3xl p-6 sm:p-8 space-y-5 shadow-2xl">
        <div class="text-center space-y-1.5">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-rose-600 via-rose-500 to-rose-400 flex items-center justify-center font-black text-white text-lg mx-auto shadow-lg shadow-rose-600/30">
                <i class="fa-solid fa-user-plus text-base"></i>
            </div>
            <h1 class="text-2xl font-black text-white">Create an Account</h1>
            <p class="text-xs text-gray-400">Join FF Panel Store for instant game recharges & services</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="text-xs text-rose-400 bg-rose-500/10 border border-rose-500/20 rounded-xl p-3 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-rose-500 shrink-0"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <div id="regError" class="hidden text-xs text-rose-400 bg-rose-500/10 border border-rose-500/20 rounded-xl p-3 flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-rose-500 shrink-0"></i>
            <span id="regErrorText"></span>
        </div>

        <form id="registerForm" action="/register" method="POST" class="space-y-3.5">
            <?= csrf_field() ?>

            <div>
                <label for="regName" class="block text-xs font-bold text-gray-300 mb-1">Full Name *</label>
                <div class="relative">
                    <i class="fa-solid fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
                    <input type="text" id="regName" name="name" required placeholder="Aaris Ali" 
                           class="w-full bg-[#111723] border border-gray-800 rounded-xl pl-9 pr-4 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 transition">
                </div>
            </div>

            <div>
                <label for="regEmail" class="block text-xs font-bold text-gray-300 mb-1">Email Address *</label>
                <div class="relative">
                    <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
                    <input type="email" id="regEmail" name="email" required placeholder="name@example.com" 
                           class="w-full bg-[#111723] border border-gray-800 rounded-xl pl-9 pr-4 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 transition">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="regPhone" class="block text-xs font-bold text-gray-300 mb-1">Phone (Optional)</label>
                    <input type="tel" id="regPhone" name="phone" placeholder="+91 9876543210" 
                           class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 transition">
                </div>
                <div>
                    <label for="regUid" class="block text-xs font-bold text-gray-300 mb-1">Free Fire UID (Default)</label>
                    <input type="text" id="regUid" name="ff_uid" placeholder="5482910482" 
                           class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white font-mono focus:outline-none focus:border-rose-500 transition">
                </div>
            </div>

            <div>
                <label for="regPassword" class="block text-xs font-bold text-gray-300 mb-1">Password *</label>
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
                    <input type="password" id="regPassword" name="password" required minlength="6" placeholder="At least 6 characters" 
                           class="w-full bg-[#111723] border border-gray-800 rounded-xl pl-9 pr-4 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 transition">
                </div>
            </div>

            <div>
                <label for="regRef" class="block text-xs font-bold text-gray-300 mb-1">Referral Code (Optional)</label>
                <input type="text" id="regRef" name="referral_code" placeholder="FFVIP77" value="<?= htmlspecialchars($_GET['ref'] ?? '') ?>"
                       class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white uppercase tracking-wider focus:outline-none focus:border-rose-500 transition font-mono">
            </div>

            <button type="submit" id="regSubmitBtn" class="w-full py-3 rounded-xl bg-gradient-to-r from-rose-600 via-rose-500 to-rose-600 hover:from-rose-500 hover:to-rose-400 text-white font-bold text-xs sm:text-sm transition shadow-lg shadow-rose-600/30 flex items-center justify-center gap-2 cursor-pointer">
                <span>Create Account</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </form>

        <div class="pt-2 border-t border-gray-800/80 text-center text-xs text-gray-400">
            Already have an account? <a href="/login" class="text-rose-400 hover:text-rose-300 hover:underline font-bold transition">Sign In</a>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const errBox = document.getElementById('regError');
    const errText = document.getElementById('regErrorText');
    const btn = document.getElementById('regSubmitBtn');

    errBox.classList.add('hidden');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Creating Account...';

    const payload = {
        name: document.getElementById('regName').value.trim(),
        email: document.getElementById('regEmail').value.trim(),
        phone: document.getElementById('regPhone').value.trim(),
        ff_uid: document.getElementById('regUid').value.trim(),
        password: document.getElementById('regPassword').value,
        referral_code: document.getElementById('regRef').value.trim()
    };

    try {
        const res = await fetch('/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        });
        const data = await res.json();
        if (data.success) {
            window.location.href = data.redirect || '/dashboard';
        } else {
            errText.textContent = data.message || 'Registration failed. Please check inputs.';
            errBox.classList.remove('hidden');
            btn.disabled = false;
            btn.innerHTML = '<span>Create Account</span> <i class="fa-solid fa-arrow-right text-xs"></i>';
        }
    } catch (err) {
        document.getElementById('registerForm').submit();
    }
});
</script>

<?php require __DIR__ . '/../layouts/auth_footer.php'; ?>
