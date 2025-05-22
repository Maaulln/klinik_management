<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? APP_NAME ?></title>
    <base href="/basis-data/">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            200: '#fecaca',
                            300: '#fca5a5',
                            400: '#f87171',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                            800: '#991b1b',
                            900: '#7f1d1d',
                            },
                            secondary: {
                            50: '#f9fafb',
                            100: '#f3f4f6',
                            200: '#e5e7eb',
                            300: '#d1d5db',
                            400: '#9ca3af',
                            500: '#6b7280',
                            600: '#4b5563',
                            700: '#374151',
                            800: '#1f2937',
                            900: '#111827',
                            },
                            accent: {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            200: '#fecaca',
                            300: '#fca5a5',
                            400: '#f87171',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                            800: '#991b1b',
                            900: '#7f1d1d',
                        },
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-md">
        <div class="w-full px-0">
            <div class="flex justify-between h-16 px-6 md:px-12">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/" class="text-red-600 font-bold text-xl flex items-center">
                            <i class="fas fa-hospital-alt mr-2 text-2xl"></i>
                            <span class="tracking-wide"><?= APP_NAME ?></span>
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <?php if (isLoggedIn()): ?>
                        <div class="ml-3 relative group">
                            <div>
                                <button type="button" id="user-menu-button"
            class="flex items-center max-w-xs text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
            aria-expanded="false" aria-haspopup="true">
            <span class="sr-only">Open user menu</span>
            <div class="h-9 w-9 rounded-full bg-red-600 flex items-center justify-center text-white font-medium">
                <?= substr($_SESSION['username'], 0, 1) ?>
            </div>
            <span class="ml-2 text-gray-700 font-medium"><?= $_SESSION['username'] ?></span>
            <svg class="ml-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
        </div>
            <div id="user-menu-dropdown"
            class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
            role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
            <?php if (hasRole('admin')): ?>
                <a href="admin" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors" role="menuitem">Admin Dashboard</a>
            <?php elseif (hasRole('doctor')): ?>
                <a href="doctor" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors" role="menuitem">Doctor Dashboard</a>
            <?php elseif (hasRole('cashier')): ?>
                <a href="cashier" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors" role="menuitem">Cashier Dashboard</a>
            <?php elseif (hasRole('patient')): ?>
                <a href="patient" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors" role="menuitem">Patient Dashboard</a>
            <?php endif; ?>
            <a href="login?action=logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors" role="menuitem">Sign out</a>
        </div>
                        </div>
                    <?php else: ?>
                        <a href="login" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all">
                            Sign in
                        </a>
                        <a href="register" class="ml-4 inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all">
                            Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main content -->
    <main class="flex-grow">
        <div class="w-full px-0 py-0">
            <?= flashMessage() ?>
            <?= $content ?? '' ?>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="w-full px-6 py-6 md:px-12">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-500 text-sm mb-4 md:mb-0">
                    &copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-red-500 transition-colors">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-red-500 transition-colors">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-red-500 transition-colors">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-red-500 transition-colors">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('user-menu-button');
    const menu = document.getElementById('user-menu-dropdown');
    let open = false;

    btn && btn.addEventListener('click', function(e) {
        e.stopPropagation();
        open = !open;
        menu.classList.toggle('hidden', !open);
    });

    document.addEventListener('click', function(e) {
        if (open && !btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.add('hidden');
            open = false;
        }
    });
});
    </script>
</body>
</html>