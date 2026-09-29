<?php
$pageTitle = "Notifications - FF Panel Store";
$activeNav = 'notifications';
require __DIR__ . '/../layouts/user_header.php';
?>

<div class="max-w-4xl mx-auto px-4 py-8 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-white">Notifications</h1>
            <p class="text-xs text-gray-400 mt-1">Real-time status updates for your orders, wallet top-ups, and announcements.</p>
        </div>
    </div>

    <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-6">
        <?php if (empty($notifications)): ?>
            <div class="py-12 text-center text-gray-500 text-xs">
                You have no notifications right now.
            </div>
        <?php else: ?>
            <div class="divide-y divide-gray-800">
                <?php foreach ($notifications as $n): ?>
                <div class="py-4 flex items-start gap-3.5 <?= $n['is_read'] ? 'opacity-70' : '' ?>">
                    <div class="w-8 h-8 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid <?= $n['type'] === 'order' ? 'fa-receipt' : ($n['type'] === 'wallet' ? 'fa-wallet' : 'fa-bell') ?> text-xs"></i>
                    </div>
                    <div class="flex-1 space-y-0.5">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-white text-xs"><?= htmlspecialchars($n['title']) ?></h4>
                            <span class="text-[10px] text-gray-500"><?= date('d M, h:i A', strtotime($n['created_at'])) ?></span>
                        </div>
                        <p class="text-xs text-gray-400 leading-relaxed"><?= htmlspecialchars($n['message']) ?></p>
                        <?php if (!empty($n['action_url'])): ?>
                            <a href="<?= htmlspecialchars($n['action_url']) ?>" class="text-[11px] text-rose-400 hover:underline inline-block pt-1">View Details →</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layouts/user_footer.php'; ?>
