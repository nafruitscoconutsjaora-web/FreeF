<?php
$pageTitle = "Audit & API Logs - Admin Control Center";
$activeAdminNav = 'logs';
require __DIR__ . '/../../layouts/admin_header.php';
?>

<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-black text-white">System & API Logs</h1>
        <p class="text-xs text-gray-400 mt-1">Audit log of outgoing provider requests, status changes, and backend automated tasks.</p>
    </div>

    <!-- API Request Logs -->
    <div class="bg-[#0D111A] border border-gray-800 rounded-3xl p-6 space-y-4">
        <h3 class="text-sm font-bold text-white uppercase tracking-wider">Provider API Calls Log</h3>
        
        <?php if (empty($apiLogs)): ?>
            <div class="p-8 text-center text-xs text-gray-500">No outgoing API calls recorded in audit table.</div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#121824] text-gray-400 uppercase text-[10px]">
                        <tr>
                            <th class="p-3">ID</th>
                            <th class="p-3">Provider ID</th>
                            <th class="p-3">Action</th>
                            <th class="p-3">Request Payload</th>
                            <th class="p-3">Response</th>
                            <th class="p-3">HTTP Code</th>
                            <th class="p-3">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        <?php foreach ($apiLogs as $log): ?>
                        <tr class="hover:bg-gray-800/30 font-mono">
                            <td class="p-3 text-gray-400">#<?= $log['id'] ?></td>
                            <td class="p-3 text-white">Provider #<?= $log['provider_id'] ?></td>
                            <td class="p-3 text-rose-400 font-bold"><?= htmlspecialchars($log['action']) ?></td>
                            <td class="p-3 text-gray-400 max-w-xs truncate"><?= htmlspecialchars($log['request_payload'] ?? '') ?></td>
                            <td class="p-3 text-gray-400 max-w-xs truncate"><?= htmlspecialchars($log['response_payload'] ?? '') ?></td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold <?= ($log['http_status'] ?? 200) < 300 ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' ?>">
                                    <?= $log['http_status'] ?? 200 ?>
                                </span>
                            </td>
                            <td class="p-3 text-gray-500 text-[11px]"><?= date('M d, H:i:s', strtotime($log['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Order Status Transition History -->
    <div class="bg-[#0D111A] border border-gray-800 rounded-3xl p-6 space-y-4">
        <h3 class="text-sm font-bold text-white uppercase tracking-wider">Order Status Transitions</h3>
        
        <?php if (empty($statusHistory)): ?>
            <div class="p-8 text-center text-xs text-gray-500">No status changes logged yet.</div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#121824] text-gray-400 uppercase text-[10px]">
                        <tr>
                            <th class="p-3">Order #</th>
                            <th class="p-3">Previous</th>
                            <th class="p-3">New Status</th>
                            <th class="p-3">Changed By</th>
                            <th class="p-3">Notes</th>
                            <th class="p-3">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        <?php foreach ($statusHistory as $h): ?>
                        <tr class="hover:bg-gray-800/30">
                            <td class="p-3 font-mono font-bold text-rose-400">#<?= htmlspecialchars($h['order_number']) ?></td>
                            <td class="p-3 text-gray-400"><?= ucfirst($h['previous_status'] ?: 'none') ?></td>
                            <td class="p-3 font-bold text-white"><?= ucfirst($h['new_status']) ?></td>
                            <td class="p-3 font-mono text-gray-300"><?= htmlspecialchars($h['changed_by']) ?></td>
                            <td class="p-3 text-gray-400"><?= htmlspecialchars($h['notes'] ?? '') ?></td>
                            <td class="p-3 text-gray-500"><?= date('M d, H:i', strtotime($h['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
