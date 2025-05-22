<?php
$pageTitle = 'All Appointments';
ob_start();
?>

<div class="max-w-5xl mx-auto mt-10 bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6 text-primary-700">All Appointments</h2>
    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                <!-- Doctor column removed -->
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (!empty($appointments)): ?>
                <?php foreach ($appointments as $i => $appt): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap"><?= $i + 1 ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($appt['tanggal_janji']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($appt['nama_pasien']) ?></td>
                        <!-- Doctor column data removed -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
                                <?= $appt['status'] === 'completed' ? 'bg-green-100 text-green-800' : ($appt['status'] === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                                <?= ucfirst($appt['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="admin?action=appointments&sub_action=view&id=<?= $appt['id_appointment'] ?>" class="text-blue-600 hover:underline mr-2">View</a>
                            <a href="admin?action=appointments&sub_action=edit&id=<?= $appt['id_appointment'] ?>" class="text-green-600 hover:underline mr-2">Edit</a>
                            <a href="admin?action=appointments&sub_action=delete&id=<?= $appt['id_appointment'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure you want to delete this appointment?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No appointments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="mt-8">
        <a href="admin?action=dashboard" class="inline-block px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">Back to Dashboard</a>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.php';
?>