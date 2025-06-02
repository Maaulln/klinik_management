<?php
ob_start();
?>

<div class="pb-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
    <h3 class="text-2xl leading-6 font-bold text-gray-900">
        <i class="fas fa-credit-card mr-2 text-primary-500"></i> Buat Pembayaran
    </h3>
    <div class="mt-3 flex sm:mt-0">
        <a href="patient?action=billing" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
</div>

<div class="mt-5">
    <form action="patient?action=billing&sub_action=create" method="POST" class="space-y-6 bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
        
        <?php if (isset($_GET['id_appointment'])): ?>
            <input type="hidden" name="id_appointment" value="<?= (int)$_GET['id_appointment'] ?>">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Appointment</h3>
                    <p class="mt-1 text-sm text-gray-500">Detail appointment yang akan dibayar.</p>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6">
                            <label class="block text-sm font-medium text-gray-700">ID Appointment</label>
                            <div class="mt-1 text-sm text-gray-900">#<?= (int)$_GET['id_appointment'] ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden sm:block" aria-hidden="true">
                <div class="py-5">
                    <div class="border-t border-gray-200"></div>
                </div>
            </div>
        <?php endif; ?>

        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Detail Pembayaran</h3>
                <p class="mt-1 text-sm text-gray-500">Masukkan informasi pembayaran yang akan dilakukan.</p>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-4">
                        <label for="amount" class="block text-sm font-medium text-gray-700">Jumlah Pembayaran</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="amount" id="amount" class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-12 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0" required min="1">
                        </div>
                    </div>

                    <div class="col-span-6">
                        <label for="description" class="block text-sm font-medium text-gray-700">Keterangan</label>
                        <div class="mt-1">
                            <textarea id="description" name="description" rows="3" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border border-gray-300 rounded-md"><?php
                                if (isset($_GET['id_appointment'])) {
                                    echo "Pembayaran untuk appointment #" . (int)$_GET['id_appointment'];
                                }
                            ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <i class="fas fa-save mr-2"></i> Simpan Pembayaran
            </button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.php';
?>