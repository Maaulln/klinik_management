<?php
// Start output buffering
ob_start();
?>

<div class="min-h-full flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-red-50 to-white">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-lg border border-gray-100">
        <div class="text-center">
            <h2 class="text-center text-3xl font-bold text-gray-900 tracking-tight">
                Create a new account
            </h2>
            <div class="w-16 h-1 bg-red-500 mx-auto my-4 rounded-full"></div>
            <p class="mt-2 text-center text-gray-600">
                Or
                <a href="login" class="font-medium text-red-600 hover:text-red-500 transition-colors">
                    sign in to your existing account
                </a>
            </p>
        </div>
        <form class="mt-8 space-y-6" action="register?action=register" method="POST">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
            <div class="rounded-lg shadow-sm space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input id="name" name="name" type="text" required class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-red-500 focus:border-red-500 transition-colors sm:text-sm" placeholder="Enter your full name">
                    </div>
                </div>
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user-tag text-gray-400"></i>
                        </div>
                        <input id="username" name="username" type="text" required class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-red-500 focus:border-red-500 transition-colors sm:text-sm" placeholder="Choose a username">
                    </div>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" required class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-red-500 focus:border-red-500 transition-colors sm:text-sm" placeholder="Enter your email address">
                    </div>
                </div>
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <div class="relative">
                        <div class="absolute top-3 left-0 pl-3 flex items-start pointer-events-none">
                            <i class="fas fa-home text-gray-400"></i>
                        </div>
                        <textarea id="address" name="address" required class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-red-500 focus:border-red-500 transition-colors sm:text-sm" placeholder="Enter your address" rows="3"></textarea>
                    </div>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" required class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-red-500 focus:border-red-500 transition-colors sm:text-sm" placeholder="Create a password">
                    </div>
                </div>
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="confirm_password" name="confirm_password" type="password" required class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-red-500 focus:border-red-500 transition-colors sm:text-sm" placeholder="Confirm your password">
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-red-500 group-hover:text-red-400 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Register
                </button>
            </div>
        </form>
    </div>
</div>

<?php
// Get the content of the output buffer
$content = ob_get_clean();

// Include the main layout
require_once __DIR__ . "/../layouts/main.php";
?>