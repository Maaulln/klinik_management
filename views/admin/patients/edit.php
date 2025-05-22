<?php

$pageTitle = 'Edit Patient';
ob_start();
?>

<div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6 text-primary-700">Edit Patient</h2>
    <form action="admin?action=patients&sub_action=edit&id=<?= htmlspecialchars($id) ?>" method="POST" class="space-y-6">
        <div>
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($name ?? '') ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
        </div>
        <div>
            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
            <input type="text" id="address" name="address" value="<?= htmlspecialchars($address ?? '') ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
        </div>
        <div class="flex justify-end">
            <a href="admin?action=patients" class="mr-4 inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">Update</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.php';
?>