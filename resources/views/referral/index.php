<?php
$pageTitle = "Referral Program - FF Panel Store";
$activeNav = 'referral';
require __DIR__ . '/../layouts/user_header.php';
?>

<div class="max-w-6xl mx-auto px-4 lg:px-8 py-8 space-y-8">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-rose-950/60 via-purple-950/40 to-[#0B0E14] border border-rose-900/40 rounded-3xl p-6 sm:p-8 relative overflow-hidden">
        <div class="max-w-xl space-y-3">
            <span class="px-3 py-1 rounded-full bg-rose-500/20 text-rose-400 text-xs font-bold border border-rose-500/30">
                <i class="fa-solid fa-gift mr-1"></i> Earn Commission
            </span>
            <h1 class="text-2xl sm:text-3xl font-black text-white">Invite Friends & Earn Real Wallet Cash</h1>
            <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                Share your referral link with squad mates or on WhatsApp. Whenever they make their first diamond or membership recharge, you earn an instant 5% bonus credit directly in your wallet!
            </p>
        </div>
    </div>

    <!-- Referral Stats & Code Box -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-[#0B0E14] border border-gray-800 rounded-3xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider">Your Referral Link</h3>
            
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <input type="text" id="refLinkInput" readonly value="<?= htmlspecialchars($referralLink ?? '') ?>" 
                           class="flex-1 bg-[#121824] border border-gray-800 rounded-2xl px-4 py-3 text-xs text-rose-400 font-mono focus:outline-none">
                    <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('refLinkInput').value); alert('Referral link copied to clipboard!');"
                            class="px-5 py-3 rounded-2xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-lg transition flex items-center gap-1.5 shrink-0">
                        <i class="fa-regular fa-copy"></i> Copy Link
                    </button>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <span class="text-xs text-gray-400">Your Unique Code:</span>
                    <span class="px-3 py-1 rounded-xl bg-gray-800 text-white font-mono font-bold text-xs"><?= htmlspecialchars($referralCode ?? 'FF000') ?></span>
                </div>
            </div>
        </div>

        <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-6 flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Total Referral Earnings</span>
            <div>
                <div class="text-3xl font-black text-emerald-400">₹ <?= number_format($totalEarnings ?? 0.00, 2) ?></div>
                <p class="text-[11px] text-gray-500 mt-1">Credited directly to your store wallet</p>
            </div>
            <a href="/wallet" class="mt-4 inline-block text-center py-2.5 rounded-xl bg-[#121824] border border-gray-800 hover:border-gray-700 text-xs font-bold text-gray-300 hover:text-white transition">
                View Wallet Balance →
            </a>
        </div>
    </div>

    <!-- Referred Users Table -->
    <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-6 space-y-4">
        <h3 class="text-sm font-bold text-white">Referred Squad Players</h3>

        <?php if (empty($referrals)): ?>
            <div class="p-12 text-center text-xs text-gray-500">
                <i class="fa-solid fa-user-plus text-3xl mb-3 text-gray-600 block"></i>
                You haven't referred any players yet. Share your link above to start earning!
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#121824] text-gray-400 uppercase text-[10px]">
                        <tr>
                            <th class="p-3">Player</th>
                            <th class="p-3">Joined Date</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-right">Commission Earned</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        <?php foreach ($referrals as $ref): ?>
                        <tr class="hover:bg-gray-800/20">
                            <td class="p-3 font-semibold text-white"><?= htmlspecialchars($ref['referred_user_name'] ?? 'Player') ?></td>
                            <td class="p-3 text-gray-400"><?= date('M d, Y', strtotime($ref['joined_at'] ?? 'now')) ?></td>
                            <td class="p-3">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold <?= $ref['status'] === 'completed' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-yellow-500/10 text-yellow-400' ?>">
                                    <?= ucfirst($ref['status'] ?? 'pending') ?>
                                </span>
                            </td>
                            <td class="p-3 text-right font-mono font-bold text-emerald-400">
                                +₹ <?= number_format($ref['total_earned'] ?? 0.00, 2) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layouts/user_footer.php'; ?>
