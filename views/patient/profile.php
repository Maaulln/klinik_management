<?php
// Start output buffering
ob_start();
?>

<div class="pb-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
    <h3 class="text-2xl leading-6 font-bold text-gray-900">
        <i class="fas fa-user-circle mr-2 text-primary-500"></i> My Profile
    </h3>
    <div class="mt-3 flex sm:mt-0">
        <a href="patient" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
    </div>
</div>

<!-- Profile Information -->
<div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
    <div class="sm:col-span-6">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Personal Information
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Update your account details and preferences.
                </p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <form action="patient?action=profile" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                    
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Full name
                            </label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" value="<?= $patient['nama_pasien'] ?>" required class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                        <div class="sm:col-span-3">
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                Email address
                            </label>
                            <div class="mt-1">
                                <input type="email" name="email" id="email" value="<?= $user['email'] ?>" required class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                        <div class="sm:col-span-6">
                            <label for="address" class="block text-sm font-medium text-gray-700">
                                Address
                            </label>
                            <div class="mt-1">
                                <textarea id="address" name="address" rows="3" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"><?= $patient['alamat'] ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Change Password
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Leave blank to keep your current password.
                        </p>
                        
                        <div class="mt-4 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-2">
                                <label for="current_password" class="block text-sm font-medium text-gray-700">
                                    Current password
                                </label>
                                <div class="mt-1">
                                    <input type="password" name="current_password" id="current_password" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                            
                            <div class="sm:col-span-2">
                                <label for="new_password" class="block text-sm font-medium text-gray-700">
                                    New password
                                </label>
                                <div class="mt-1">
                                    <input type="password" name="new_password" id="new_password" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                            
                            <div class="sm:col-span-2">
                                <label for="confirm_password" class="block text-sm font-medium text-gray-700">
                                    Confirm password
                                </label>
                                <div class="mt-1">
                                    <input type="password" name="confirm_password" id="confirm_password" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="button" onclick="window.location.href='patient'" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Cancel
                        </button>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// Get the content of the output buffer
$content = ob_get_clean();

// Include the patient layout
require_once 'views/layouts/main.php';
?>