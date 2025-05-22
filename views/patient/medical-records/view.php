<?php
// Start output buffering
ob_start();
?>

<div class="pb-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
    <h3 class="text-2xl leading-6 font-bold text-gray-900">
        <i class="fas fa-notes-medical mr-2 text-primary-500"></i> Medical Record
    </h3>
    <div class="mt-3 flex sm:mt-0">
        <a href="patient?action=medical-records" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            <i class="fas fa-arrow-left mr-2"></i> Back to Records
        </a>
    </div>
</div>

<!-- Medical Record Details -->
<div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 bg-gray-50">
        <div class="flex justify-between">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Medical Record Details
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Record #<?= $record['id_catatan'] ?>
                </p>
            </div>
            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                Dr. <?= $record['nama_dokter'] ?>
            </span>
        </div>
    </div>
    <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">
                    Date
                </dt>
                <dd class="mt-1 text-sm text-gray-900">
                    <?= formatDate($record['tanggal_catatan'], 'F j, Y') ?>
                </dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">
                    Doctor
                </dt>
                <dd class="mt-1 text-sm text-gray-900">
                    <?= $record['nama_dokter'] ?>
                </dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">
                    Medical Notes
                </dt>
                <dd class="mt-1 text-sm text-gray-900 prose max-w-none">
                    <?= nl2br($record['isi_catatan']) ?>
                </dd>
            </div>
        </dl>
    </div>
</div>

<!-- Prescriptions -->
<?php if (!empty($prescriptions)): ?>
    <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Prescriptions
            </h3>
        </div>
        <div class="border-t border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Medication
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Quantity
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Instructions
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($prescriptions as $prescription): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= $prescription['nama_obat'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $prescription['jumlah'] ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <?= $prescription['aturan_pakai'] ?: '—' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= formatDate($prescription['tanggal_resep'], 'M d, Y') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
// Get the content of the output buffer
$content = ob_get_clean();

// Include the patient layout
require_once 'views/layouts/main.php';
?>