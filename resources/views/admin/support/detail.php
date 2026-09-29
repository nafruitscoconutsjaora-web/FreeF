<?php
$pageTitle = "Ticket #" . htmlspecialchars($ticket['ticket_number']) . " - Admin Support";
$activeAdminNav = 'support';
require __DIR__ . '/../../layouts/admin_header.php';
?>

<div class="space-y-6 max-w-4xl mx-auto">
    <div class="flex items-center justify-between">
        <a href="/admin/support" class="text-xs text-rose-400 hover:underline">← Back to All Tickets</a>
        <div class="flex items-center gap-2">
            <span class="text-xs text-gray-400">Status:</span>
            <select id="ticketStatusSelect" class="bg-[#121824] border border-gray-800 rounded-lg px-2.5 py-1 text-xs text-white">
                <option value="open" <?= $ticket['status'] === 'open' ? 'selected' : '' ?>>Open</option>
                <option value="answered" <?= $ticket['status'] === 'answered' ? 'selected' : '' ?>>Answered</option>
                <option value="closed" <?= $ticket['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
            </select>
            <button type="button" id="saveTicketStatusBtn" class="px-3 py-1 bg-gray-800 hover:bg-gray-700 text-white rounded-lg text-xs font-semibold">Save</button>
        </div>
    </div>

    <!-- Ticket Summary Card -->
    <div class="bg-[#0D111A] border border-gray-800 rounded-3xl p-6 space-y-3">
        <div class="flex items-center justify-between border-b border-gray-800 pb-3">
            <div>
                <span class="font-mono text-xs text-rose-400 font-bold">#<?= htmlspecialchars($ticket['ticket_number']) ?></span>
                <h1 class="text-base font-bold text-white mt-0.5"><?= htmlspecialchars($ticket['subject']) ?></h1>
            </div>
            <div class="text-right text-xs">
                <span class="text-white font-semibold"><?= htmlspecialchars($ticket['user_name']) ?></span>
                <div class="text-[10px] text-gray-500"><?= htmlspecialchars($ticket['user_email']) ?></div>
            </div>
        </div>

        <div class="flex gap-4 text-xs text-gray-400">
            <span>Category: <strong class="text-white"><?= ucwords(str_replace('_', ' ', $ticket['category'])) ?></strong></span>
            <span>Priority: <strong class="text-rose-400"><?= ucfirst($ticket['priority']) ?></strong></span>
            <?php if (!empty($ticket['order_id'])): ?>
                <span>Order Ref: <a href="/admin/orders?q=<?= $ticket['order_id'] ?>" class="text-sky-400 underline">Order #<?= $ticket['order_id'] ?></a></span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Message Thread -->
    <div class="space-y-4">
        <?php foreach ($messages as $msg): ?>
            <?php $isAdmin = ($msg['sender_type'] === 'admin'); ?>
            <div class="flex <?= $isAdmin ? 'justify-end' : 'justify-start' ?>">
                <div class="max-w-xl rounded-2xl p-4 <?= $isAdmin ? 'bg-rose-950/40 border border-rose-800/40 text-rose-100' : 'bg-[#0D111A] border border-gray-800 text-gray-200' ?> space-y-1.5">
                    <div class="flex items-center justify-between gap-4 text-[10px] <?= $isAdmin ? 'text-rose-400' : 'text-gray-400' ?> font-semibold">
                        <span><?= $isAdmin ? 'Support Team (Admin)' : htmlspecialchars($ticket['user_name']) ?></span>
                        <span><?= date('M d, h:i A', strtotime($msg['created_at'])) ?></span>
                    </div>
                    <p class="text-xs leading-relaxed whitespace-pre-line"><?= htmlspecialchars($msg['message']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Admin Reply Form -->
    <form action="/admin/support/<?= $ticket['id'] ?>/reply" method="POST" class="bg-[#0D111A] border border-gray-800 rounded-3xl p-5 space-y-3">
        <label class="block text-xs font-semibold text-gray-300">Reply to Customer</label>
        <textarea name="message" rows="3" required placeholder="Type your official response..."
                  class="w-full bg-[#121824] border border-gray-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-rose-500"></textarea>
        <div class="flex justify-end">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow">
                Send Reply
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('saveTicketStatusBtn')?.addEventListener('click', async () => {
    const status = document.getElementById('ticketStatusSelect').value;
    const res = await fetch('/admin/support/<?= $ticket['id'] ?>/status', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ status })
    });
    const data = await res.json();
    alert(data.message);
});
</script>

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
