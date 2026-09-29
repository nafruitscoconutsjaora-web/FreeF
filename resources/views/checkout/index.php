<?php
$pageTitle = "Checkout & Payment - FF Panel Store";
$activeNav = 'checkout';
require __DIR__ . '/../layouts/user_header.php';
?>

<div class="max-w-6xl mx-auto px-4 lg:px-8 py-8 space-y-8">
    <div class="border-b border-gray-800 pb-4">
        <h1 class="text-2xl font-black text-white">Complete Your Order</h1>
        <p class="text-xs text-gray-400 mt-1">Review your Free Fire Player UID and choose your payment method.</p>
    </div>

    <form id="checkoutForm" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Free Fire Player UID Verification Box -->
            <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-6 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-rose-500/20 text-rose-500 flex items-center justify-center font-bold text-sm">1</div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Free Fire Player Information</h3>
                </div>

                <div class="p-4 bg-[#121824] rounded-2xl border border-gray-800 space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1.5">Player UID *</label>
                        <input type="text" id="checkoutUid" name="ff_uid" required placeholder="Enter 9-10 digit Garena Free Fire UID"
                               value="<?= htmlspecialchars($items[0]['ff_uid'] ?? '') ?>"
                               class="w-full bg-[#0B0E14] border border-gray-800 rounded-xl px-4 py-2.5 text-xs text-white font-mono focus:outline-none focus:border-rose-500">
                    </div>
                    <p class="text-[11px] text-gray-500">
                        <i class="fa-solid fa-circle-info text-rose-400 mr-1"></i> Ensure your Free Fire UID is exact. Top-ups are delivered immediately to the account matching this ID.
                    </p>
                </div>
            </div>

            <!-- Payment Method Selector -->
            <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-6 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-rose-500/20 text-rose-500 flex items-center justify-center font-bold text-sm">2</div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Select Payment Method</h3>
                </div>

                <div class="space-y-3">
                    <!-- Wallet Option -->
                    <label class="flex items-center justify-between p-4 rounded-2xl border border-gray-800 hover:border-rose-500/50 cursor-pointer bg-[#121824] transition">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_method" value="wallet" <?= ($walletBalance >= $finalTotal) ? 'checked' : 'disabled' ?> class="accent-rose-500">
                            <div>
                                <span class="text-xs font-bold text-white block">Account Wallet Balance</span>
                                <span class="text-[11px] <?= ($walletBalance >= $finalTotal) ? 'text-emerald-400' : 'text-gray-500' ?>">
                                    Available: ₹ <?= number_format($walletBalance, 2) ?>
                                    <?php if ($walletBalance < $finalTotal): ?> (Insufficient balance - Add funds in Wallet)<?php endif; ?>
                                </span>
                            </div>
                        </div>
                        <i class="fa-solid fa-wallet text-xl text-rose-400"></i>
                    </label>

                    <!-- Razorpay / UPI Gateway Option -->
                    <label class="flex items-center justify-between p-4 rounded-2xl border border-gray-800 hover:border-rose-500/50 cursor-pointer bg-[#121824] transition">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_method" value="razorpay" <?= ($walletBalance < $finalTotal) ? 'checked' : '' ?> class="accent-rose-500">
                            <div>
                                <span class="text-xs font-bold text-white block">Razorpay / Instant UPI Gateway</span>
                                <span class="text-[11px] text-gray-400">GPay, PhonePe, Paytm, Cards & NetBanking</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-gray-400 text-sm">
                            <i class="fa-solid fa-qrcode text-rose-400"></i>
                            <i class="fa-brands fa-google-pay"></i>
                            <i class="fa-brands fa-cc-visa"></i>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-6 space-y-6 self-start">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider">Summary</h3>

            <div class="space-y-3 text-xs border-b border-gray-800 pb-4">
                <?php foreach ($items as $item): ?>
                <div class="flex justify-between items-center text-gray-300">
                    <span class="truncate max-w-[180px]"><?= htmlspecialchars($item['product']['name']) ?> (x<?= $item['quantity'] ?>)</span>
                    <span class="font-mono text-white">₹ <?= number_format($item['subtotal'], 2) ?></span>
                </div>
                <?php endforeach; ?>

                <div class="pt-2 border-t border-gray-800/60 flex justify-between text-gray-400">
                    <span>Subtotal</span>
                    <span class="text-white font-mono">₹ <?= number_format($subtotal, 2) ?></span>
                </div>

                <?php if ($discount > 0): ?>
                <div class="flex justify-between text-emerald-400">
                    <span>Discount</span>
                    <span class="font-mono">-₹ <?= number_format($discount, 2) ?></span>
                </div>
                <?php endif; ?>

                <div class="flex justify-between text-gray-400">
                    <span>Delivery</span>
                    <span class="text-emerald-400">Instant Automated</span>
                </div>
            </div>

            <div class="flex justify-between items-center text-sm">
                <span class="font-bold text-white">Total Amount</span>
                <span class="text-2xl font-black text-rose-500 font-mono">₹ <?= number_format($finalTotal, 2) ?></span>
            </div>

            <button type="submit" id="payBtn" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs shadow-lg shadow-rose-600/30 transition">
                Confirm & Pay ₹ <?= number_format($finalTotal, 2) ?>
            </button>

            <div class="text-[11px] text-gray-500 text-center space-y-1">
                <p><i class="fa-solid fa-lock text-emerald-400 mr-1"></i> 256-bit SSL Secure Checkout</p>
                <p>Transactions verified directly by Razorpay</p>
            </div>
        </div>
    </form>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('checkoutForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('payBtn');
    btn.disabled = true;
    btn.textContent = 'Processing order...';

    const ffUid = document.getElementById('checkoutUid').value;
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value || 'wallet';

    if (!ffUid) {
        alert('Please enter your Free Fire Player UID.');
        btn.disabled = false;
        btn.textContent = 'Confirm & Pay';
        return;
    }

    const res = await fetch('/checkout/process', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            ff_uid: ffUid,
            payment_method: paymentMethod
        })
    });

    const data = await res.json();
    if (!data.success) {
        alert(data.message || 'Error processing checkout.');
        btn.disabled = false;
        btn.textContent = 'Confirm & Pay';
        return;
    }

    if (data.payment_method === 'wallet') {
        alert('Order placed successfully! Order ID: #' + data.order_number);
        window.location.href = '/orders/' + data.order_id;
    } else if (data.payment_method === 'razorpay') {
        const options = {
            key: data.razorpay_key,
            amount: data.amount,
            currency: 'INR',
            name: 'FF Panel Store',
            description: 'Free Fire Direct UID Top-Up',
            order_id: data.razorpay_order_id,
            handler: async function (response) {
                const verifyRes = await fetch('/payment/razorpay/verify', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_signature: response.razorpay_signature,
                        order_id: data.order_id
                    })
                });
                const verifyData = await verifyRes.json();
                if (verifyData.success) {
                    alert('Payment successful! Your Free Fire recharge is being processed.');
                    window.location.href = '/orders/' + data.order_id;
                } else {
                    alert(verifyData.message || 'Payment verification failed.');
                }
            },
            prefill: {
                name: '<?= htmlspecialchars($_SESSION['user_name'] ?? 'Player') ?>',
                email: '<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>'
            },
            theme: {
                color: '#E11D48'
            }
        };
        const rzp = new Razorpay(options);
        rzp.open();
        btn.disabled = false;
        btn.textContent = 'Confirm & Pay';
    }
});
</script>

<?php require __DIR__ . '/../layouts/user_footer.php'; ?>
