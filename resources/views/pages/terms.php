<?php
$pageTitle = "Terms & Conditions - FF Panel Store";
if (!empty($_SESSION['user_id'])) {
    require __DIR__ . '/../layouts/user_header.php';
} else {
    require __DIR__ . '/../layouts/public_header.php';
}
?>

<div class="max-w-4xl mx-auto px-4 py-12 space-y-6">
    <div class="border-b border-gray-800 pb-4">
        <h1 class="text-3xl font-black text-white">Terms and Conditions</h1>
        <p class="text-xs text-gray-400 mt-1">Last revised: January 2026</p>
    </div>

    <div class="prose prose-invert max-w-none space-y-4 text-xs text-gray-300 leading-relaxed">
        <h3 class="text-sm font-bold text-white uppercase tracking-wider">1. Acceptance of Terms</h3>
        <p>By registering, accessing, or placing an order on FF Panel Store, you agree to be bound by these Terms of Service. If you do not agree with any part, please do not use our services.</p>

        <h3 class="text-sm font-bold text-white uppercase tracking-wider">2. Digital Services & UID Top-Up</h3>
        <p>All products sold on this website are digital Free Fire vouchers, diamonds, memberships, and in-game items. Deliveries are processed directly via your provided Player UID. It is the customer's sole responsibility to ensure that the correct UID is submitted.</p>

        <h3 class="text-sm font-bold text-white uppercase tracking-wider">3. Wallet & Payments</h3>
        <p>Wallet top-ups made through Razorpay or direct gateway transactions are stored securely in your user balance. Credits cannot be withdrawn to external bank accounts once added unless an unresolvable technical fault occurred.</p>

        <h3 class="text-sm font-bold text-white uppercase tracking-wider">4. Fair Usage & Prohibited Behavior</h3>
        <p>Any attempt to exploit promotional codes, reverse engineer payment webhooks, or engage in fraudulent chargebacks will result in immediate permanent suspension of the account and IP banning.</p>
    </div>
</div>

<?php 
if (!empty($_SESSION['user_id'])) {
    require __DIR__ . '/../layouts/user_footer.php';
} else {
    require __DIR__ . '/../layouts/public_footer.php';
}
?>
