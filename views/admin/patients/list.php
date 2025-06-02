<?php
// Start output buffering
ob_start();
?>

<div class="max-w-7xl mx-auto pb-6 border-b border-gray-200 mt-5">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <h3 class="text-2xl font-bold text-gray-900 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Patients
        </h3>
        <div class="mt-4 sm:mt-0">
            <a href="admin?action=patients&sub_action=add" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New Patient
            </a>
        </div>
    </div>
</div>

<!-- Search and filters -->
<div class="my-6">
    <form action="admin" method="GET" class="flex flex-col sm:flex-row gap-3">
        <input type="hidden" name="action" value="patients">
        <div class="flex-1">
            <div class="relative rounded-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" id="search" value="<?= $search ?? '' ?>" class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Search patients by name or ID...">
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                Search
            </button>
            <?php if (!empty($search)): ?>
                <a href="admin?action=patients" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    Clear
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Patients list -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
    <ul class="divide-y divide-gray-200">
        <?php if (empty($patients)): ?>
            <li class="px-6 py-8">
                <div class="flex flex-col items-center justify-center text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-500 text-lg">No patients found</p>
                    <p class="text-gray-400 text-sm mt-1">Try adjusting your search criteria</p>
                </div>
            </li>
        <?php else: ?>
            <?php foreach ($patients as $patient): ?>
                <li class="hover:bg-gray-50 transition-colors">
                    <a href="admin?action=patients&sub_action=view&id=<?= $patient['id_pasien'] ?>" class="block">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-primary-100 flex items-center justify-center">
                                        <span class="text-primary-700 font-bold text-lg"><?= substr($patient['nama_pasien'], 0, 1) ?></span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-lg font-medium text-gray-900">
                                            <?= $patient['nama_pasien'] ?>
                                        </div>
                                        <div class="text-sm text-gray-500 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                            </svg>
                                            ID: <?= $patient['id_pasien'] ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <div class="flex space-x-2">
                                        <a href="admin?action=patients&sub_action=edit&id=<?= $patient['id_pasien'] ?>" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                        <a href="admin?action=patients&sub_action=delete&id=<?= $patient['id_pasien'] ?>" onclick="return confirm('Are you sure you want to delete this patient?');" class="inline-flex items-center px-3 py-1.5 border border-red-600 shadow-sm text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Delete
                                        </a>
                                        <a href="admin?action=patients&sub_action=view&id=<?= $patient['id_pasien'] ?>" class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm text-xs font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <p><?= $patient['alamat'] ?: 'No address provided' ?></p>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

<!-- Pagination -->
<div class="mt-5">
    <?= pagination($page, $totalPages, 'admin?action=patients') ?>
</div>

<?php
// Get the content of the output buffer
$content = ob_get_clean();

// Include the admin layout
require_once 'views/layouts/main.php';
?>