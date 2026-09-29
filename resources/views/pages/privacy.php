<?php
$pageTitle = "Privacy Policy - FF Panel Store";
if (!empty($_SESSION['user_id'])) {
    require __DIR__ . '/../layouts/user_header.php';
} else {
    require __DIR__ . '/../layouts/public_header.php';
}
?>

<div class="max-w-4xl mx-auto px-4 py-12 space-y-6">
    <div class="border-b border-gray-800 pb-4">
        <h1 class="text-3xl font-black text-white">Privacy Policy</h1>
        <p class="text-xs text-gray-400 mt-1">Effective date: January 2026</p>
    </div>

    <div class="space-y-4 text-xs text-gray-300 leading-relaxed">
        <h3 class="text-sm font-bold text-white uppercase tracking-wider">1. Data We Collect</h3>
        <p>We collect your name, email address, mobile number (for verification), and Free Fire Player UID when placing orders. We never request or store your Free Fire game login passwords.</p>

        <h3 class="text-sm font-bold text-white uppercase tracking-wider">2. Payment Security</h3>
        <p>All online payment transactions are processed securely through certified PCI-DSS compliant gateways like Razorpay. Your card numbers, UPI PINs, or banking passwords are never stored on our servers.</p>

        <h3 class="text-sm font-bold text-white uppercase tracking-wider">3. Third Party Providers</h3>
        <p>To deliver in-game diamond top-ups, we transmit solely your Free Fire UID to verified game publishers and authorized regional voucher distribution APIs.</p>
    </div>
</div>

<?php 
if (!empty($_SESSION['user_id'])) {
    require __DIR__ . '/../layouts/user_footer.php';
} else {
    require __DIR__ . '/../layouts/public_footer.php';
}
?>
