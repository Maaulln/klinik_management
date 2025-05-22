<?php
$pageTitle = 'Doctors List';
ob_start();
?>

<div class="max-w-5xl mx-auto my-8">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Header section -->
        <div class="px-6 py-5 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-primary-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h2 class="text-2xl font-bold text-gray-900">Doctors</h2>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="admin?action=doctors&sub_action=add" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Doctor
                </a>
            </div>
        </div>

        <!-- Search section (optional) -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <form action="admin" method="GET" class="flex items-center space-x-4">
                <input type="hidden" name="action" value="doctors">
                <div class="relative flex-grow max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" placeholder="Search doctors..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Search
                </button>
            </form>
        </div>

        <!-- List style section -->
        <ul class="divide-y divide-gray-200">
            <?php if (!empty($doctors)): ?>
                <?php foreach ($doctors as $doctor): ?>
                    <li class="px-6 py-5 hover:bg-gray-50 transition-colors flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-primary-100 flex items-center justify-center">
                                <span class="text-primary-700 font-bold text-xl">
                                    <?= strtoupper(substr(htmlspecialchars($doctor['nama_dokter'] ?? ''), 0, 1)) ?>
                                </span>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($doctor['nama_dokter'] ?? '') ?></div>
                                <div class="text-sm text-gray-500"><?= htmlspecialchars($doctor['specialization'] ?? '') ?></div>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <a href="admin?action=doctors&sub_action=edit&id=<?= $doctor['id_dokter'] ?>" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                            <a href="admin?action=doctors&sub_action=delete&id=<?= $doctor['id_dokter'] ?>" class="inline-flex items-center text-sm text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this doctor?')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </a>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="px-6 py-10 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-500 text-lg font-medium">No doctors found</p>
                        <p class="text-gray-400 text-sm mt-1">Add a new doctor to get started</p>
                    </div>
                </li>
            <?php endif; ?>
        </ul>

        <!-- Pagination (if needed) -->
        <?php if (!empty($doctors) && isset($pagination)): ?>
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <?= $pagination ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.php';
?>