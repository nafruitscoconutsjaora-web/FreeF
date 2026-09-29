<?php
$pageTitle = "Support Tickets - Admin Control Center";
$activeAdminNav = 'support';
require __DIR__ . '/../../layouts/admin_header.php';
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white">Support Tickets</h1>
            <p class="text-xs text-gray-400 mt-1">Review player queries, diamond delays, and customer service inquiries.</p>
        </div>

        <div class="flex items-center gap-2 text-xs">
            <a href="/admin/support" class="px-3.5 py-1.5 rounded-full font-semibold <?= empty($activeStatus) ? 'bg-[#E11D48] text-white' : 'bg-[#0D111A] text-gray-400 hover:text-white border border-gray-800' ?>">All</a>
            <a href="/admin/support?status=open" class="px-3.5 py-1.5 rounded-full font-semibold <?= ($activeStatus ?? '') === 'open' ? 'bg-[#E11D48] text-white' : 'bg-[#0D111A] text-gray-400 hover:text-white border border-gray-800' ?>">Open</a>
            <a href="/admin/support?status=answered" class="px-3.5 py-1.5 rounded-full font-semibold <?= ($activeStatus ?? '') === 'answered' ? 'bg-[#E11D48] text-white' : 'bg-[#0D111A] text-gray-400 hover:text-white border border-gray-800' ?>">Answered</a>
            <a href="/admin/support?status=closed" class="px-3.5 py-1.5 rounded-full font-semibold <?= ($activeStatus ?? '') === 'closed' ? 'bg-[#E11D48] text-white' : 'bg-[#0D111A] text-gray-400 hover:text-white border border-gray-800' ?>">Closed</a>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="bg-[#0D111A] border border-gray-800 rounded-2xl overflow-hidden shadow-lg">
        <?php if (empty($tickets)): ?>
            <div class="p-12 text-center text-xs text-gray-500">
                No tickets matching your filter.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#121824] text-gray-400 uppercase text-[10px]">
                        <tr>
                            <th class="p-3.5">Ticket #</th>
                            <th class="p-3.5">User</th>
                            <th class="p-3.5">Subject</th>
                            <th class="p-3.5">Category</th>
                            <th class="p-3.5">Priority</th>
                            <th class="p-3.5">Status</th>
                            <th class="p-3.5">Submitted</th>
                            <th class="p-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        <?php foreach ($tickets as $t): ?>
                        <tr class="hover:bg-gray-800/30">
                            <td class="p-3.5 font-mono font-bold text-rose-400">#<?= htmlspecialchars($t['ticket_number']) ?></td>
                            <td class="p-3.5">
                                <span class="font-bold text-white"><?= htmlspecialchars($t['user_name']) ?></span>
                                <div class="text-[10px] text-gray-500"><?= htmlspecialchars($t['user_email']) ?></div>
                            </td>
                            <td class="p-3.5 text-white font-medium max-w-xs truncate"><?= htmlspecialchars($t['subject']) ?></td>
                            <td class="p-3.5 text-gray-400"><?= ucwords(str_replace('_', ' ', $t['category'])) ?></td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold <?= $t['priority'] === 'high' ? 'bg-rose-500/20 text-rose-400' : 'bg-gray-700 text-gray-300' ?>">
                                    <?= ucfirst($t['priority']) ?>
                                </span>
                            </td>
                            <td class="p-3.5">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold <?= $t['status'] === 'open' ? 'bg-sky-500/20 text-sky-400' : ($t['status'] === 'answered' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-gray-700 text-gray-400') ?>">
                                    <?= ucfirst($t['status']) ?>
                                </span>
                            </td>
                            <td class="p-3.5 text-gray-500"><?= date('M d, H:i', strtotime($t['created_at'])) ?></td>
                            <td class="p-3.5 text-right">
                                <a href="/admin/support/<?= $t['id'] ?>" class="px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow inline-block">
                                    Open Chat →
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

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
