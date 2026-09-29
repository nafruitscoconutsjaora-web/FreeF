<?php
$pageTitle = "Create Account - FF Panel Store";
require __DIR__ . '/../layouts/public_header.php';
?>

<div class="max-w-md mx-auto px-4 py-12">
    <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-8 space-y-6 shadow-2xl">
        <div class="text-center space-y-1">
            <h1 class="text-2xl font-black text-white">Create an Account</h1>
            <p class="text-xs text-gray-400">Join FF Panel Store for instant gaming top-ups</p>
        </div>

        <form id="registerForm" class="space-y-3.5">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <div>
                <label class="block text-xs font-bold text-gray-300 mb-1">Full Name *</label>
                <input type="text" id="regName" required placeholder="Aaris Ali" 
                       class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-300 mb-1">Email Address *</label>
                <input type="email" id="regEmail" required placeholder="name@example.com" 
                       class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-300 mb-1">Phone Number (Optional)</label>
                <input type="text" id="regPhone" placeholder="+91 9876543210" 
                       class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-300 mb-1">Free Fire UID (Default)</label>
                <input type="text" id="regUid" placeholder="e.g. 5482910482" 
                       class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 font-mono transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-300 mb-1">Password *</label>
                <input type="password" id="regPassword" required minlength="6" placeholder="At least 6 characters" 
                       class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-300 mb-1">Referral Code (Optional)</label>
                <input type="text" id="regRef" placeholder="FFVIP77" value="<?= htmlspecialchars($_GET['ref'] ?? '') ?>"
                       class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white uppercase focus:outline-none focus:border-rose-500 transition">
            </div>

            <div id="regError" class="hidden text-xs text-rose-400 bg-rose-500/10 border border-rose-500/20 rounded-xl p-3"></div>

            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs transition shadow-lg shadow-rose-600/30">
                Register Account
            </button>
        </form>

        <div class="text-center text-xs text-gray-400">
            Already have an account? <a href="/login" class="text-rose-400 hover:underline font-bold">Sign In</a>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const errBox = document.getElementById('regError');
    errBox.classList.add('hidden');
    try {
        const res = await fetch('/register', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                name: document.getElementById('regName').value,
                email: document.getElementById('regEmail').value,
                phone: document.getElementById('regPhone').value,
                ff_uid: document.getElementById('regUid').value,
                password: document.getElementById('regPassword').value,
                referral_code: document.getElementById('regRef').value,
            })
        });
        const data = await res.json();
        if (data.success) {
            window.location.href = data.redirect || '/dashboard';
        } else {
            errBox.textContent = data.message || 'Registration failed';
            errBox.classList.remove('hidden');
        }
    } catch (err) {
        errBox.textContent = 'Server communication error';
        errBox.classList.remove('hidden');
    }
});
</script>

<?php require __DIR__ . '/../layouts/public_footer.php'; ?>
