<?php
$pageTitle = 'Financial Report';
ob_start();
?>

<div class="max-w-7xl mx-auto pb-6 border-b border-gray-200 mt-5">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <h3 class="text-2xl font-bold text-gray-900 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            Financial Report
        </h3>
    </div>
</div>

<!-- Search and filters -->
<div class="my-6">
    <form method="GET" action="admin" class="flex flex-col sm:flex-row gap-3">
        <input type="hidden" name="action" value="reports">
        <input type="hidden" name="sub_action" value="financial">
        <div class="flex-1">
            <div class="relative rounded-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2z" />
                    </svg>
                </div>
                <input type="date" name="start_date" id="start_date" value="<?= htmlspecialchars($startDate ?? '') ?>" class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Start Date">
            </div>
        </div>
        <div class="flex-1">
            <div class="relative rounded-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2z" />
                    </svg>
                </div>
                <input type="date" name="end_date" id="end_date" value="<?= htmlspecialchars($endDate ?? '') ?>" class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="End Date">
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                Filter
            </button>
            <?php if (!empty($startDate) || !empty($endDate)): ?>
                <a href="admin?action=reports&sub_action=financial" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    Clear
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Transactions list -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
    <ul class="divide-y divide-gray-200">
        <?php if (empty($transactions)): ?>
            <li class="px-6 py-8">
                <div class="flex flex-col items-center justify-center text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-500 text-lg">No transactions found</p>
                    <p class="text-gray-400 text-sm mt-1">Try adjusting your date range</p>
                </div>
            </li>
        <?php else: ?>
            <?php foreach ($transactions as $trx): ?>
                <li class="hover:bg-gray-50 transition-colors">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-lg font-medium text-gray-900">
                                        <?= htmlspecialchars($trx['nama_pasien']) ?>
                                    </div>
                                    <div class="text-sm text-gray-500 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2z" />
                                        </svg>
                                        <?= htmlspecialchars($trx['waktu_transaksi']) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-2 flex-shrink-0 flex flex-col items-end">
                                <span class="text-xl font-bold text-green-600">
                                    Rp<?= number_format($trx['harga'], 0, ',', '.') ?>
                                </span>
                                <div class="text-sm text-gray-500 mt-1">
                                    Cashier: <?= htmlspecialchars($trx['nama_kasir']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

<!-- Summary section -->
<?php if (!empty($transactions)): ?>
<div class="mt-6 bg-primary-50 border border-primary-200 rounded-lg p-6">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-lg font-semibold text-primary-900">Report Summary</h4>
            <p class="text-sm text-primary-700 mt-1">
                <?= count($transactions) ?> transaction(s) found
                <?php if (!empty($startDate) && !empty($endDate)): ?>
                    from <?= htmlspecialchars($startDate) ?> to <?= htmlspecialchars($endDate) ?>
                <?php endif; ?>
            </p>
        </div>
        <div class="text-right">
            <p class="text-sm text-primary-700">Total Revenue</p>
            <p class="text-3xl font-bold text-primary-900">
                Rp<?= number_format($total ?? 0, 0, ',', '.') ?>
            </p>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="mt-5">
    <a href="admin?action=dashboard" class="inline-block px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">Back to Dashboard</a>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.php';
?>