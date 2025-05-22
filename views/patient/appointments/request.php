<?php
// Start output buffering
ob_start();
?>

<div class="pb-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
    <h3 class="text-2xl leading-6 font-bold text-gray-900">
        <i class="fas fa-calendar-plus mr-2 text-primary-500"></i> Request Appointment
    </h3>
    <div class="mt-3 flex sm:mt-0">
        <a href="patient?action=appointments" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            <i class="fas fa-arrow-left mr-2"></i> Back to Appointments
        </a>
    </div>
</div>

<!-- Appointment Request Form -->
<div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <form action="patient?action=appointments&sub_action=request" method="POST">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
            
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="appointment_date" class="block text-sm font-medium text-gray-700">
                        Date
                    </label>
                    <div class="mt-1">
                        <input type="date" name="appointment_date" id="appointment_date" min="<?= date('Y-m-d') ?>" required class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <p class="mt-2 text-sm text-gray-500">
                        Select a future date for your appointment.
                    </p>
                </div>
                <div class="sm:col-span-3">
                    <label for="id_dokter" class="block text-sm font-medium text-gray-700">
                    Doctor
                    </label>
                        <div class="mt-1">
                            <select name="id_dokter" id="id_dokter" required class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">-- Select Doctor --</option>
                                <?php foreach ($doctors as $doctor): ?>
                                <option value="<?= $doctor['id_dokter'] ?>"><?= htmlspecialchars($doctor['nama_dokter']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <p class="mt-2 text-sm text-gray-500">
                        Choose your preferred doctor.
                    </p>
                </div>
                <div class="sm:col-span-3">
                    <label for="appointment_time" class="block text-sm font-medium text-gray-700">
                        Time
                    </label>
                    <div class="mt-1">
                        <input type="time" name="appointment_time" id="appointment_time" required class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <p class="mt-2 text-sm text-gray-500">
                        Select a preferred time for your appointment.
                    </p>
                </div>
                
                <div class="sm:col-span-6">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" required class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="font-medium text-gray-700">I acknowledge that this is a request and subject to confirmation</label>
                            <p class="text-gray-500">Your appointment request will be reviewed, and you will be notified once it's confirmed or if any changes are needed.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <a href="patient?action=appointments" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Cancel
                </a>
                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Request Appointment
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Appointment Information -->
<div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 bg-blue-50 border-b border-blue-200">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500"></i>
            </div>
            <h3 class="ml-2 text-lg leading-6 font-medium text-blue-800">
                Appointment Information
            </h3>
        </div>
    </div>
    <div class="px-4 py-5 sm:p-6">
        <h4 class="text-base font-semibold text-gray-900 mb-3">Important Notes:</h4>
        <ul class="space-y-2 text-sm text-gray-600">
            <li class="flex items-start">
                <div class="flex-shrink-0 mt-0.5">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <p class="ml-2">Please arrive 15 minutes before your scheduled appointment for check-in procedures.</p>
            </li>
            <li class="flex items-start">
                <div class="flex-shrink-0 mt-0.5">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <p class="ml-2">Bring any relevant medical records, imaging results, or lab work related to your visit.</p>
            </li>
            <li class="flex items-start">
                <div class="flex-shrink-0 mt-0.5">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <p class="ml-2">If you need to cancel or reschedule, please do so at least 24 hours in advance.</p>
            </li>
            <li class="flex items-start">
                <div class="flex-shrink-0 mt-0.5">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <p class="ml-2">A confirmation email will be sent once your appointment is approved.</p>
            </li>
        </ul>
    </div>
</div>

<?php
// Get the content of the output buffer
$content = ob_get_clean();

// Include the patient layout
require_once 'views/layouts/main.php';
?>