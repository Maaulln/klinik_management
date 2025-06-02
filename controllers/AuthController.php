<?php
// Controller autentikasi
global $path;

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Routing utama autentikasi
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
        // Jika path adalah 'login', tampilkan form login
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
 * Menampilkan form login
 */
function showLoginForm() {
    // Redirect jika sudah login
    redirectIfAuthenticated('/');
    
    $pageTitle = 'Login';
    require_once __DIR__ . '/../views/auth/login.php';
}

/**
 * Proses login
 */
function handleLogin() {    
    // Cek jika form dikirim
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validasi CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlashMessage('Form tidak valid. Silakan coba lagi.', 'error');
            redirect('/basis-data/login');
        }
        
        // Validasi input username dan password
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            setFlashMessage('Username dan password wajib diisi.', 'error');
            redirect('/basis-data/login');
        }
        
        // Autentikasi user
        if (authenticate($username, $password)) {
            // Redirect ke halaman sesuai role
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
            setFlashMessage('Username atau password salah.', 'error');
            redirect('/basis-data/login');
        }
    } else {
        // Jika GET, tampilkan form login
        showLoginForm();
    }
}

/**
 * Menampilkan form registrasi
 */
function showRegisterForm() {
    // Redirect jika sudah login
    redirectIfAuthenticated('/');
    
    $pageTitle = 'Register';
    require_once __DIR__ . '/../views/auth/register.php';
}

/**
 * Proses registrasi user baru
 */
function handleRegister() {
    // Redirect jika sudah login
    redirectIfAuthenticated('/');
    
    // Cek jika form dikirim
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validasi CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlashMessage('Form tidak valid. Silakan coba lagi.', 'error');
            redirect('basis-data/register');
        }
        
        // Validasi input
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $name = trim($_POST['name'] ?? '');
        $role = 'patient'; // Default role untuk registrasi pasien
        
        // Validasi field wajib
        if (empty($username) || empty($email) || empty($password) || empty($name)) {
            setFlashMessage('Semua field wajib diisi.', 'error');
            redirect('basis-data/register');
        }
        
        // Validasi format email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            setFlashMessage('Email tidak valid.', 'error');
            redirect('basis-data/register');
        }
        
        // Validasi konfirmasi password
        if ($password !== $confirmPassword) {
            setFlashMessage('Password tidak sama.', 'error');
            redirect('basis-data/register');
        }
        
        // Validasi panjang password
        if (strlen($password) < 8) {
            setFlashMessage('Password minimal 8 karakter.', 'error');
            redirect('basis-data/register');
        }
        
        // Query untuk mendaftarkan user baru ke database
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
            setFlashMessage('Registrasi berhasil. Silakan login.', 'success');
            redirect('/basis-data/login');
        } else {
            setFlashMessage('Username atau email sudah digunakan.', 'error');
            redirect('basis-data/register');
        }
    } else {
        // Jika GET, tampilkan form registrasi
        showRegisterForm();
    }
}

/**
 * Proses logout user
 */
function handleLogout() {
    logout();
    setFlashMessage('Anda berhasil logout.', 'info');
    redirect('/basis-data/login');
}

/**
 * Menampilkan form reset password
 */
function handleResetPassword() {
    // Fitur reset password
    $pageTitle = 'Reset Password';
    require_once __DIR__ . '/../views/auth/reset-password.php';
}
?> 