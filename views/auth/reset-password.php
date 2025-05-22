<?php
// Start output buffering
ob_start();
?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white py-8 px-10 shadow rounded-lg">
        <div class="mb-8">
            <h2 class="text-center text-2xl font-bold text-gray-900">
                Reset your password
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Enter your email address below and we'll send you instructions to reset your password.
            </p>
        </div>
        
        <form class="space-y-6" action="login?action=reset-password" method="POST">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
            
            <div class="rounded-md shadow-sm">
                <div>
                    <label for="email" class="sr-only">Email address</label>
                    <input id="email" name="email" type="email" required class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary-500 focus:border-primary-500 focus:z-10 sm:text-sm" placeholder="Email address">
                </div>
            </div>


            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Send Reset Instructions
                </button>
            </div>
        </form>
        <div class="mt-6 text-center text-sm">
            <span class="text-gray-600">Or</span>
            <a href="login" class="ml-1 font-medium text-primary-600 hover:text-primary-500">
                sign in to your account
            </a>
        </div>
    </div>
</div>

<?php
// Get the content of the output buffer
$content = ob_get_clean();

// Include the main layout
require_once 'views/layouts/main.php';
?>
