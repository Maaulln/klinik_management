<?php
$pageTitle = 'Edit Doctor';
ob_start();
?>

<div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6 text-primary-700">Edit Doctor</h2>
    <form action="admin?action=doctors&sub_action=edit&id=<?= htmlspecialchars($doctor['id_dokter']) ?>" method="POST" class="space-y-6">
        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($doctor['nama_dokter'] ?? '') ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
        </div>
        <div>
            <label for="specialization" class="block text-sm font-medium text-gray-700">Specialization</label>
            <select id="specialization" name="specialization" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="" disabled <?= empty($doctor['specialization']) ? 'selected' : '' ?>>Select specialization</option>
                <option value="General Practitioner" <?= (isset($doctor['specialization']) && $doctor['specialization'] === 'General Practitioner') ? 'selected' : '' ?>>General Practitioner</option>
                <option value="Cardiologist" <?= (isset($doctor['specialization']) && $doctor['specialization'] === 'Cardiologist') ? 'selected' : '' ?>>Cardiologist</option>
                <option value="Dermatologist" <?= (isset($doctor['specialization']) && $doctor['specialization'] === 'Dermatologist') ? 'selected' : '' ?>>Dermatologist</option>
                <option value="Neurologist" <?= (isset($doctor['specialization']) && $doctor['specialization'] === 'Neurologist') ? 'selected' : '' ?>>Neurologist</option>
                <option value="Pediatrician" <?= (isset($doctor['specialization']) && $doctor['specialization'] === 'Pediatrician') ? 'selected' : '' ?>>Pediatrician</option>
                <option value="Psychiatrist" <?= (isset($doctor['specialization']) && $doctor['specialization'] === 'Psychiatrist') ? 'selected' : '' ?>>Psychiatrist</option>
                <option value="Radiologist" <?= (isset($doctor['specialization']) && $doctor['specialization'] === 'Radiologist') ? 'selected' : '' ?>>Radiologist</option>
                <option value="Surgeon" <?= (isset($doctor['specialization']) && $doctor['specialization'] === 'Surgeon') ? 'selected' : '' ?>>Surgeon</option>
                <option value="Orthopedist" <?= (isset($doctor['specialization']) && $doctor['specialization'] === 'Orthopedist') ? 'selected' : '' ?>>Orthopedist</option>
                <option value="Gynecologist" <?= (isset($doctor['specialization']) && $doctor['specialization'] === 'Gynecologist') ? 'selected' : '' ?>>Gynecologist</option>
            </select>
        </div>
        <div class="flex justify-end">
            <a href="admin?action=doctors" class="mr-4 inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">Update</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.php';
?>