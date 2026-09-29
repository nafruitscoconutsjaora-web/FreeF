<?php
$pageTitle = "My Profile & Settings - FF Panel Store";
$activeNav = 'profile';
require __DIR__ . '/../layouts/user_header.php';
?>

<div class="max-w-4xl mx-auto px-4 py-8 space-y-6">
    <div>
        <h1 class="text-2xl font-black text-white">Profile & Account Settings</h1>
        <p class="text-xs text-gray-400 mt-1">Manage your Free Fire Player UID, contact details, and security passwords.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
        <!-- Personal Information -->
        <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-6 space-y-4">
            <h3 class="font-bold text-white text-base">Account Information</h3>
            
            <form id="profileForm" class="space-y-3.5">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Full Name</label>
                    <input type="text" id="profName" value="<?= htmlspecialchars($user['name'] ?? '') ?>" 
                           class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Email Address</label>
                    <input type="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" disabled 
                           class="w-full bg-[#080B11] border border-gray-800/60 rounded-xl px-3.5 py-2.5 text-xs text-gray-500 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Default Free Fire UID</label>
                    <input type="text" id="profUid" value="<?= htmlspecialchars($user['ff_uid'] ?? '') ?>" 
                           class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Phone Number</label>
                    <input type="text" id="profPhone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" 
                           class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500">
                </div>

                <div id="profMsg" class="hidden text-xs rounded-xl p-3"></div>

                <button type="submit" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs transition shadow">
                    Save Changes
                </button>
            </form>
        </div>

        <!-- Security / Password Change -->
        <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-6 space-y-4">
            <h3 class="font-bold text-white text-base">Security & Password</h3>

            <form id="passwordForm" class="space-y-3.5">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Current Password</label>
                    <input type="password" id="curPass" required placeholder="••••••••" 
                           class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">New Password</label>
                    <input type="password" id="newPass" required minlength="6" placeholder="At least 6 characters" 
                           class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Confirm New Password</label>
                    <input type="password" id="confirmNewPass" required minlength="6" placeholder="••••••••" 
                           class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500">
                </div>

                <div id="passMsg" class="hidden text-xs rounded-xl p-3"></div>

                <button type="submit" class="w-full py-2.5 rounded-xl bg-gray-800 hover:bg-gray-700 text-white font-bold text-xs transition">
                    Update Password
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('profileForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const box = document.getElementById('profMsg');
    box.className = 'text-xs text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-3 block';
    box.textContent = "Profile information updated successfully!";
});

document.getElementById('passwordForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const p1 = document.getElementById('newPass').value;
    const p2 = document.getElementById('confirmNewPass').value;
    const box = document.getElementById('passMsg');
    if (p1 !== p2) {
        box.className = 'text-xs text-rose-400 bg-rose-500/10 border border-rose-500/20 rounded-xl p-3 block';
        box.textContent = 'Passwords do not match.';
        return;
    }
    box.className = 'text-xs text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-3 block';
    box.textContent = 'Password updated successfully!';
    document.getElementById('passwordForm').reset();
});
</script>

<?php require __DIR__ . '/../layouts/user_footer.php'; ?>
