<?php
$pageTitle = "Coupons & Offers - FF Panel Store";
$activeNav = 'coupons';
require __DIR__ . '/../layouts/user_header.php';
?>

<div class="max-w-4xl mx-auto px-4 py-8 space-y-6">
    <div>
        <h1 class="text-2xl font-black text-white">Active Coupons & Promo Offers</h1>
        <p class="text-xs text-gray-400 mt-1">Apply promo codes during checkout for instant discounts on your Free Fire diamonds.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php if (empty($coupons)): ?>
            <div class="col-span-2 bg-[#0B0E14] border border-gray-800 rounded-2xl p-10 text-center text-gray-400 text-xs">
                No active coupon codes at this moment. Stay tuned for holiday sales!
            </div>
        <?php else: ?>
            <?php foreach ($coupons as $c): ?>
            <div class="bg-[#0B0E14] border border-gray-800 hover:border-rose-900/60 rounded-2xl p-5 space-y-3 relative overflow-hidden transition">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="inline-block px-3 py-1 rounded-lg bg-rose-600/20 text-rose-400 border border-rose-600/30 font-mono font-black text-sm tracking-wider">
                            <?= htmlspecialchars($c['code']) ?>
                        </span>
                        <div class="font-bold text-white text-base mt-2">
                            <?= $c['discount_type'] === 'percentage' ? number_format($c['discount_value'], 0) . '% OFF' : '₹ ' . number_format($c['discount_value'], 2) . ' FLAT OFF' ?>
                        </div>
                    </div>
                    <button class="copy-coupon-btn px-3 py-1.5 rounded-xl bg-gray-800 hover:bg-gray-700 text-xs font-bold text-white transition" data-code="<?= htmlspecialchars($c['code']) ?>">
                        Copy Code
                    </button>
                </div>

                <div class="text-xs text-gray-400 space-y-1 pt-2 border-t border-gray-800">
                    <div>Minimum order: <span class="text-white font-semibold">₹ <?= number_format($c['min_order_amount'], 2) ?></span></div>
                    <?php if (!empty($c['max_discount_amount'])): ?>
                        <div>Maximum discount: <span class="text-white font-semibold">₹ <?= number_format($c['max_discount_amount'], 2) ?></span></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
document.querySelectorAll('.copy-coupon-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const code = btn.getAttribute('data-code');
        navigator.clipboard?.writeText(code);
        btn.textContent = 'Copied!';
        setTimeout(() => { btn.textContent = 'Copy Code'; }, 2000);
    });
});
</script>

<?php require __DIR__ . '/../layouts/user_footer.php'; ?>
