<?php
/**
 * Public Storefront Landing Page
 * Displays strictly public Free Fire services, categories, promotional banners, and quick recharge.
 * NO private user wallet, notifications, profile, or personal order widgets.
 */
$pageTitle = "FF Panel Store - Fast, Safe & Reliable Free Fire Services";
$activeNav = 'home';
$isLoggedIn = !empty($_SESSION['user_id']) && !empty($_SESSION['user_logged_in']);

require __DIR__ . '/layouts/public_header.php';
?>

<div class="max-w-[1580px] mx-auto px-4 lg:px-8 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- LEFT SIDEBAR: PUBLIC STORE NAVIGATION -->
        <aside class="hidden xl:block lg:col-span-2 space-y-6">
            <div class="bg-[#0B0E14] border border-gray-800/80 rounded-2xl p-3 space-y-1">
                <a href="/" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl bg-[#E11D48] text-white font-medium text-xs transition">
                    <i class="fa-solid fa-house w-4 text-center"></i> Home
                </a>
                <a href="/services" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-gray-800/40 text-xs transition">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-gamepad w-4 text-center"></i> Services
                    </div>
                    <span class="text-[9px] bg-rose-500/20 text-rose-400 font-bold px-1.5 py-0.5 rounded-full border border-rose-500/30">Top</span>
                </a>
                <a href="/services?category=diamonds" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-gray-800/40 text-xs transition">
                    <i class="fa-solid fa-gem w-4 text-center text-blue-400"></i> Diamonds
                </a>
                <a href="/services?category=membership" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-gray-800/40 text-xs transition">
                    <i class="fa-solid fa-crown w-4 text-center text-amber-400"></i> Membership
                </a>
                <a href="/services?category=elite-pass" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-gray-800/40 text-xs transition">
                    <i class="fa-solid fa-ticket w-4 text-center text-yellow-500"></i> Elite Pass
                </a>
                <a href="/about" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-gray-800/40 text-xs transition">
                    <i class="fa-solid fa-circle-info w-4 text-center"></i> About Us
                </a>
                <a href="/contact" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-gray-800/40 text-xs transition">
                    <i class="fa-solid fa-headset w-4 text-center"></i> 24/7 Support
                </a>
                <a href="/terms" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-gray-800/40 text-xs transition">
                    <i class="fa-solid fa-shield-halved w-4 text-center"></i> Guarantees
                </a>

                <?php if (!$isLoggedIn): ?>
                <div class="pt-3 border-t border-gray-800/80 mt-2 space-y-1.5">
                    <a href="/login" class="flex items-center justify-center gap-2 px-3.5 py-2 rounded-xl text-gray-300 hover:text-white hover:bg-gray-800/60 text-xs font-semibold transition border border-gray-800">
                        <i class="fa-solid fa-arrow-right-to-bracket text-[11px]"></i> Sign In
                    </a>
                    <a href="/register" class="flex items-center justify-center gap-2 px-3.5 py-2 rounded-xl text-white bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-xs font-bold shadow-md shadow-rose-600/30 transition">
                        <i class="fa-solid fa-user-plus text-[11px]"></i> Create Account
                    </a>
                </div>
                <?php else: ?>
                <div class="pt-3 border-t border-gray-800/80 mt-2">
                    <a href="/dashboard" class="flex items-center justify-center gap-2 px-3.5 py-2 rounded-xl text-white bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-xs font-bold shadow-md shadow-rose-600/30 transition">
                        <i class="fa-solid fa-chart-pie text-[11px]"></i> User Dashboard
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Public Promo Card -->
            <div class="rounded-2xl p-4 bg-gradient-to-b from-[#2B101E] to-[#120B15] border border-rose-900/40 relative overflow-hidden">
                <div class="w-10 h-10 rounded-full bg-rose-500/20 flex items-center justify-center text-rose-500 mb-3 shadow-inner">
                    <i class="fa-solid fa-bolt text-base"></i>
                </div>
                <h4 class="font-extrabold text-white text-xs leading-snug">Instant Top-Up Service</h4>
                <p class="text-[11px] text-gray-400 mt-1 mb-3">Recharge directly using your Player UID. No login or password required!</p>
                <a href="/services" class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 text-white font-bold text-xs hover:from-rose-500 hover:to-rose-400 transition shadow">
                    View Catalog <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="px-2">
                <p class="text-[11px] text-gray-500">© <?= date('Y') ?> FF Panel Store.</p>
                <p class="text-[10px] text-gray-600 mt-0.5">Fast • Safe • Reliable</p>
            </div>
        </aside>

        <!-- CENTER MAIN CONTENT: PUBLIC STOREFRONT -->
        <main class="lg:col-span-12 xl:col-span-7 space-y-7">
            
            <!-- Hero Banner -->
            <div class="relative rounded-3xl overflow-hidden bg-gradient-to-r from-[#170B16] via-[#1E0D1E] to-[#110714] border border-rose-900/30 p-6 md:p-8 flex flex-col md:flex-row items-center justify-between min-h-[260px] shadow-xl">
                <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-rose-600/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="space-y-3 z-10 max-w-md">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30">
                        <i class="fa-solid fa-fire text-rose-400"></i> Best Place For Free Fire Services
                    </span>
                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-black text-white leading-tight tracking-tight">
                        Get Your <span class="text-rose-500">Free Fire</span> Services <span class="text-rose-500">Instantly</span>
                    </h1>
                    <p class="text-gray-300 text-xs md:text-sm">
                        Diamonds, Memberships, Elite Pass, UID, and more — all at the best Indian prices with automated delivery.
                    </p>
                    <div class="pt-2 flex items-center gap-3">
                        <a href="/services" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 hover:to-rose-400 text-white font-bold text-xs shadow-lg shadow-rose-600/30 transition transform hover:-translate-y-0.5">
                            Explore Services <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                        <a href="/services?category=diamonds" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full bg-[#111723] hover:bg-gray-800 text-gray-300 hover:text-white font-semibold text-xs border border-gray-800 transition">
                            <i class="fa-solid fa-gem text-blue-400 text-xs"></i> Top Up Diamonds
                        </a>
                    </div>
                </div>

                <!-- Right Hero Graphic -->
                <div class="mt-6 md:mt-0 relative flex flex-col items-center text-center select-none">
                    <div class="w-44 h-44 rounded-full bg-gradient-to-tr from-rose-600/30 via-red-500/15 to-transparent blur-2xl absolute -top-4"></div>
                    <div class="relative z-10 py-2">
                        <div class="w-20 h-20 mx-auto rounded-2xl bg-gradient-to-tr from-rose-700 via-rose-600 to-red-500 flex items-center justify-center text-white shadow-2xl shadow-rose-600/50 mb-3 border border-rose-400/30">
                            <i class="fa-solid fa-user-ninja text-3xl"></i>
                        </div>
                        <span class="text-xl md:text-2xl font-black italic tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-white via-rose-200 to-rose-400 block drop-shadow">
                            FREE FIRE
                        </span>
                        <span class="text-xs font-black tracking-widest text-rose-400 uppercase block mt-0.5">
                            TOP UP & GAME SERVICES
                        </span>
                        <div class="mt-3 flex items-center justify-center gap-2 text-[10px] text-gray-300 bg-black/60 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-white/10 shadow">
                            <span class="text-rose-400 font-semibold">Fast Delivery</span> • <span>Safe</span> • <span>24/7 Support</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shop by Category -->
            <section id="categories" class="space-y-3.5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-fire text-rose-500"></i>
                        <h2 class="text-sm font-bold text-white">Shop by Category</h2>
                    </div>
                    <a href="/services" class="text-xs font-semibold text-rose-500 hover:text-rose-400 flex items-center gap-1 transition">
                        View All <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                <div class="grid grid-cols-3 sm:grid-cols-5 md:grid-cols-9 gap-2.5">
                    <?php 
                    $categoryIcons = [
                        'diamonds' => ['icon' => 'fa-gem', 'color' => 'text-blue-400'],
                        'membership' => ['icon' => 'fa-crown', 'color' => 'text-amber-400'],
                        'elite-pass' => ['icon' => 'fa-ticket', 'color' => 'text-yellow-500'],
                        'character' => ['icon' => 'fa-user-ninja', 'color' => 'text-rose-400'],
                        'weapon-skin' => ['icon' => 'fa-crosshairs', 'color' => 'text-red-400'],
                        'bundle' => ['icon' => 'fa-box', 'color' => 'text-purple-400'],
                        'pet' => ['icon' => 'fa-paw', 'color' => 'text-pink-400'],
                        'id-uid' => ['icon' => 'fa-id-card', 'color' => 'text-emerald-400'],
                        'special-offers' => ['icon' => 'fa-percent', 'color' => 'text-amber-500'],
                    ];
                    foreach ($categories as $cat): 
                        $iconMeta = $categoryIcons[$cat['slug']] ?? ['icon' => 'fa-gem', 'color' => 'text-rose-400'];
                    ?>
                    <a href="/services?category=<?= urlencode($cat['slug']) ?>" class="bg-[#111723] hover:bg-[#161F2E] border border-gray-800 hover:border-rose-500/50 rounded-2xl p-3 flex flex-col items-center justify-center text-center transition group">
                        <div class="w-10 h-10 rounded-xl bg-gray-900/80 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                            <i class="fa-solid <?= $iconMeta['icon'] ?> <?= $iconMeta['color'] ?> text-lg"></i>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-200 group-hover:text-white leading-tight"><?= htmlspecialchars($cat['name']) ?></span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Popular Services Section -->
            <section class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-star text-rose-500 text-sm"></i>
                            <h2 class="text-sm font-bold text-white">Popular Services</h2>
                        </div>
                        <p class="text-xs text-gray-400">Most purchased services by our customers</p>
                    </div>

                    <!-- Filter pills -->
                    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs">
                        <a href="/" class="px-3.5 py-1 rounded-full font-semibold bg-[#E11D48] text-white">All</a>
                        <a href="/services?category=diamonds" class="px-3 py-1 rounded-full font-medium text-gray-400 hover:text-white bg-[#111723] border border-gray-800 transition">Diamonds</a>
                        <a href="/services?category=membership" class="px-3 py-1 rounded-full font-medium text-gray-400 hover:text-white bg-[#111723] border border-gray-800 transition">Membership</a>
                        <a href="/services?category=elite-pass" class="px-3 py-1 rounded-full font-medium text-gray-400 hover:text-white bg-[#111723] border border-gray-800 transition">Elite Pass</a>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
                    <?php if (empty($products)): ?>
                        <div class="col-span-4 bg-[#111723] border border-gray-800 rounded-2xl p-12 text-center text-gray-400 space-y-2">
                            <i class="fa-solid fa-box-open text-3xl text-gray-600"></i>
                            <p class="font-semibold text-sm text-white">No products available.</p>
                            <p class="text-xs text-gray-500">Products are currently being updated. Please check back shortly.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($products as $p): ?>
                        <div class="bg-[#111723] border border-gray-800 hover:border-rose-500/40 rounded-2xl p-4 flex flex-col justify-between transition group">
                            <!-- Product Image/Banner Box -->
                            <div class="w-full h-28 rounded-xl bg-gradient-to-br from-[#1C162E] via-[#161B2B] to-[#0E131E] border border-gray-800/80 flex items-center justify-center relative overflow-hidden mb-3">
                                <?php if ($p['category_slug'] === 'diamonds'): ?>
                                    <div class="flex items-center gap-1.5 text-blue-400">
                                        <i class="fa-solid fa-gem text-3xl filter drop-shadow-[0_0_10px_rgba(96,165,250,0.5)]"></i>
                                    </div>
                                    <span class="absolute right-2 bottom-1.5 text-[9px] font-black tracking-widest text-white/40">FREE FIRE</span>
                                <?php elseif ($p['category_slug'] === 'membership'): ?>
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-500 to-yellow-300 flex items-center justify-center font-black text-black text-lg shadow-lg shadow-amber-500/30">
                                        <?= strtoupper(substr($p['name'], 0, 1)) ?>
                                    </div>
                                <?php elseif ($p['category_slug'] === 'elite-pass'): ?>
                                    <div class="flex flex-col items-center text-yellow-400">
                                        <i class="fa-solid fa-ticket text-3xl filter drop-shadow-[0_0_8px_rgba(250,204,21,0.5)]"></i>
                                        <span class="text-[9px] font-extrabold tracking-wider mt-1">ELITE PASS</span>
                                    </div>
                                <?php else: ?>
                                    <div class="flex items-center gap-1 text-rose-400">
                                        <i class="fa-solid fa-user-ninja text-3xl"></i>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Product Info -->
                            <div class="space-y-1.5">
                                <h3 class="font-bold text-white text-xs leading-snug group-hover:text-rose-400 transition line-clamp-1"><?= htmlspecialchars($p['name']) ?></h3>
                                <p class="text-[11px] text-gray-400 line-clamp-1"><?= htmlspecialchars($p['short_description'] ?? 'Instant top-up delivered to UID.') ?></p>

                                <!-- Badges -->
                                <div class="flex items-center gap-1.5 pt-1">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-cyan-950/60 text-cyan-400 border border-cyan-800/40">Instant</span>
                                    <?php if (!empty($p['badge'])): ?>
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-rose-950/60 text-rose-400 border border-rose-800/40"><?= htmlspecialchars($p['badge']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Price and Action -->
                            <div class="pt-3 mt-2 border-t border-gray-800/80">
                                <div class="flex items-baseline gap-2 mb-2">
                                    <span class="text-sm font-extrabold text-white font-mono">₹ <?= number_format($p['price'], 2) ?></span>
                                    <?php if (!empty($p['original_price'])): ?>
                                        <span class="text-[10px] text-gray-500 line-through font-mono">₹ <?= number_format($p['original_price'], 2) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <a href="/service/<?= urlencode($p['slug']) ?>" class="flex-1 py-1.5 px-2 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs flex items-center justify-center gap-1 transition shadow">
                                        <i class="fa-solid fa-cart-shopping text-[10px]"></i> Buy Now
                                    </a>
                                    <a href="/service/<?= urlencode($p['slug']) ?>" class="p-1.5 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white text-xs" title="View details">
                                        <i class="fa-solid fa-eye text-[11px]"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Store Information & Why Choose Us -->
            <section class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-6 space-y-4">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-rose-500 text-sm"></i>
                    <h3 class="text-sm font-bold text-white">Why Choose FF Panel Store?</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                    <div class="p-4 bg-[#111723] rounded-2xl border border-gray-800/80 space-y-1.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-sm font-bold">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <h4 class="text-xs font-bold text-white">Instant UID Delivery</h4>
                        <p class="text-[11px] text-gray-400 leading-relaxed">Direct automated top-up delivered to your Free Fire Player ID in under 60 seconds.</p>
                    </div>

                    <div class="p-4 bg-[#111723] rounded-2xl border border-gray-800/80 space-y-1.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-400 flex items-center justify-center text-sm font-bold">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <h4 class="text-xs font-bold text-white">100% Account Safe</h4>
                        <p class="text-[11px] text-gray-400 leading-relaxed">No password or game login credentials needed. Never risk account bans or security flags.</p>
                    </div>

                    <div class="p-4 bg-[#111723] rounded-2xl border border-gray-800/80 space-y-1.5">
                        <div class="w-8 h-8 rounded-lg bg-rose-500/10 text-rose-500 flex items-center justify-center text-sm font-bold">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <h4 class="text-xs font-bold text-white">24/7 Dedicated Support</h4>
                        <p class="text-[11px] text-gray-400 leading-relaxed">Our customer support is always active to assist you with order tracking and inquiries.</p>
                    </div>
                </div>
            </section>

            <!-- Final Call To Action -->
            <section class="bg-gradient-to-r from-rose-950/60 via-purple-950/40 to-[#0B0E14] border border-rose-900/40 rounded-3xl p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="space-y-1.5 text-center sm:text-left">
                    <h3 class="text-lg sm:text-xl font-black text-white">Ready to Level Up Your Free Fire Account?</h3>
                    <p class="text-xs text-gray-300">Recharge diamonds now and unlock the latest characters, gun skins, and elite passes.</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="/services" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs shadow-lg transition">
                        Shop All Services
                    </a>
                </div>
            </section>
        </main>

        <!-- RIGHT SIDEBAR: PUBLIC STORE WIDGETS -->
        <aside class="lg:col-span-12 xl:col-span-3 space-y-6">
            
            <!-- Widget 1: Quick Recharge -->
            <div class="bg-[#0B0E14] border border-gray-800 rounded-2xl p-5 space-y-4 shadow-lg">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-rose-500/10 text-rose-500 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-bolt text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-sm">Quick Recharge</h3>
                        <p class="text-[11px] text-gray-400">Top up your Free Fire account instantly</p>
                    </div>
                </div>

                <form id="quickRechargeForm" action="/quick-recharge" method="POST" class="space-y-3">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Enter Player UID</label>
                        <input type="text" name="uid" required placeholder="e.g. 5482910482" 
                               class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3.5 py-2 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-rose-500 transition font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Select Diamonds Amount</label>
                        <select name="product_id" required class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-rose-500 transition">
                            <?php foreach ($products as $pr): ?>
                                <?php if ($pr['category_slug'] === 'diamonds' || $pr['is_quick_recharge']): ?>
                                    <option value="<?= $pr['id'] ?>"><?= htmlspecialchars($pr['name']) ?> - ₹ <?= number_format($pr['price'], 2) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 hover:to-rose-400 text-white font-bold text-xs flex items-center justify-center gap-1.5 transition shadow-lg shadow-rose-600/30">
                        Recharge Now <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </form>
            </div>

            <!-- Widget 2: Public Store Guarantees -->
            <div class="bg-[#0B0E14] border border-gray-800 rounded-2xl p-5 space-y-3.5 shadow-lg">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-certificate text-rose-500 text-sm"></i>
                    <h3 class="font-bold text-white text-sm">Store Guarantees</h3>
                </div>

                <div class="space-y-2.5 text-xs">
                    <div class="flex items-center gap-2.5 text-gray-300">
                        <i class="fa-solid fa-circle-check text-emerald-400"></i>
                        <span>100% Garena Direct UID Top-Up</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-gray-300">
                        <i class="fa-solid fa-circle-check text-emerald-400"></i>
                        <span>Instant UPI & QR Code Payments</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-gray-300">
                        <i class="fa-solid fa-circle-check text-emerald-400"></i>
                        <span>No Password Or Login Needed</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-gray-300">
                        <i class="fa-solid fa-circle-check text-emerald-400"></i>
                        <span>Official Automated API Delivery</span>
                    </div>
                </div>
            </div>

            <!-- Widget 3: Promotional Coupons Card -->
            <div class="rounded-2xl p-5 bg-gradient-to-br from-[#2D101E] to-[#120B15] border border-rose-900/40 relative overflow-hidden shadow-lg">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="font-extrabold text-white text-sm">Special <span class="text-rose-400">Offers!</span></h4>
                        <p class="text-xs text-gray-300 mt-1">Get bonus diamonds on your recharges using coupon codes at checkout.</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center font-bold text-lg shrink-0">
                        <i class="fa-solid fa-gift"></i>
                    </div>
                </div>
                <div class="pt-4">
                    <a href="/services" class="inline-flex items-center justify-center gap-1.5 w-full py-2 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 hover:to-rose-400 text-white font-bold text-xs transition shadow">
                        Explore Offers <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

        </aside>
    </div>
</div>

<?php require __DIR__ . '/layouts/public_footer.php'; ?>
