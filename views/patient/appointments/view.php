<?php
// Start output buffering
ob_start();
?>

<div class="pb-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
    <h3 class="text-2xl leading-6 font-bold text-gray-900">
        <i class="fas fa-calendar-check mr-2 text-primary-500"></i> Detail Appointment
    </h3>
    <div class="mt-3 flex sm:mt-0">
        <a href="patient?action=appointments" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
</div>

<div class="bg-white shadow overflow-hidden sm:rounded-lg mt-5">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Informasi Appointment</h3>
    </div>
    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">ID Appointment</dt>
                <dd class="mt-1 text-sm text-gray-900">#<?= $appointment['id_appointment'] ?></dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                <dd class="mt-1 text-sm text-gray-900">
                    <?php
                    $statusLabels = [
                        'scheduled' => ['label' => 'Scheduled', 'class' => 'bg-blue-100 text-blue-800'],
                        'completed' => ['label' => 'Completed', 'class' => 'bg-gray-100 text-gray-800'],
                        'cancelled' => ['label' => 'Cancelled', 'class' => 'bg-red-100 text-red-800'],
                        'pending' => ['label' => 'Pending Payment', 'class' => 'bg-yellow-100 text-yellow-800']
                    ];
                    $status = strtolower($appointment['status']);
                    $label = $statusLabels[$status]['label'] ?? ucfirst($status);
                    $class = $statusLabels[$status]['class'] ?? 'bg-gray-100 text-gray-800';
                    ?>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $class ?>">
                        <?= htmlspecialchars($label) ?>
                    </span>
                </dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">Tanggal</dt>
                <dd class="mt-1 text-sm text-gray-900"><?= formatDate($appointment['tanggal_janji'], 'l, j F Y') ?></dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">Waktu</dt>
                <dd class="mt-1 text-sm text-gray-900"><?= formatDate($appointment['waktu_janji'], 'H:i') ?> WIB</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">Dokter</dt>
                <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($appointment['nama_dokter']) ?></dd>
            </div>
        </dl>

        <?php if ($appointment['status'] === 'scheduled'): ?>
        <div class="mt-6 flex space-x-3">
            <form action="" method="POST" class="inline-block">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <i class="fas fa-check-circle mr-2"></i> Selesaikan Appointment
                </button>
            </form>
            <a href="patient?action=appointments&sub_action=cancel&id=<?= $appointment['id_appointment'] ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <i class="fas fa-times-circle mr-2"></i> Batalkan
            </a>
        </div>
        <?php endif; ?>

        <?php if ($appointment['status'] === 'pending'): ?>
        <div class="mt-6">
            <a href="patient?action=billing&sub_action=create&id_appointment=<?= $appointment['id_appointment'] ?>" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <i class="fas fa-credit-card mr-2"></i> Lakukan Pembayaran
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.php';
?>