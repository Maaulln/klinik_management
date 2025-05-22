<?php
$pageTitle = 'Patient Details';
ob_start();
?>

<div class="max-w-3xl mx-auto mt-10 bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6 text-primary-700"><?= htmlspecialchars($patient['nama_pasien']) ?></h2>
    <div class="mb-4">
        <strong>Address:</strong> <?= htmlspecialchars($patient['alamat']) ?>
    </div>

    <h3 class="text-xl font-semibold mt-8 mb-2 text-primary-600">Medical Records</h3>
    <?php if (!empty($medicalRecords)): ?>
        <ul class="mb-6">
            <?php foreach ($medicalRecords as $record): ?>
                <li class="mb-2 border-b pb-2">
                    <div><strong>Date:</strong> <?= htmlspecialchars($record['tanggal_catatan']) ?></div>
                    <div><strong>Doctor:</strong> <?= htmlspecialchars($record['nama_dokter']) ?></div>
                    <div><strong>Notes:</strong> <?= htmlspecialchars($record['isi_catatan']) ?></div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-gray-500 mb-6">No medical records found.</p>
    <?php endif; ?>

    <h3 class="text-xl font-semibold mt-8 mb-2 text-primary-600">Registrations</h3>
    <?php if (!empty($registrations)): ?>
        <ul class="mb-6">
            <?php foreach ($registrations as $reg): ?>
                <li class="mb-2 border-b pb-2">
                    <div><strong>Date:</strong> <?= htmlspecialchars($reg['waktu_registrasi']) ?></div>
                    <div><strong>Registration ID:</strong> <?= htmlspecialchars($reg['id_registrasi']) ?></div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-gray-500 mb-6">No registrations found.</p>
    <?php endif; ?>

    <h3 class="text-xl font-semibold mt-8 mb-2 text-primary-600">Transactions</h3>
    <?php if (!empty($transactions)): ?>
        <ul>
            <?php foreach ($transactions as $trx): ?>
                <li class="mb-2 border-b pb-2">
                    <div><strong>Date:</strong> <?= htmlspecialchars($trx['waktu_transaksi']) ?></div>
                    <div><strong>Cashier:</strong> <?= htmlspecialchars($trx['nama_kasir']) ?></div>
                    <div><strong>Amount:</strong> Rp<?= number_format($trx['harga'] ?? 0, 0, ',', '.') ?></div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-gray-500">No transactions found.</p>
    <?php endif; ?>

    <div class="mt-8">
        <a href="admin?action=patients" class="inline-block px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">Back to Patients</a>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.php';
?>