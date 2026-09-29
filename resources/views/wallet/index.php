<?php
$pageTitle = "My Wallet - FF Panel Store";
require __DIR__ . '/../layouts/user_header.php';
?>

<div class="max-w-4xl mx-auto px-4 py-8 space-y-8">
    <!-- Wallet Card -->
    <div class="bg-gradient-to-r from-[#1E0F1E] via-[#161224] to-[#0E1524] border border-rose-900/40 rounded-3xl p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <span class="text-xs uppercase font-extrabold tracking-wider text-rose-400">Available Funds</span>
            <div class="text-4xl font-black text-white mt-1">₹ <?= number_format($balance ?? 0.00, 2) ?></div>
            <p class="text-xs text-gray-400 mt-2">Use your wallet for 1-click Free Fire top-ups without payment delay.</p>
        </div>

        <div class="w-full md:w-auto bg-[#0B0E14]/80 backdrop-blur-md border border-gray-800 rounded-2xl p-4 space-y-3 shrink-0">
            <h4 class="text-xs font-bold text-gray-300">Add Balance (Razorpay)</h4>
            <div class="flex items-center gap-2">
                <input type="number" id="topupAmount" min="10" step="10" value="100" 
                       class="w-32 bg-[#111723] border border-gray-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:border-rose-500">
                <button id="rzpTopupBtn" class="px-4 py-2 bg-[#E11D48] hover:bg-rose-700 text-white font-bold text-xs rounded-xl transition shadow">
                    Top Up
                </button>
            </div>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-6 space-y-4">
        <h3 class="font-bold text-white text-base">Transaction History</h3>

        <?php if (empty($transactions)): ?>
            <div class="py-12 text-center text-gray-500 text-sm">
                No wallet transactions found.
            </div>
        <?php else: ?>
            <div class="divide-y divide-gray-800/80">
                <?php foreach ($transactions as $tx): ?>
                <div class="py-3.5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl <?= $tx['type'] === 'credit' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' ?> flex items-center justify-center font-bold">
                            <i class="fa-solid <?= $tx['type'] === 'credit' ? 'fa-arrow-down-left' : 'fa-arrow-up-right' ?>"></i>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-white block"><?= htmlspecialchars($tx['description']) ?></span>
                            <span class="text-[11px] text-gray-500"><?= date('d M Y, h:i A', strtotime($tx['created_at'])) ?></span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-bold <?= $tx['type'] === 'credit' ? 'text-emerald-400' : 'text-rose-400' ?>">
                            <?= $tx['type'] === 'credit' ? '+' : '-' ?> ₹ <?= number_format($tx['amount'], 2) ?>
                        </span>
                        <span class="text-[10px] text-gray-500 block">Bal: ₹ <?= number_format($tx['closing_balance'], 2) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layouts/user_footer.php'; ?>
