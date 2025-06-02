<?php
// Start output buffering
ob_start();
?>

<div class="max-w-7xl mx-auto mt-10">
    <!-- Header Section -->
    <div class="pb-6 border-b border-gray-200 mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <h3 class="text-3xl font-bold text-gray-900 flex items-center">
            <span class="bg-primary-100 p-2 rounded-lg mr-3">
                <i class="fas fa-tachometer-alt text-primary-600"></i>
            </span>
            Admin Dashboard
        </h3>
    </div>

    <!-- Dashboard Stats Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Patients Card -->
        <div class="bg-white overflow-hidden shadow-lg rounded-xl transition-all duration-200 hover:shadow-xl border border-gray-100">
            <div class="px-5 py-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-primary-100 rounded-xl p-4">
                        <i class="fas fa-users text-primary-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Patients
                            </dt>
                            <dd class="mt-1">
                                <div class="text-2xl font-bold text-gray-900">
                                    <?= $patientCount ?>
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3 border-t border-gray-100">
                <div class="text-sm">
                    <a href="admin?action=patients" class="font-medium text-primary-600 hover:text-primary-500 flex items-center justify-between">
                        <span>View all patients</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Doctors Card -->
        <div class="bg-white overflow-hidden shadow-lg rounded-xl transition-all duration-200 hover:shadow-xl border border-gray-100">
            <div class="px-5 py-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-xl p-4">
                        <i class="fas fa-user-md text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Doctors
                            </dt>
                            <dd class="mt-1">
                                <div class="text-2xl font-bold text-gray-900">
                                    <?= $doctorCount ?>
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3 border-t border-gray-100">
                <div class="text-sm">
                    <a href="admin?action=doctors" class="font-medium text-primary-600 hover:text-primary-500 flex items-center justify-between">
                        <span>View all doctors</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Appointments Card -->
        <div class="bg-white overflow-hidden shadow-lg rounded-xl transition-all duration-200 hover:shadow-xl border border-gray-100">
            <div class="px-5 py-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-xl p-4">
                        <i class="fas fa-calendar-check text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Appointments
                            </dt>
                            <dd class="mt-1">
                                <div class="text-2xl font-bold text-gray-900">
                                    <?= $appointmentCount ?>
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3 border-t border-gray-100">
                <div class="text-sm">
                    <a href="admin?action=appointments" class="font-medium text-primary-600 hover:text-primary-500 flex items-center justify-between">
                        <span>View all appointments</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Transactions Card -->
        <div class="bg-white overflow-hidden shadow-lg rounded-xl transition-all duration-200 hover:shadow-xl border border-gray-100">
            <div class="px-5 py-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-xl p-4">
                        <i class="fas fa-money-bill-wave text-yellow-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Transactions
                            </dt>
                            <dd class="mt-1">
                                <div class="text-2xl font-bold text-gray-900">
                                    <?= $transactionCount ?>
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3 border-t border-gray-100">
                <div class="text-sm">
                    <a href="admin?action=reports&sub_action=financial" class="font-medium text-primary-600 hover:text-primary-500 flex items-center justify-between">
                        <span>View financial report</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <!-- Recent Patients Panel -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-semibold text-gray-900 flex items-center">
                    <span class="bg-primary-100 p-1.5 rounded-lg mr-2.5">
                        <i class="fas fa-users text-primary-600"></i>
                    </span>
                    Recent Patients
                </h3>
            </div>
            <ul class="divide-y divide-gray-200">
                <?php if (empty($recentPatients)): ?>
                    <li class="px-6 py-6">
                        <p class="text-gray-500 text-center">No recent patients found.</p>
                    </li>
                <?php else: ?>
                    <?php foreach ($recentPatients as $patient): ?>
                        <li class="px-6 py-4 hover:bg-gray-50 transition duration-150">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-primary-100 flex items-center justify-center">
                                        <span class="text-primary-600 font-bold text-lg"><?= substr($patient['nama_pasien'], 0, 1) ?></span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">
                                            <?= $patient['nama_pasien'] ?>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            <span class="inline-flex items-center">
                                                <i class="fas fa-id-card mr-1"></i> ID: <?= $patient['id_pasien'] ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="admin?action=patients&sub_action=view&id=<?= $patient['id_pasien'] ?>" class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 hover:bg-green-200 transition duration-150 text-xs font-semibold">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                <div class="text-sm">
                    <a href="admin?action=patients" class="font-medium text-primary-600 hover:text-primary-500 flex items-center justify-between">
                        <span>View all patients</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Recent Transactions Panel -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-semibold text-gray-900 flex items-center">
                    <span class="bg-yellow-100 p-1.5 rounded-lg mr-2.5">
                        <i class="fas fa-money-bill-wave text-yellow-600"></i>
                    </span>
                    Recent Transactions
                </h3>
            </div>
            <ul class="divide-y divide-gray-200">
                <?php if (empty($recentTransactions)): ?>
                    <li class="px-6 py-6">
                        <p class="text-gray-500 text-center">No recent transactions found.</p>
                    </li>
                <?php else: ?>
                    <?php foreach ($recentTransactions as $transaction): ?>
                        <li class="px-6 py-4 hover:bg-gray-50 transition duration-150">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">
                                        <?= $transaction['nama_pasien'] ?>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-0.5 flex items-center">
                                        <i class="far fa-clock mr-1"></i>
                                        <?= formatDate($transaction['waktu_transaksi'], 'M d, Y H:i') ?>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="text-sm font-bold text-gray-900 mr-2">
                                        Rp<?= number_format($transaction['harga'], 2) ?>
                                    </div>
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Paid
                                    </span>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                <div class="text-sm">
                    <a href="admin?action=reports&sub_action=financial" class="font-medium text-primary-600 hover:text-primary-500 flex items-center justify-between">
                        <span>View all transactions</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Get the content of the output buffer
$content = ob_get_clean();

// Include the admin layout
require_once 'views/layouts/main.php';
?>