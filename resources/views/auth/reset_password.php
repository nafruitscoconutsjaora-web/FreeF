<?php
$pageTitle = "Set New Password - FF Panel Store";
require __DIR__ . '/../layouts/public_header.php';
?>

<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-8 space-y-6 shadow-2xl">
        <div class="text-center space-y-1">
            <h1 class="text-2xl font-black text-white">Set New Password</h1>
            <p class="text-xs text-gray-400">Choose a new secure password for your account</p>
        </div>

        <form id="resetForm" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-300 mb-1">New Password</label>
                <input type="password" id="newPass" required minlength="6" placeholder="••••••••" 
                       class="w-full bg-[#111723] border border-gray-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-300 mb-1">Confirm New Password</label>
                <input type="password" id="confirmPass" required minlength="6" placeholder="••••••••" 
                       class="w-full bg-[#111723] border border-gray-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 transition">
            </div>

            <div id="resetMsg" class="hidden text-xs rounded-xl p-3"></div>

            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs transition shadow-lg shadow-rose-600/30">
                Update Password
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('resetForm')?.addEventListener('submit', (e) => {
    e.preventDefault();
    const p1 = document.getElementById('newPass').value;
    const p2 = document.getElementById('confirmPass').value;
    const box = document.getElementById('resetMsg');
    if (p1 !== p2) {
        box.className = 'text-xs text-rose-400 bg-rose-500/10 border border-rose-500/20 rounded-xl p-3 block';
        box.textContent = 'Passwords do not match.';
        return;
    }
    box.className = 'text-xs text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-3 block';
    box.textContent = 'Password successfully updated! You may now login.';
    setTimeout(() => { window.location.href = '/login'; }, 1500);
});
</script>

<?php require __DIR__ . '/../layouts/public_footer.php'; ?>
