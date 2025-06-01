<?php
// Start output buffering
ob_start();
?>

<div class="pb-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
    <h3 class="text-2xl leading-6 font-bold text-gray-900">
        <i class="fas fa-calendar-check mr-2 text-primary-500"></i> My Appointments
    </h3>
    <div class="mt-3 sm:mt-0 sm:ml-4">
        <a href="patient?action=appointments&sub_action=request" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            <i class="fas fa-calendar-plus mr-2"></i> Request Appointment
        </a>
    </div>
</div>

<!-- Appointments Tabs -->
<div class="mt-6">
    <div class="sm:hidden">
        <label for="appointmentsTabs" class="sr-only">Select a tab</label>
        <select id="appointmentsTabs" name="appointmentsTabs" class="block w-full focus:ring-primary-500 focus:border-primary-500 border-gray-300 rounded-md">
            <option value="upcoming" selected>Upcoming</option>
            <option value="past">Past</option>
        </select>
    </div>
    <div class="hidden sm:block">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button class="tablink border-primary-500 text-primary-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="upcoming">
                    Upcoming Appointments
                </button>
                <button class="tablink border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="past">
                    Past Appointments
                </button>
            </nav>
        </div>
    </div>
</div>

<!-- Appointments Content -->
<div class="mt-6">
    <!-- Upcoming Appointments -->
    <div id="upcoming" class="tabcontent block">
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                <?php if (empty($upcomingAppointments)): ?>
                    <li class="px-4 py-4 sm:px-6">
                        <p class="text-gray-500 text-center py-4">No upcoming appointments.</p>
                    </li>
                <?php else: ?>
                    <?php foreach ($upcomingAppointments as $appointment): ?>
                        <li>
                            <div class="block hover:bg-gray-50">
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                Appointment #<?= $appointment['id_appointment'] ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?= formatDate($appointment['tanggal_janji'] . ' ' . $appointment['waktu_janji'], 'F j, Y \a\t g:i a') ?>
                                            </div>
                                        </div>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <?php
                                            // Map status to label and style
                                            $statusLabels = [
                                                'scheduled' => ['label' => 'Scheduled', 'class' => 'bg-blue-100 text-blue-800'],
                                                'completed' => ['label' => 'Completed', 'class' => 'bg-gray-100 text-gray-800'],
                                                'cancelled' => ['label' => 'Cancelled', 'class' => 'bg-red-100 text-red-800'],
                                                'pending' => ['label' => 'Pending', 'class' => 'bg-yellow-100 text-yellow-800'],
                                                'rescheduled' => ['label' => 'Rescheduled', 'class' => 'bg-purple-100 text-purple-800'],
                                            ];
                                            $status = strtolower($appointment['status']);
                                            $label = $statusLabels[$status]['label'] ?? ucfirst($status);
                                            $class = $statusLabels[$status]['class'] ?? 'bg-gray-100 text-gray-800';
                                            ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $class ?>">
                                                <?= htmlspecialchars($label) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-2 flex justify-end space-x-2">
                                        <a href="patient?action=appointments&sub_action=cancel&id=<?= $appointment['id_appointment'] ?>" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    
    <!-- Past Appointments -->
    <div id="past" class="tabcontent hidden">
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                <?php 
                $hasPast = false;
                foreach ($appointments as $appointment): 
                    if (strtotime($appointment['tanggal_janji']) <= time()):
                        $hasPast = true;
                ?>
                    <li>
                        <div class="block hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            Appointment #<?= $appointment['id_appointment'] ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?= formatDate(trim(($appointment['tanggal_janji'] ?? '') . ' ' . ($appointment['waktu_janji'] ?? '')),'F j, Y \a\t g:i a' ) ?>                                        </div>
                                    </div>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <?php
                                        // Map status to label and style
                                        $statusLabels = [
                                            'scheduled' => ['label' => 'Scheduled', 'class' => 'bg-blue-100 text-blue-800'],
                                            'completed' => ['label' => 'Completed', 'class' => 'bg-gray-100 text-gray-800'],
                                            'cancelled' => ['label' => 'Cancelled', 'class' => 'bg-red-100 text-red-800'],
                                            'pending' => ['label' => 'Pending', 'class' => 'bg-yellow-100 text-yellow-800'],
                                            'rescheduled' => ['label' => 'Rescheduled', 'class' => 'bg-purple-100 text-purple-800'],
                                        ];
                                        $status = strtolower($appointment['status']);
                                        $label = $statusLabels[$status]['label'] ?? ucfirst($status);
                                        $class = $statusLabels[$status]['class'] ?? 'bg-gray-100 text-gray-800';
                                        ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $class ?>">
                                            <?= htmlspecialchars($label) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php 
                    endif;
                endforeach; 
                
                if (!$hasPast):
                ?>
                    <li class="px-4 py-4 sm:px-6">
                        <p class="text-gray-500 text-center py-4">No past appointments.</p>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    
    <!-- Past Appointments -->
    <div id="past" class="tabcontent hidden">
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                <?php if (empty($pastAppointments)): ?>
                    <li class="px-4 py-4 sm:px-6">
                        <p class="text-gray-500 text-center py-4">No past appointments.</p>
                    </li>
                <?php else: ?>
                    <?php foreach ($pastAppointments as $appointment): ?>
                        <li>
                            <div class="block hover:bg-gray-50">
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                Appointment #<?= $appointment['id_appointment'] ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?= formatDate(trim(($appointment['tanggal_janji'] ?? '') . ' ' . ($appointment['waktu_janji'] ?? '')),'F j, Y \a\t g:i a' ) ?>
                                            </div>
                                        </div>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <?php
                                            // Map status to label and style
                                            $statusLabels = [
                                                'scheduled' => ['label' => 'Scheduled', 'class' => 'bg-blue-100 text-blue-800'],
                                                'completed' => ['label' => 'Completed', 'class' => 'bg-gray-100 text-gray-800'],
                                                'cancelled' => ['label' => 'Cancelled', 'class' => 'bg-red-100 text-red-800'],
                                                'pending' => ['label' => 'Pending', 'class' => 'bg-yellow-100 text-yellow-800'],
                                                'rescheduled' => ['label' => 'Rescheduled', 'class' => 'bg-purple-100 text-purple-800'],
                                            ];
                                            $status = strtolower($appointment['status']);
                                            $label = $statusLabels[$status]['label'] ?? ucfirst($status);
                                            $class = $statusLabels[$status]['class'] ?? 'bg-gray-100 text-gray-800';
                                            ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $class ?>">
                                                <?= htmlspecialchars($label) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>

<!-- Add JavaScript for tabs -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tablinks = document.querySelectorAll('.tablink');
        const tabcontents = document.querySelectorAll('.tabcontent');
        
        tablinks.forEach(function(tablink) {
            tablink.addEventListener('click', function() {
                const tab = this.dataset.tab;
                
                // Deactivate all tabs
                tablinks.forEach(function(t) {
                    t.classList.remove('border-primary-500', 'text-primary-600');
                    t.classList.add('border-transparent', 'text-gray-500');
                });
                
                // Hide all tab content
                tabcontents.forEach(function(content) {
                    content.classList.add('hidden');
                    content.classList.remove('block');
                });
                
                // Activate the clicked tab
                this.classList.remove('border-transparent', 'text-gray-500');
                this.classList.add('border-primary-500', 'text-primary-600');
                
                // Show the corresponding content
                document.getElementById(tab).classList.remove('hidden');
                document.getElementById(tab).classList.add('block');
            });
        });
        
        // Also handle the mobile select
        const mobileSelect = document.getElementById('appointmentsTabs');
        if (mobileSelect) {
            mobileSelect.addEventListener('change', function() {
                const tab = this.value;
                
                // Hide all tab content
                tabcontents.forEach(function(content) {
                    content.classList.add('hidden');
                    content.classList.remove('block');
                });
                
                // Show the selected content
                document.getElementById(tab).classList.remove('hidden');
                document.getElementById(tab).classList.add('block');
                
                // Update the desktop tabs
                tablinks.forEach(function(t) {
                    if (t.dataset.tab === tab) {
                        t.classList.remove('border-transparent', 'text-gray-500');
                        t.classList.add('border-primary-500', 'text-primary-600');
                    } else {
                        t.classList.remove('border-primary-500', 'text-primary-600');
                        t.classList.add('border-transparent', 'text-gray-500');
                    }
                });
            });
        }
    });
</script>

<?php
// Get the content of the output buffer
$content = ob_get_clean();

// Include the patient layout
require_once 'views/layouts/main.php';
?>