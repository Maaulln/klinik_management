<?php
// Start output buffering
ob_start();
?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white py-8 px-10 shadow rounded-lg text-center">
        <div class="flex-shrink-0 flex justify-center mb-8">
            <a href="/" class="inline-flex">
                <span class="sr-only">Home</span>
                <span class="text-primary-600 text-5xl">
                    <i class="fas fa-hospital-alt"></i>
                </span>
            </a>
        </div>
        
        <div class="space-y-4">
            <p class="text-sm font-semibold text-primary-500 uppercase tracking-wide">404 Not Found</p>
            <h1 class="text-3xl font-bold text-gray-900">Page Not Found</h1>
            <p class="text-sm text-gray-600">The page you're looking for doesn't exist or has been moved</p>
        </div>

        <div class="mt-8">
            <a href="/basis-data" class="w-full inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                Return Home
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