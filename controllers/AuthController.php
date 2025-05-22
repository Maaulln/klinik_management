<?php
// Authentication controller
global $path;

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Handle authentication routes
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'login':
        handleLogin();
        break;
    case 'register':
        handleRegister();
        break;
    case 'logout':
        handleLogout();
        break;
    case 'reset-password':
        handleResetPassword();
        break;
    default:
        // Default to login form if path is 'login'
        if ($path === 'login') {
            showLoginForm();
        } elseif ($path === 'register') {
            showRegisterForm();
        } else {
            http_response_code(404);
            require_once __DIR__ . '/../views/404.php';
        }
}

/**
 * Show login form
 */
function showLoginForm() {
    // Redirect if already logged in
    redirectIfAuthenticated('/');
    
    $pageTitle = 'login';
    require_once __DIR__ . '/../views/auth/login.php';
}

/**
 * Handle login
 */
function handleLogin() {    
    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlashMessage('Invalid form submission. Please try again.', 'error');
            redirect('/basis-data/login');
        }
        
        // Validate input
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            setFlashMessage('Please provide both username and password.', 'error');
            redirect('/basis-data/login');
        }
        
        // Authenticate user
        if (authenticate($username, $password)) {
            // Redirect to intended URL or default based on role
            $redirect = $_GET['redirect'] ?? '';
            if (!empty($redirect) && strpos($redirect, '/') === 0) {
                redirect($redirect);
            } else {
                $user = getCurrentUser();
                switch ($user['role']) {
                    case 'admin':
                        redirect('admin');
                        break;
                    case 'doctor':
                        redirect('doctor');
                        break;
                    case 'cashier':
                        redirect('cashier');
                        break;
                    case 'patient':
                        redirect('patient');
                        break;
                    default:
                        redirect('/');
                }
            }
        } else {
            setFlashMessage('Invalid username or password.', 'error');
            redirect('/basis-data/login');
        }
    } else {
        // Show login form for GET requests
        showLoginForm();
    }
}

/**
 * Show register form
 */
function showRegisterForm() {
    // Redirect if already logged in
    redirectIfAuthenticated('/');
    
    $pageTitle = 'Register';
    require_once __DIR__ . '/../views/auth/register.php';
}

/**
 * Handle registration
 */
function handleRegister() {
    // Redirect if already logged in
    redirectIfAuthenticated('/');
    
    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlashMessage('Invalid form submission. Please try again.', 'error');
            redirect('basis-data/register');
        }
        
        // Validate input
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $name = trim($_POST['name'] ?? '');
        $role = 'patient';
        
        // Validate required fields
        if (empty($username) || empty($email) || empty($password) || empty($name)) {
            setFlashMessage('Please fill all required fields.', 'error');
            redirect('basis-data/register');
        }
        
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            setFlashMessage('Please provide a valid email address.', 'error');
            redirect('basis-data/register');
        }
        
        // Validate password match
        if ($password !== $confirmPassword) {
            setFlashMessage('Passwords do not match.', 'error');
            redirect('basis-data/register');
        }
        
        // Validate password strength
        if (strlen($password) < 8) {
            setFlashMessage('Password must be at least 8 characters long.', 'error');
            redirect('basis-data/register');
        }
        
        // Register user
        $userData = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'name' => $name,
            'role' => $role,
            'address' => $_POST['address'] ?? ''
        ];
        
        $userId = registerUser($userData);
        
        if ($userId) {
            setFlashMessage('Registration successful. You can now log in.', 'success');
            redirect('/basis-data/login');
        } else {
            setFlashMessage('Username or email already exists.', 'error');
            redirect('basis-data/register');
        }
    } else {
        // Show registration form for GET requests
        showRegisterForm();
    }
}

/**
 * Handle logout
 */
function handleLogout() {
    logout();
    setFlashMessage('You have been logged out.', 'info');
    redirect('/basis-data/login');
}

/**
 * Handle password reset
 */
function handleResetPassword() {
    // Implement password reset functionality here
    $pageTitle = 'Reset Password';
    require_once __DIR__ . '/../views/auth/reset-password.php';
}
?>