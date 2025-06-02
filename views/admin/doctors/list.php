<?php
$pageTitle = 'Doctors List';
ob_start();
?>

<div class="max-w-7xl mx-auto pb-6 border-b border-gray-200 mt-5">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <h3 class="text-2xl font-bold text-gray-900 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Doctors
        </h3>
        <div class="mt-4 sm:mt-0">
            <a href="admin?action=doctors&sub_action=add" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New Doctor
            </a>
        </div>
    </div>
</div>

<!-- Search and filters -->
<div class="my-6">
    <form action="admin" method="GET" class="flex flex-col sm:flex-row gap-3">
        <input type="hidden" name="action" value="doctors">
        <div class="flex-1">
            <div class="relative rounded-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" id="search" value="<?= $search ?? '' ?>" class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Search doctors by name or specialization...">
            </div>
        </div>
        <div class="flex-1">
            <select name="specialization" class="block w-full py-2 px-3 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                <option value="">All Specializations</option>
                <option value="General Practice" <?= ($specialization ?? '') === 'General Practice' ? 'selected' : '' ?>>General Practice</option>
                <option value="Cardiology" <?= ($specialization ?? '') === 'Cardiology' ? 'selected' : '' ?>>Cardiology</option>
                <option value="Neurology" <?= ($specialization ?? '') === 'Neurology' ? 'selected' : '' ?>>Neurology</option>
                <option value="Pediatrics" <?= ($specialization ?? '') === 'Pediatrics' ? 'selected' : '' ?>>Pediatrics</option>
                <option value="Orthopedics" <?= ($specialization ?? '') === 'Orthopedics' ? 'selected' : '' ?>>Orthopedics</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                Search
            </button>
            <?php if (!empty($search) || !empty($specialization)): ?>
                <a href="admin?action=doctors" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    Clear
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Doctors list -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
    <ul class="divide-y divide-gray-200">
        <?php if (empty($doctors)): ?>
            <li class="px-6 py-8">
                <div class="flex flex-col items-center justify-center text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <p class="text-gray-500 text-lg">No doctors found</p>
                    <p class="text-gray-400 text-sm mt-1">Add a new doctor to get started</p>
                </div>
            </li>
        <?php else: ?>
            <?php foreach ($doctors as $doctor): ?>
                <li class="hover:bg-gray-50 transition-colors">
                    <a href="admin?action=doctors&sub_action=view&id=<?= $doctor['id_dokter'] ?>" class="block">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-primary-100 flex items-center justify-center">
                                        <span class="text-primary-700 font-bold text-lg">
                                            <?= strtoupper(substr(htmlspecialchars($doctor['nama_dokter'] ?? ''), 0, 1)) ?>
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-lg font-medium text-gray-900">
                                            <?= htmlspecialchars($doctor['nama_dokter'] ?? '') ?>
                                        </div>
                                        <div class="text-sm text-gray-500 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0H8m8 0v2a2 2 0 01-2 2H10a2 2 0 01-2-2V6" />
                                            </svg>
                                            <?= htmlspecialchars($doctor['specialization'] ?? 'General Practice') ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <div class="flex space-x-2">
                                        <a href="admin?action=doctors&sub_action=edit&id=<?= $doctor['id_dokter'] ?>" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                        <a href="admin?action=doctors&sub_action=delete&id=<?= $doctor['id_dokter'] ?>" onclick="return confirm('Are you sure you want to delete this doctor?');" class="inline-flex items-center px-3 py-1.5 border border-red-600 shadow-sm text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Delete
                                        </a>
                                        <a href="admin?action=doctors&sub_action=view&id=<?= $doctor['id_dokter'] ?>" class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm text-xs font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php if (!empty($doctor['phone']) || !empty($doctor['email'])): ?>
                            <div class="mt-3">
                                <div class="flex items-center text-sm text-gray-500">
                                    <?php if (!empty($doctor['phone'])): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span class="mr-4"><?= htmlspecialchars($doctor['phone']) ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($doctor['email'])): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span><?= htmlspecialchars($doctor['email']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

<!-- Pagination -->
<?php if (!empty($doctors) && isset($pagination)): ?>
<div class="mt-5">
    <?= $pagination ?>
</div>
<?php endif; ?>

<div class="mt-5">
    <a href="admin?action=dashboard" class="inline-block px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 transition-colors">Back to Dashboard</a>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.php';
?>