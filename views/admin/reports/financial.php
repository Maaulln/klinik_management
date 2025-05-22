<?php
$pageTitle = 'Financial Report';
ob_start();
?>

<div class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6 text-primary-700">Financial Report</h2>

    <form method="get" action="admin" class="mb-6 flex flex-wrap gap-4 items-end">
        <input type="hidden" name="action" value="reports">
        <input type="hidden" name="sub_action" value="financial">
        <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
            <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($startDate) ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
        </div>
        <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
            <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars($endDate) ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
        </div>
        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">Filter</button>
    </form>

    <table class="min-w-full divide-y divide-gray-200 mb-6">
        <thead>
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cashier</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (!empty($transactions)): ?>
                <?php foreach ($transactions as $trx): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($trx['waktu_transaksi']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($trx['nama_pasien']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($trx['nama_kasir']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">Rp<?= number_format($trx['harga'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No transactions found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="mt-6 text-right font-bold">
        Total: Rp<?= number_format($total ?? 0, 0, ',', '.') ?>
    </div>

    <div class="mt-8">
        <a href="admin?action=dashboard" class="inline-block px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">Back to Dashboard</a>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.php';
?>