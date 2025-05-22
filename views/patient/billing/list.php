<?php
// Start output buffering
ob_start();
?>

<div class="pb-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
    <h3 class="text-2xl leading-6 font-bold text-gray-900">
        <i class="fas fa-file-invoice-dollar mr-2 text-primary-500"></i> Billing History
    </h3>
    <div class="mt-3 flex sm:mt-0">
        <a href="patient" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
    </div>
</div>

<!-- Billing Summary -->
<div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 bg-gray-50">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Billing Summary
        </h3>
    </div>
    <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-3">
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">
                    Total Transactions
                </dt>
                <dd class="mt-1 text-xl font-semibold text-gray-900">
                    <?= count($transactions) ?>
                </dd>
            </div>
            
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">
                    Total Amount Paid
                </dt>
                <dd class="mt-1 text-xl font-semibold text-green-600">
                    <?php
                    $totalPaid = 0;
                    foreach ($transactions as $transaction) {
                        $totalPaid += $transaction['harga'];
                    }
                    echo '$' . number_format($totalPaid / 100, 2);
                    ?>
                </dd>
            </div>
            
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">
                    Last Transaction
                </dt>
                <dd class="mt-1 text-xl font-semibold text-gray-900">
                    <?= !empty($transactions) ? formatDate($transactions[0]['waktu_transaksi'], 'M d, Y') : 'None' ?>
                </dd>
            </div>
        </dl>
    </div>
</div>

<!-- Transaction History -->
<div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 bg-gray-50">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Transaction History
        </h3>
    </div>
    <div class="border-t border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Transaction ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cashier
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amount
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($transactions)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No transactions found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $transaction): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= formatDate($transaction['waktu_transaksi'], 'M d, Y') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #<?= $transaction['id_transaksi'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $transaction['nama_kasir'] ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <?= $transaction['keterangan'] ?: 'Medical services' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    $<?= number_format($transaction['harga'] / 100, 2) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Get the content of the output buffer
$content = ob_get_clean();

// Include the patient layout
require_once 'views/layouts/main.php';
?>