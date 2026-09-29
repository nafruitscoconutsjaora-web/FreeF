<?php
$pageTitle = htmlspecialchars($product['name']) . " - FF Panel Store";
require __DIR__ . '/../layouts/header.php';
?>

<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
        
        <!-- Product Visual Card -->
        <div class="bg-[#111723] border border-gray-800 rounded-3xl p-8 flex flex-col items-center justify-center text-center min-h-[350px] relative overflow-hidden">
            <div class="w-32 h-32 rounded-3xl bg-gradient-to-tr from-rose-600/30 to-blue-600/20 flex items-center justify-center mb-6">
                <i class="fa-solid fa-gem text-6xl text-blue-400 filter drop-shadow-[0_0_15px_rgba(96,165,250,0.6)]"></i>
            </div>
            <span class="text-xs font-bold text-rose-400 bg-rose-500/10 border border-rose-500/30 px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                <?= htmlspecialchars($product['category_name']) ?>
            </span>
            <h1 class="text-2xl font-black text-white"><?= htmlspecialchars($product['name']) ?></h1>
            <p class="text-gray-400 text-sm mt-2 max-w-sm"><?= htmlspecialchars($product['short_description'] ?? '') ?></p>
        </div>

        <!-- Product Purchase Card -->
        <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-6 sm:p-8 space-y-6">
            <div>
                <div class="flex items-baseline gap-3">
                    <span class="text-3xl font-black text-white">₹ <?= number_format($product['price'], 2) ?></span>
                    <?php if (!empty($product['original_price'])): ?>
                        <span class="text-base text-gray-500 line-through">₹ <?= number_format($product['original_price'], 2) ?></span>
                    <?php endif; ?>
                </div>
                <p class="text-xs text-emerald-400 font-semibold mt-1">Instant delivery to your Free Fire account</p>
            </div>

            <form action="/cart/add" method="POST" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                <div>
                    <label class="block text-xs font-bold text-gray-300 mb-1">Free Fire Player UID *</label>
                    <input type="text" name="ff_uid" required placeholder="e.g. 1234567890" 
                           value="<?= htmlspecialchars($_SESSION['user']['ff_uid'] ?? '') ?>"
                           class="w-full bg-[#111723] border border-gray-800 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-rose-500 transition">
                    <span class="text-[11px] text-gray-500 mt-1 block">You can find this in your Free Fire profile page.</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-300 mb-1">Quantity</label>
                    <input type="number" name="quantity" min="1" max="10" value="1" 
                           class="w-32 bg-[#111723] border border-gray-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500 transition">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 hover:to-rose-400 text-white font-bold text-sm flex items-center justify-center gap-2 transition shadow-lg shadow-rose-600/30">
                        <i class="fa-solid fa-cart-shopping"></i> Add to Cart & Checkout
                    </button>
                </div>
            </form>

            <div class="border-t border-gray-800 pt-4 space-y-2 text-xs text-gray-400">
                <div class="flex items-center gap-2"><i class="fa-solid fa-shield-halved text-emerald-400"></i> 100% Safe & Anti-Ban Protection</div>
                <div class="flex items-center gap-2"><i class="fa-solid fa-clock text-blue-400"></i> Average Delivery Speed: Under 60 Seconds</div>
            </div>
        </div>

    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
