<?php
ob_start();
?>

<div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6 text-primary-700">Create Transaction</h2>
    <form action="patient?action=billing&sub_action=create" method="POST" class="space-y-6">
        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
        <?php if (isset($_GET['id_appointment'])): ?>
            <input type="hidden" name="id_appointment" value="<?= (int)$_GET['id_appointment'] ?>">
        <?php endif; ?>
        <div>
            <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
            <input type="number" step="0.01" min="0" id="amount" name="amount" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500" placeholder="Enter amount">
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500" placeholder="Enter description (optional)"><?php
                if (isset($_GET['id_appointment'])) {
                    echo "Payment for appointment #" . (int)$_GET['id_appointment'];
                }
            ?></textarea>
        </div>
        <div class="flex justify-end">
            <a href="patient?action=billing&sub_action=list" class="mr-4 inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">Create</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.php';
?>
