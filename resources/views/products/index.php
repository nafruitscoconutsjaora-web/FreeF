<?php
$pageTitle = "All Services & Free Fire Diamond Recharges - FF Panel Store";
$activeNav = 'services';
$isLoggedIn = !empty($_SESSION['user_id']);

if ($isLoggedIn) {
    require __DIR__ . '/../layouts/user_header.php';
} else {
    require __DIR__ . '/../layouts/public_header.php';
}
?>

<div class="max-w-[1580px] mx-auto px-4 lg:px-8 py-8 space-y-8">
    <!-- Header Title & Search -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white">Free Fire Services Catalog</h1>
            <p class="text-xs sm:text-sm text-gray-400 mt-1">Official Garena UID direct recharges, Elite Passes, and exclusive character packages.</p>
        </div>

        <form action="/services" method="GET" class="flex items-center gap-2">
            <?php if (!empty($activeCategory)): ?>
                <input type="hidden" name="category" value="<?= htmlspecialchars($activeCategory) ?>">
            <?php endif; ?>
            <div class="relative w-64 sm:w-80">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-xs text-gray-400"></i>
                <input type="text" name="q" value="<?= htmlspecialchars($searchQuery ?? '') ?>" placeholder="Search diamonds, weekly pass, crates..."
                       class="w-full bg-[#121824] border border-gray-800 rounded-xl pl-9 pr-3.5 py-2.5 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-rose-500">
            </div>
            <button type="submit" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-500 text-white rounded-xl text-xs font-bold transition">
                Search
            </button>
        </form>
    </div>

    <!-- Category Filter Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none text-xs">
        <a href="/services" 
           class="px-4 py-2 rounded-full font-semibold transition shrink-0 <?= empty($activeCategory) ? 'bg-[#E11D48] text-white shadow-md' : 'bg-[#0B0E14] text-gray-400 hover:text-white border border-gray-800' ?>">
            All Services
        </a>
        <?php foreach ($categories as $cat): ?>
            <a href="/services?category=<?= urlencode($cat['slug']) ?>" 
               class="px-4 py-2 rounded-full font-semibold transition shrink-0 <?= ($activeCategory ?? '') === $cat['slug'] ? 'bg-[#E11D48] text-white shadow-md' : 'bg-[#0B0E14] text-gray-400 hover:text-white border border-gray-800' ?>">
                <?= htmlspecialchars($cat['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Products Grid -->
    <?php if (empty($products)): ?>
        <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-16 text-center space-y-3">
            <i class="fa-solid fa-box-open text-4xl text-gray-600"></i>
            <h3 class="text-base font-bold text-white">No products found</h3>
            <p class="text-xs text-gray-400">There are no services matching your active filter. Try viewing all services or clearing your search.</p>
            <a href="/services" class="inline-block mt-2 px-4 py-2 rounded-xl bg-rose-600 text-white font-bold text-xs">Reset Filters</a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            <?php foreach ($products as $p): ?>
            <div class="bg-[#0B0E14] border border-gray-800 hover:border-rose-500/40 rounded-2xl p-4 flex flex-col justify-between transition-all group">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider bg-[#121824] px-2.5 py-1 rounded-lg">
                            <?= htmlspecialchars($p['category_name'] ?? 'Free Fire') ?>
                        </span>
                        <?php if (!empty($p['badge'])): ?>
                            <span class="text-[10px] font-bold text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2 py-0.5 rounded-full">
                                <?= htmlspecialchars($p['badge']) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="aspect-video bg-[#121824] rounded-xl flex items-center justify-center p-4 overflow-hidden relative">
                        <i class="fa-solid fa-gem text-4xl text-rose-500 group-hover:scale-110 transition duration-300"></i>
                    </div>

                    <div>
                        <h3 class="text-sm font-bold text-white group-hover:text-rose-400 transition"><?= htmlspecialchars($p['name']) ?></h3>
                        <p class="text-xs text-gray-400 mt-1 line-clamp-2"><?= htmlspecialchars($p['short_description'] ?? 'Instant top-up delivered directly to player UID.') ?></p>
                    </div>
                </div>

                <div class="pt-4 mt-3 border-t border-gray-800/80 flex items-center justify-between">
                    <div>
                        <div class="text-xs text-gray-500 line-through">₹ <?= number_format($p['original_price'] ?? ($p['price'] * 1.2), 2) ?></div>
                        <div class="text-lg font-black text-rose-400">₹ <?= number_format($p['price'], 2) ?></div>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <a href="/service/<?= htmlspecialchars($p['slug']) ?>" class="p-2.5 rounded-xl bg-gray-800 hover:bg-gray-700 text-white text-xs font-semibold" title="View Details">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <form action="/cart/add" method="POST">
                            <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="px-3.5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs flex items-center gap-1.5 shadow-md">
                                <i class="fa-solid fa-cart-plus"></i> Buy
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php 
if ($isLoggedIn) {
    require __DIR__ . '/../layouts/user_footer.php';
} else {
    require __DIR__ . '/../layouts/public_footer.php';
}
?>
