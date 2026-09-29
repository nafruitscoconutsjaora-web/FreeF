<?php
$pageTitle = "Shopping Cart - FF Panel Store";
$activeNav = 'cart';
$isLoggedIn = !empty($_SESSION['user_id']) && !empty($_SESSION['user_logged_in']);

if ($isLoggedIn) {
    require __DIR__ . '/../layouts/user_header.php';
} else {
    require __DIR__ . '/../layouts/public_header.php';
}
?>

<div class="max-w-6xl mx-auto px-4 lg:px-8 py-8 space-y-8">
    <div class="flex items-center justify-between border-b border-gray-800 pb-4">
        <div>
            <h1 class="text-2xl font-black text-white">Your Shopping Cart</h1>
            <p class="text-xs text-gray-400 mt-1">Review your selected Free Fire diamonds and enter player details before checkout.</p>
        </div>
        <a href="/services" class="text-xs text-rose-400 hover:underline">← Continue Shopping</a>
    </div>

    <?php if (empty($items)): ?>
        <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-16 text-center space-y-4">
            <i class="fa-solid fa-cart-shopping text-4xl text-gray-600"></i>
            <h3 class="text-base font-bold text-white">Your cart is empty</h3>
            <p class="text-xs text-gray-400">You haven't added any Free Fire packages or memberships to your cart yet.</p>
            <a href="/services" class="inline-block mt-3 px-6 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-lg transition">
                Browse Store Services
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Items List -->
            <div class="lg:col-span-2 space-y-4">
                <?php foreach ($items as $item): ?>
                <div class="bg-[#0B0E14] border border-gray-800 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl bg-[#121824] flex items-center justify-center text-rose-500 text-2xl shrink-0">
                            <i class="fa-solid fa-gem"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white"><?= htmlspecialchars($item['product']['name']) ?></h4>
                            <div class="text-xs text-rose-400 font-mono font-semibold mt-0.5">₹ <?= number_format($item['product']['price'], 2) ?> each</div>
                            
                            <div class="mt-2 flex items-center gap-2">
                                <label class="text-[11px] text-gray-400">Player UID:</label>
                                <input type="text" class="cart-uid-input bg-[#121824] border border-gray-800 rounded-lg px-2.5 py-1 text-xs text-white w-40 focus:outline-none focus:border-rose-500 font-mono"
                                       data-product-id="<?= $item['product']['id'] ?>"
                                       placeholder="e.g. 102938475"
                                       value="<?= htmlspecialchars($item['ff_uid'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between w-full sm:w-auto gap-4 pt-3 sm:pt-0 border-t sm:border-t-0 border-gray-800">
                        <div class="flex items-center gap-2 bg-[#121824] rounded-xl px-2 py-1 border border-gray-800">
                            <span class="text-xs text-gray-400">Qty:</span>
                            <span class="text-xs font-bold text-white font-mono"><?= $item['quantity'] ?></span>
                        </div>

                        <div class="text-right">
                            <div class="text-xs text-gray-400">Subtotal</div>
                            <div class="text-base font-black text-rose-400">₹ <?= number_format($item['subtotal'], 2) ?></div>
                        </div>

                        <button type="button" class="remove-cart-item text-gray-500 hover:text-rose-500 transition p-1"
                                data-product-id="<?= $item['product']['id'] ?>" title="Remove item">
                            <i class="fa-solid fa-trash-can text-sm"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Order Summary -->
            <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-6 space-y-6 self-start">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Order Summary</h3>

                <div class="space-y-3 text-xs border-b border-gray-800 pb-4">
                    <div class="flex justify-between text-gray-400">
                        <span>Items Subtotal</span>
                        <span class="text-white font-mono font-semibold">₹ <?= number_format($total, 2) ?></span>
                    </div>

                    <?php 
                    $discount = 0.00;
                    if (!empty($appliedCoupon)) {
                        $discount = ($appliedCoupon['type'] === 'percentage') 
                            ? ($total * ($appliedCoupon['value'] / 100))
                            : min($total, (float)$appliedCoupon['value']);
                    }
                    $finalTotal = max(0, $total - $discount);
                    ?>

                    <?php if ($discount > 0): ?>
                    <div class="flex justify-between text-emerald-400">
                        <span>Coupon Discount (<?= htmlspecialchars($appliedCoupon['code']) ?>)</span>
                        <span class="font-mono font-semibold">-₹ <?= number_format($discount, 2) ?></span>
                    </div>
                    <?php endif; ?>

                    <div class="flex justify-between text-gray-400">
                        <span>Delivery Fee</span>
                        <span class="text-emerald-400 font-semibold">FREE (Instant UID)</span>
                    </div>
                </div>

                <!-- Coupon Form -->
                <form id="couponForm" class="flex gap-2">
                    <input type="text" id="couponCode" placeholder="Enter coupon code" 
                           class="flex-1 bg-[#121824] border border-gray-800 rounded-xl px-3 py-2 text-xs text-white uppercase font-mono focus:outline-none focus:border-rose-500">
                    <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white rounded-xl text-xs font-bold transition">Apply</button>
                </form>

                <div class="flex justify-between items-center text-sm pt-2">
                    <span class="font-bold text-white">Final Total</span>
                    <span class="text-2xl font-black text-rose-500 font-mono">₹ <?= number_format($finalTotal, 2) ?></span>
                </div>

                <a href="/checkout" class="block text-center w-full py-3 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs shadow-lg shadow-rose-600/30 transition">
                    Proceed to Checkout →
                </a>

                <div class="flex items-center justify-center gap-2 text-[11px] text-gray-500 text-center">
                    <i class="fa-solid fa-shield-halved text-emerald-400"></i> 100% Encrypted & Safe Garena Recharges
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.querySelectorAll('.remove-cart-item').forEach(btn => {
    btn.addEventListener('click', async () => {
        const productId = btn.getAttribute('data-product-id');
        const res = await fetch('/cart/remove', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ product_id: productId })
        });
        const data = await res.json();
        if (data.success) {
            window.location.reload();
        }
    });
});

document.querySelectorAll('.cart-uid-input').forEach(input => {
    input.addEventListener('change', async () => {
        const productId = input.getAttribute('data-product-id');
        const ffUid = input.value;
        await fetch('/cart/update', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ product_id: productId, ff_uid: ffUid })
        });
    });
});

document.getElementById('couponForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const code = document.getElementById('couponCode').value;
    const res = await fetch('/cart/apply-coupon', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ code })
    });
    const data = await res.json();
    if (data.success) {
        alert(data.message);
        window.location.reload();
    } else {
        alert(data.message || 'Invalid coupon');
    }
});
</script>

<?php 
if ($isLoggedIn) {
    require __DIR__ . '/../layouts/user_footer.php';
} else {
    require __DIR__ . '/../layouts/public_footer.php';
}
?>
