<?php
$pageTitle = "Email Verification - FF Panel Store";
require __DIR__ . '/../layouts/public_header.php';
?>

<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-8 space-y-5 text-center shadow-2xl">
        <div class="w-16 h-16 rounded-2xl bg-rose-500/10 text-rose-500 flex items-center justify-center mx-auto text-2xl font-bold">
            <i class="fa-solid fa-envelope-circle-check"></i>
        </div>
        <h1 class="text-2xl font-black text-white">Email Verified Successfully</h1>
        <p class="text-xs text-gray-400">Your account email has been verified. You now have full access to high-volume Free Fire instant recharges.</p>
        <div class="pt-2">
            <a href="/dashboard" class="inline-block px-6 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 text-white font-bold text-xs shadow-md">
                Go to Dashboard
            </a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/public_footer.php'; ?>
