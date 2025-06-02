<?php
ob_start();
?>

<div class="max-w-7xl mx-auto py-6">
    <h1 class="text-3xl font-bold mb-4"><?= htmlspecialchars($doctor['nama_dokter']) ?></h1>
    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-4">
            <h2 class="text-xl font-semibold mb-2">Specialization</h2>
            <p><?= htmlspecialchars($doctor['specialization'] ?? 'General Practice') ?></p>
        </div>
        <?php if (!empty($doctor['phone'])): ?>
        <div class="mb-4">
            <h2 class="text-xl font-semibold mb-2">Phone</h2>
            <p><?= htmlspecialchars($doctor['phone']) ?></p>
        </div>
        <?php endif; ?>
        <?php if (!empty($doctor['email'])): ?>
        <div class="mb-4">
            <h2 class="text-xl font-semibold mb-2">Email</h2>
            <p><?= htmlspecialchars($doctor['email']) ?></p>
        </div>
        <?php endif; ?>
        <div class="mt-6">
            <a href="admin?action=doctors&sub_action=edit&id=<?= $doctor['id_dokter'] ?>" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors mr-2">Edit</a>
            <a href="admin?action=doctors&sub_action=delete&id=<?= $doctor['id_dokter'] ?>" onclick="return confirm('Are you sure you want to delete this doctor?');" class="inline-block px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">Delete</a>
            <a href="admin?action=doctors" class="inline-block px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition-colors ml-2">Back to List</a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.php';
?>
