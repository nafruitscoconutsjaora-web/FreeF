<?php
$pageTitle = "Refund & Cancellation Policy - FF Panel Store";
if (!empty($_SESSION['user_id'])) {
    require __DIR__ . '/../layouts/user_header.php';
} else {
    require __DIR__ . '/../layouts/public_header.php';
}
?>

<div class="max-w-4xl mx-auto px-4 py-12 space-y-6">
    <div class="border-b border-gray-800 pb-4">
        <h1 class="text-3xl font-black text-white">Refund & Cancellation Policy</h1>
        <p class="text-xs text-gray-400 mt-1">Our commitment to fair resolutions</p>
    </div>

    <div class="space-y-4 text-xs text-gray-300 leading-relaxed">
        <h3 class="text-sm font-bold text-white uppercase tracking-wider">1. Nature of Digital Goods</h3>
        <p>Because Free Fire diamonds, memberships, and weapon crates are delivered instantly to the submitted Player UID, orders marked as "Completed" cannot be revoked or refunded.</p>

        <h3 class="text-sm font-bold text-white uppercase tracking-wider">2. Failed Transactions & Delays</h3>
        <p>If payment was deducted but the order status remains "Failed" or "Cancelled" due to publisher maintenance, the full order amount is automatically refunded back to your FF Panel Store digital wallet immediately.</p>

        <h3 class="text-sm font-bold text-white uppercase tracking-wider">3. Incorrect UID Entry</h3>
        <p>Please double-check your UID before confirming payment. Orders sent to a valid Free Fire account due to user typographical error cannot be refunded.</p>
    </div>
</div>

<?php 
if (!empty($_SESSION['user_id'])) {
    require __DIR__ . '/../layouts/user_footer.php';
} else {
    require __DIR__ . '/../layouts/public_footer.php';
}
?>
