<?php
// Start output buffering
ob_start();
?>

<div class="pb-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
    <h3 class="text-2xl leading-6 font-bold text-gray-900">
        <i class="fas fa-tachometer-alt mr-2 text-primary-500"></i> Patient Dashboard
    </h3>
    <div class="mt-3 sm:mt-0 sm:ml-4">
        <a href="patient?action=appointments&sub_action=request" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            <i class="fas fa-calendar-plus mr-2"></i> Request Appointment
        </a>
    </div>
</div>

<div class="mt-6">
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Patient Information
            </h3>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                        Full name
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <?= $patient['nama_pasien'] ?>
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                        Patient ID
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <?= $patient['id_pasien'] ?>
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">
                        Address
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <?= $patient['alamat'] ?: 'No address provided' ?>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>

<!-- Dashboard Content -->
<div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
    <!-- Upcoming Appointments -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Upcoming Appointments
            </h3>
            <a href="patient?action=appointments" class="text-sm font-medium text-primary-600 hover:text-primary-500">
                View all
            </a>
        </div>
        <div class="border-t border-gray-200">
            <ul class="divide-y divide-gray-200">
                <?php if (empty($upcomingAppointments)): ?>
                    <li class="px-4 py-4 sm:px-6">
                        <p class="text-gray-500 text-center py-4">No upcoming appointments.</p>
                    </li>
                <?php else: ?>
                    <?php foreach ($upcomingAppointments as $appointments): ?>
                        <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        Appointment #<?= $appointments['id_appointment'] ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?= formatDate($appointments['tanggal_janji'], 'F j, Y \a\t g:i a') ?>
                                    </div>
                                </div>
                                <div>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Scheduled
                                    </span>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    
    <!-- Recent Medical Records -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Recent Medical Records
            </h3>
            <a href="patient?action=medical-records" class="text-sm font-medium text-primary-600 hover:text-primary-500">
                View all
            </a>
        </div>
        <div class="border-t border-gray-200">
            <ul class="divide-y divide-gray-200">
                <?php if (empty($recentMedicalRecords)): ?>
                    <li class="px-4 py-4 sm:px-6">
                        <p class="text-gray-500 text-center py-4">No medical records found.</p>
                    </li>
                <?php else: ?>
                    <?php foreach ($recentMedicalRecords as $record): ?>
                        <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                            <a href="patient?action=medical-records&sub_action=view&id=<?= $record['id_catatan'] ?>" class="block">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            Dr. <?= $record['nama_dokter'] ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?= formatDate($record['tanggal_catatan'], 'F j, Y') ?>
                                        </div>
                                    </div>
                                    <div>
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    
    <!-- Recent Transactions -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg lg:col-span-2">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Recent Transactions
            </h3>
            <a href="patient?action=billing" class="text-sm font-medium text-primary-600 hover:text-primary-500">
                View all
            </a>
        </div>
        <div class="border-t border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                     <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($recentTransactions)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No transactions found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentTransactions as $transaction): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #<?= $transaction['id_transaksi'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= formatDate($transaction['waktu_transaksi'], 'M d, Y') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $transaction['nama_kasir'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    $<?= number_format($transaction['harga'] / 100, 2) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $transaction['keterangan'] ?: '—' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Transaction ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Cashier
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Description
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($recentTransactions)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    No transactions found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentTransactions as $transaction): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #<?= $transaction['id_transaksi'] ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= formatDate($transaction['waktu_transaksi'], 'M d, Y') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= $transaction['nama_kasir'] ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        $<?= number_format($transaction['harga'] / 100, 2) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= $transaction['keterangan'] ?: '—' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
// Get the content of the output buffer
$content = ob_get_clean();

// Include the patient layout
require_once 'views/layouts/main.php';
?>