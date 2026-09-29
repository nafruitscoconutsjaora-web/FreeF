<?php
$pageTitle = "About Us - FF Panel Store";
$activeNav = 'about';
if (!empty($_SESSION['user_id'])) {
    require __DIR__ . '/../layouts/user_header.php';
} else {
    require __DIR__ . '/../layouts/public_header.php';
}
?>

<div class="max-w-4xl mx-auto px-4 py-12 space-y-10">
    <div class="text-center space-y-3">
        <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20 uppercase tracking-wider">
            Who We Are
        </span>
        <h1 class="text-3xl sm:text-4xl font-black text-white">Direct & Instant Free Fire Top-Up Services</h1>
        <p class="text-gray-400 text-sm max-w-2xl mx-auto">
            FF Panel Store is an automated Free Fire voucher and top-up portal designed for competitive players, esports teams, and gaming enthusiasts across India and globally.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#0B0E14] border border-gray-800 rounded-2xl p-6 space-y-3">
            <div class="w-12 h-12 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center text-xl">
                <i class="fa-solid fa-bolt"></i>
            </div>
            <h3 class="font-bold text-white text-base">Under 60s Delivery</h3>
            <p class="text-xs text-gray-400 leading-relaxed">Direct automated provider API pipelines process diamond deliveries directly into player accounts in seconds.</p>
        </div>

        <div class="bg-[#0B0E14] border border-gray-800 rounded-2xl p-6 space-y-3">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h3 class="font-bold text-white text-base">100% Anti-Ban Guarantee</h3>
            <p class="text-xs text-gray-400 leading-relaxed">All diamond top-ups are sourced through official regional gaming publisher vouchers without password sharing.</p>
        </div>

        <div class="bg-[#0B0E14] border border-gray-800 rounded-2xl p-6 space-y-3">
            <div class="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-headset"></i>
            </div>
            <h3 class="font-bold text-white text-base">24/7 Priority Support</h3>
            <p class="text-xs text-gray-400 leading-relaxed">Dedicated ticket and WhatsApp resolution desk ensuring zero pending orders remain unresolved.</p>
        </div>
    </div>
</div>

<?php 
if (!empty($_SESSION['user_id'])) {
    require __DIR__ . '/../layouts/user_footer.php';
} else {
    require __DIR__ . '/../layouts/public_footer.php';
}
?>
