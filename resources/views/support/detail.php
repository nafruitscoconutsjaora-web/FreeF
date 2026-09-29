<?php
$pageTitle = "Ticket #" . htmlspecialchars($ticket['ticket_number']) . " - FF Panel Store";
$activeNav = 'support';
require __DIR__ . '/../layouts/user_header.php';
?>

<div class="max-w-4xl mx-auto px-4 py-8 space-y-6">
    <div class="flex items-center justify-between border-b border-gray-800 pb-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="font-mono text-rose-400 font-bold text-sm">#<?= htmlspecialchars($ticket['ticket_number']) ?></span>
                <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-full
                    <?= $ticket['status'] === 'resolved' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-blue-500/20 text-blue-400' ?>">
                    <?= htmlspecialchars($ticket['status']) ?>
                </span>
            </div>
            <h1 class="text-xl font-black text-white mt-1"><?= htmlspecialchars($ticket['subject']) ?></h1>
        </div>
        <a href="/support" class="text-xs text-gray-400 hover:text-white">← Back to Tickets</a>
    </div>

    <!-- Message Thread -->
    <div class="space-y-4">
        <?php foreach ($messages ?? [] as $m): ?>
            <div class="bg-[#0B0E14] border <?= $m['sender_type'] === 'admin' ? 'border-rose-900/50 bg-[#160E18]' : 'border-gray-800' ?> rounded-2xl p-5 space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold <?= $m['sender_type'] === 'admin' ? 'text-rose-400' : 'text-gray-200' ?>">
                        <i class="fa-solid <?= $m['sender_type'] === 'admin' ? 'fa-shield-halved' : 'fa-user' ?> mr-1.5"></i>
                        <?= $m['sender_type'] === 'admin' ? 'Support Representative' : htmlspecialchars($_SESSION['user']['name'] ?? 'You') ?>
                    </span>
                    <span class="text-[10px] text-gray-500"><?= date('d M Y, h:i A', strtotime($m['created_at'])) ?></span>
                </div>
                <p class="text-xs text-gray-300 leading-relaxed whitespace-pre-wrap"><?= htmlspecialchars($m['message']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Reply Form -->
    <?php if ($ticket['status'] !== 'closed'): ?>
    <div class="bg-[#0B0E14] border border-gray-800 rounded-2xl p-5 space-y-3">
        <h3 class="font-bold text-white text-xs">Add a Reply</h3>
        <form action="/support/<?= urlencode($ticket['ticket_number']) ?>/reply" method="POST" class="space-y-3">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <textarea name="message" rows="3" required placeholder="Type your response..." 
                      class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 transition"></textarea>
            <button type="submit" class="px-5 py-2 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs shadow transition">
                Send Reply
            </button>
        </form>
    </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/user_footer.php'; ?>
