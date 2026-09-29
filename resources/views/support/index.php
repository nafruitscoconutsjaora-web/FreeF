<?php
$pageTitle = "Support Tickets - FF Panel Store";
$activeNav = 'support';
require __DIR__ . '/../layouts/user_header.php';
?>

<div class="max-w-6xl mx-auto px-4 lg:px-8 py-8 space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white">Support & Assistance</h1>
            <p class="text-xs text-gray-400 mt-1">Need help with a Free Fire diamond recharge, order delay, or payment? Open a ticket below.</p>
        </div>
        <button type="button" id="openTicketBtn" 
                class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs flex items-center gap-2 shadow-lg transition">
            <i class="fa-solid fa-plus"></i> Open New Ticket
        </button>
    </div>

    <!-- Create Ticket Panel (Normal Document Flow) -->
    <div id="newTicketPanel" class="hidden bg-[#0B0E14] border-2 border-rose-500/50 rounded-3xl p-6 sm:p-8 space-y-6">
        <div class="flex items-center justify-between border-b border-gray-800 pb-4">
            <h3 class="text-base font-bold text-white">Submit New Support Request</h3>
            <button type="button" id="closeTicketPanelBtn" class="text-gray-400 hover:text-white text-base">&times;</button>
        </div>

        <form id="ticketForm" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1.5">Subject *</label>
                    <input type="text" id="tSubject" required placeholder="e.g. Diamonds not received for order #1002"
                           class="w-full bg-[#121824] border border-gray-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1.5">Category *</label>
                    <select id="tCategory" class="w-full bg-[#121824] border border-gray-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500">
                        <option value="recharge_delay">Diamond Recharge Delay</option>
                        <option value="payment_issue">Payment / UPI Issue</option>
                        <option value="order_issue">Wrong UID / Order Mistake</option>
                        <option value="general">General Inquiry</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1.5">Priority</label>
                    <select id="tPriority" class="w-full bg-[#121824] border border-gray-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High / Urgent</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1.5">Related Order ID (Optional)</label>
                    <input type="number" id="tOrderId" placeholder="e.g. 1024"
                           class="w-full bg-[#121824] border border-gray-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-1.5">Describe your issue in detail *</label>
                <textarea id="tMessage" rows="4" required placeholder="Please provide your FF Player UID, payment UTR or order ID for fastest resolution."
                          class="w-full bg-[#121824] border border-gray-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-rose-500"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" id="cancelTicketBtn" class="px-5 py-2.5 rounded-xl bg-gray-800 text-gray-300 hover:text-white text-xs font-semibold">Cancel</button>
                <button type="submit" id="submitTicketBtn" class="px-6 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-lg">Submit Ticket</button>
            </div>
        </form>
    </div>

    <!-- Tickets History -->
    <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-6 space-y-4">
        <h3 class="text-sm font-bold text-white uppercase tracking-wider">Your Support Tickets</h3>

        <?php if (empty($tickets)): ?>
            <div class="p-12 text-center text-xs text-gray-500">
                <i class="fa-solid fa-headset text-4xl mb-3 text-gray-600 block"></i>
                You don't have any support tickets open. Click "Open New Ticket" above if you need help.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#121824] text-gray-400 uppercase text-[10px]">
                        <tr>
                            <th class="p-3">Ticket #</th>
                            <th class="p-3">Subject</th>
                            <th class="p-3">Category</th>
                            <th class="p-3">Priority</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Date</th>
                            <th class="p-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        <?php foreach ($tickets as $t): ?>
                        <tr class="hover:bg-gray-800/30">
                            <td class="p-3 font-mono font-bold text-rose-400">#<?= htmlspecialchars($t['ticket_number']) ?></td>
                            <td class="p-3 font-semibold text-white max-w-xs truncate"><?= htmlspecialchars($t['subject']) ?></td>
                            <td class="p-3 text-gray-400"><?= ucwords(str_replace('_', ' ', $t['category'])) ?></td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold <?= $t['priority'] === 'high' ? 'bg-rose-500/20 text-rose-400' : ($t['priority'] === 'medium' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-gray-700 text-gray-300') ?>">
                                    <?= ucfirst($t['priority']) ?>
                                </span>
                            </td>
                            <td class="p-3">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold <?= $t['status'] === 'closed' ? 'bg-gray-800 text-gray-400' : ($t['status'] === 'answered' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-sky-500/20 text-sky-400') ?>">
                                    <?= ucfirst($t['status']) ?>
                                </span>
                            </td>
                            <td class="p-3 text-gray-400"><?= date('M d, Y', strtotime($t['created_at'])) ?></td>
                            <td class="p-3 text-right">
                                <a href="/support/<?= $t['id'] ?>" class="px-3 py-1.5 rounded-lg bg-[#121824] hover:bg-gray-700 text-white font-semibold text-xs border border-gray-700 inline-block">
                                    View Chat →
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
const panel = document.getElementById('newTicketPanel');
document.getElementById('openTicketBtn')?.addEventListener('click', () => {
    panel.classList.remove('hidden');
    panel.scrollIntoView({ behavior: 'smooth' });
});
document.getElementById('closeTicketPanelBtn')?.addEventListener('click', () => panel.classList.add('hidden'));
document.getElementById('cancelTicketBtn')?.addEventListener('click', () => panel.classList.add('hidden'));

document.getElementById('ticketForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('submitTicketBtn');
    btn.disabled = true;
    btn.textContent = 'Submitting...';

    const res = await fetch('/support/create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            subject: document.getElementById('tSubject').value,
            category: document.getElementById('tCategory').value,
            priority: document.getElementById('tPriority').value,
            order_id: document.getElementById('tOrderId').value,
            message: document.getElementById('tMessage').value,
        })
    });

    const data = await res.json();
    if (data.success) {
        alert('Ticket created successfully! Ticket #: ' + data.ticket_number);
        window.location.reload();
    } else {
        alert(data.message || 'Failed to submit ticket');
        btn.disabled = false;
        btn.textContent = 'Submit Ticket';
    }
});
</script>

<?php require __DIR__ . '/../layouts/user_footer.php'; ?>
