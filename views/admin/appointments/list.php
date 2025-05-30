<?php
$pageTitle = 'All Appointments';
ob_start();
?>

<div class="max-w-7xl mx-auto pb-6 border-b border-gray-200 mt-5">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <h3 class="text-2xl font-bold text-gray-900 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2z" />
            </svg>
            Appointments
        </h3>
        <div class="mt-4 sm:mt-0">
            <a href="admin?action=appointments&sub_action=add" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New Appointment
            </a>
        </div>
    </div>
</div>

<!-- Search and filters -->
<div class="my-6">
    <form action="admin" method="GET" class="flex flex-col sm:flex-row gap-3">
        <input type="hidden" name="action" value="appointments">
        <div class="flex-1">
            <div class="relative rounded-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" id="search" value="<?= $search ?? '' ?>" class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Search appointments by patient name...">
            </div>
        </div>
        <div class="flex-1">
            <select name="status" class="block w-full py-2 px-3 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                <option value="">All Status</option>
                <option value="scheduled" <?= ($status ?? '') === 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
                <option value="completed" <?= ($status ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                <option value="cancelled" <?= ($status ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                Search
            </button>
            <?php if (!empty($search) || !empty($status)): ?>
                <a href="admin?action=appointments" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    Clear
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Appointments list -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
    <ul class="divide-y divide-gray-200">
        <?php if (empty($appointments)): ?>
            <li class="px-6 py-8">
                <div class="flex flex-col items-center justify-center text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-500 text-lg">No appointments found</p>
                    <p class="text-gray-400 text-sm mt-1">Try adjusting your search criteria</p>
                </div>
            </li>
        <?php else: ?>
            <?php foreach ($appointments as $i => $appt): ?>
                <li class="hover:bg-gray-50 transition-colors">
                    <a href="admin?action=appointments&sub_action=view&id=<?= $appt['id_appointment'] ?>" class="block">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-primary-100 flex items-center justify-center">
                                        <span class="text-primary-700 font-bold text-lg"><?= substr($appt['nama_pasien'], 0, 1) ?></span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-lg font-medium text-gray-900">
                                            <?= htmlspecialchars($appt['nama_pasien']) ?>
                                        </div>
                                        <div class="text-sm text-gray-500 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2z" />
                                            </svg>
                                            <?= htmlspecialchars($appt['tanggal_janji']) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-2 flex-shrink-0 flex flex-col items-end">
                                    <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold mb-2
                                        <?= $appt['status'] === 'completed' ? 'bg-green-100 text-green-800' : ($appt['status'] === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                                        <?= ucfirst($appt['status']) ?>
                                    </span>
                                    <div class="flex space-x-2">
                                        <a href="admin?action=appointments&sub_action=edit&id=<?= $appt['id_appointment'] ?>" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                        <a href="admin?action=appointments&sub_action=delete&id=<?= $appt['id_appointment'] ?>" class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm text-xs font-medium rounded-md text-red-600 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors" onclick="return confirm('Are you sure you want to delete this appointment?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

<div class="mt-5">
    <a href="admin?action=dashboard" class="inline-block px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 transition-colors">Back to Dashboard</a>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.php';
?>