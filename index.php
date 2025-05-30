<?php
// Titik masuk utama aplikasi
session_start();

// Memuat konfigurasi dan fungsi helper
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

// Routing permintaan (request)
$path = isset($_GET['path']) ? $_GET['path'] : '';

// Memecah path untuk mendapatkan base route dan action
$parts = explode('/', trim($path, '/'));
$baseRoute = $parts[0] ?? '';
$action = $parts[1] ?? '';

// Daftar route dan controller yang menangani
$routes = [
    '' => 'controllers/HomeController.php',
    'login' => 'controllers/AuthController.php',
    'register' => 'controllers/AuthController.php',
    'logout' => 'controllers/AuthController.php',
    'admin' => 'controllers/AdminController.php',
    'patient' => 'controllers/PatientController.php',
    'doctor' => 'controllers/DoctorController.php',
    'cashier' => 'controllers/CashierController.php',
];

// Menyimpan informasi route ke variabel request agar bisa digunakan di controller
$_REQUEST['path'] = $path;
$_REQUEST['base_route'] = $baseRoute;
$_REQUEST['action'] = $action;

// Membuat path tersedia di file yang di-include
$GLOBALS['path'] = $path;
$GLOBALS['base_route'] = $baseRoute;
$GLOBALS['action'] = $action;

// Set parameter action khusus untuk route autentikasi
if ($baseRoute === 'login') {
    $_GET['action'] = $_GET['action'] ?? 'login';
    $path = 'login'; 
} elseif ($baseRoute === 'register') {
    $_GET['action'] = 'register';
    $path = 'register';
} elseif ($baseRoute === 'logout') {
    $_GET['action'] = 'logout';
    $path = 'logout'; 
}

// Mengecek apakah route tersedia
if (isset($routes[$baseRoute])) {
    // Memuat controller sesuai route
    require_once __DIR__ . '/' . $routes[$baseRoute];
} elseif (isset($routes[$path])) { 
    // Coba gunakan path penuh sebagai fallback
    require_once __DIR__ . '/' . $routes[$path];
} else {
    // Jika tidak ditemukan, tampilkan halaman 404
    http_response_code(404);
    require_once __DIR__ . '/views/404.php';
}
?>