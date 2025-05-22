<?php
// Start output buffering
ob_start();
$base_url = '/basis-data';
?>

<div class="bg-white overflow-hidden w-full">
    <!-- Hero Section -->
    <div class="relative">
        <div class="absolute inset-0 bg-gradient-to-r from-red-600 to-red-800"></div>
        <div class="relative py-24 px-6 sm:py-32 flex flex-col items-center text-center">
            <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl max-w-4xl leading-tight">
                Welcome to <?= APP_NAME ?>
            </h1>
            <div class="w-24 h-1 bg-white mx-auto my-6 rounded-full"></div>
            <p class="mt-4 text-xl text-white max-w-2xl leading-relaxed">
                Your comprehensive healthcare management solution for streamlined healthcare delivery and improved patient experience.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row gap-5">
                <a href="register" class="px-8 py-4 text-base font-medium rounded-lg text-red-600 bg-white hover:bg-gray-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    Patient Registration
                </a>
                <a href="login" class="px-8 py-4 text-base font-medium rounded-lg text-white bg-red-700 hover:bg-red-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    Sign in
                </a>
            </div>
        </div>
    </div>
    
    <!-- Services Section -->
    <div class="py-20 px-6 md:px-12">
        <div class="w-full max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    Our Services
                </h2>
                <div class="w-16 h-1 bg-red-500 mx-auto mb-6 rounded-full"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">We provide comprehensive healthcare services to meet all your medical needs.</p>
            </div>
            
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <div class="bg-white p-8 rounded-xl shadow-md border border-gray-100 hover:border-red-200 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
                    <div class="text-red-500 mb-6 bg-red-50 w-16 h-16 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-md text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Expert Medical Care</h3>
                    <p class="text-gray-600">Access to top medical professionals specializing in various fields.</p>
                </div>
                
                <div class="bg-white p-8 rounded-xl shadow-md border border-gray-100 hover:border-red-200 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
                    <div class="text-red-500 mb-6 bg-red-50 w-16 h-16 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-check text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Easy Appointments</h3>
                    <p class="text-gray-600">Simple online appointment scheduling and management.</p>
                </div>
                
                <div class="bg-white p-8 rounded-xl shadow-md border border-gray-100 hover:border-red-200 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
                    <div class="text-red-500 mb-6 bg-red-50 w-16 h-16 rounded-lg flex items-center justify-center">
                        <i class="fas fa-notes-medical text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Digital Records</h3>
                    <p class="text-gray-600">Access your medical history and test results anytime.</p>
                </div>
                
                <div class="bg-white p-8 rounded-xl shadow-md border border-gray-100 hover:border-red-200 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
                    <div class="text-red-500 mb-6 bg-red-50 w-16 h-16 rounded-lg flex items-center justify-center">
                        <i class="fas fa-credit-card text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Online Payments</h3>
                    <p class="text-gray-600">Convenient billing and payment options for services.</p>
                </div>
                
                <div class="bg-white p-8 rounded-xl shadow-md border border-gray-100 hover:border-red-200 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
                    <div class="text-red-500 mb-6 bg-red-50 w-16 h-16 rounded-lg flex items-center justify-center">
                        <i class="fas fa-pills text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Pharmacy Services</h3>
                    <p class="text-gray-600">Direct prescriptions to our pharmacy with tracking.</p>
                </div>
                
                <div class="bg-white p-8 rounded-xl shadow-md border border-gray-100 hover:border-red-200 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
                    <div class="text-red-500 mb-6 bg-red-50 w-16 h-16 rounded-lg flex items-center justify-center">
                        <i class="fas fa-comments text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Communication</h3>
                    <p class="text-gray-600">Direct messaging with healthcare providers.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Why Choose Us Section -->
    <div class="bg-gray-50 py-20 px-6 md:px-12">
        <div class="w-full max-w-7xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                Why Choose Us?
            </h2>
            <div class="w-16 h-1 bg-red-500 mx-auto mb-6 rounded-full"></div>
            <p class="text-gray-600 max-w-2xl mx-auto mb-16">We are committed to providing the highest quality healthcare services.</p>
            
            <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="text-red-500 mb-6 bg-red-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-heartbeat text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Patient-Centered Care</h3>
                    <p class="text-gray-600">Focused on your needs at every step.</p>
                </div>
                
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="text-red-500 mb-6 bg-red-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-stethoscope text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Expert Physicians</h3>
                    <p class="text-gray-600">Top-rated specialists with experience.</p>
                </div>
                
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="text-red-500 mb-6 bg-red-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-laptop-medical text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Advanced Technology</h3>
                    <p class="text-gray-600">State-of-the-art equipment and solutions.</p>
                </div>
                
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="text-red-500 mb-6 bg-red-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Convenient Hours</h3>
                    <p class="text-gray-600">Flexible scheduling for your lifestyle.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- How It Works Section -->
    <div class="py-20 px-6 md:px-12 bg-white">
        <div class="w-full max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    How It Works
                </h2>
                <div class="w-16 h-1 bg-red-500 mx-auto mb-6 rounded-full"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">Getting started with our healthcare platform is simple and straightforward.</p>
            </div>
            
            <div class="flex flex-col md:flex-row justify-between gap-8 max-w-4xl mx-auto">
                <div class="text-center bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex-1">
                    <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-red-100 text-red-600 text-2xl font-bold mb-6 mx-auto">1</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Register</h3>
                    <p class="text-gray-600">Create your secure patient account in minutes.</p>
                </div>
                
                <div class="text-center bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex-1">
                    <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-red-100 text-red-600 text-2xl font-bold mb-6 mx-auto">2</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Book</h3>
                    <p class="text-gray-600">Schedule appointments with your preferred providers.</p>
                </div>
                
                <div class="text-center bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex-1">
                    <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-red-100 text-red-600 text-2xl font-bold mb-6 mx-auto">3</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Manage</h3>
                    <p class="text-gray-600">Access your records and communicate with your healthcare team.</p>
                </div>
            </div>
            
            <div class="mt-16 text-center">
                <a href="register" class="inline-block px-8 py-4 text-base font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    Get Started Today
                </a>
            </div>
        </div>
    </div>
</div>

<?php
// Get the content of the output buffer
$content = ob_get_clean();

// Include the main layout
require_once 'views/layouts/main.php';
?>