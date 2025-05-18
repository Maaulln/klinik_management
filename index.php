<?php
// Main entry point for the application
session_start();

// Include configuration and helper functions
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

// Route the request
$path = isset($_GET['path']) ? $_GET['path'] : '';

// Parse the path to get the base route and action
$parts = explode('/', trim($path, '/'));
$baseRoute = $parts[0] ?? '';
$action = $parts[1] ?? '';

// Define routes and their controllers
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

// Store route information in request variables for controllers to use
$_REQUEST['path'] = $path;
$_REQUEST['base_route'] = $baseRoute;
$_REQUEST['action'] = $action;

// Make path available to included files
$GLOBALS['path'] = $path;
$GLOBALS['base_route'] = $baseRoute;
$GLOBALS['action'] = $action;

// Set action parameter for auth routes
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

// Check if route exists
if (isset($routes[$baseRoute])) {
    require_once __DIR__ . '/' . $routes[$baseRoute];
} elseif (isset($routes[$path])) { 
    // Try the full path as fallback
    require_once __DIR__ . '/' . $routes[$path];
} else {
    // 404 Page Not Found
    http_response_code(404);
    require_once __DIR__ . '/views/404.php';
}
?>