<?php
$pageTitle = 'Edit Appointment';
ob_start();
?>

<div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6 text-primary-700">Edit Appointment</h2>
    <form action="admin?action=appointments&sub_action=edit&id=<?= htmlspecialchars($appointment['id_appointment']) ?>" method="POST" class="space-y-6">
        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

        <div>
            <label for="id_pasien" class="block text-sm font-medium text-gray-700">Patient</label>
            <select id="id_pasien" name="id_pasien" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="">Select a patient</option>
                <?php foreach ($patients as $patient): ?>
                    <option value="<?= htmlspecialchars($patient['id_pasien']) ?>" <?= $patient['id_pasien'] == $appointment['id_pasien'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($patient['nama_pasien']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="tanggal_janji" class="block text-sm font-medium text-gray-700">Appointment Date</label>
            <input type="date" id="tanggal_janji" name="tanggal_janji" value="<?= htmlspecialchars($appointment['tanggal_janji']) ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select id="status" name="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="scheduled" <?= $appointment['status'] === 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
                <option value="completed" <?= $appointment['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                <option value="cancelled" <?= $appointment['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>

        <div class="flex justify-end">
            <a href="admin?action=appointments" class="mr-4 inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">Update</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.php';
?>
