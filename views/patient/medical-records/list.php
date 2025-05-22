<?php
// Start output buffering
ob_start();
?>

<div class="pb-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
    <h3 class="text-2xl leading-6 font-bold text-gray-900">
        <i class="fas fa-notes-medical mr-2 text-primary-500"></i> My Medical Records
    </h3>
    <div class="mt-3 flex sm:mt-0">
        <a href="patient" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
    </div>
</div>

<!-- Medical Records List -->
<div class="mt-6 bg-white shadow overflow-hidden sm:rounded-md">
    <ul class="divide-y divide-gray-200">
        <?php if (empty($medicalRecords)): ?>
            <li class="px-4 py-4 sm:px-6">
                <p class="text-gray-500 text-center py-4">No medical records found.</p>
            </li>
        <?php else: ?>
            <?php foreach ($medicalRecords as $record): ?>
                <li>
                    <a href="patient?action=medical-records&sub_action=view&id=<?= $record['id_catatan'] ?>" class="block hover:bg-gray-50">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        Medical Record #<?= $record['id_catatan'] ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Date: <?= formatDate($record['tanggal_catatan'], 'F j, Y') ?>
                                    </div>
                                </div>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <div class="flex space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Dr. <?= $record['nama_dokter'] ?>
                                        </span>
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="text-sm text-gray-600 line-clamp-2">
                                    <?= substr($record['isi_catatan'], 0, 150) . (strlen($record['isi_catatan']) > 150 ? '...' : '') ?>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

<?php
// Get the content of the output buffer
$content = ob_get_clean();

// Include the patient layout
require_once 'views/layouts/main.php';
?>