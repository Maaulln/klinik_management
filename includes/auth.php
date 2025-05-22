<?php
// Authentication functions
require_once __DIR__ . "/../config/database.php";

/**
 * Check if user is logged in
 * 
 * @return bool True if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Get current user
 * 
 * @return array|null User data or null if not logged in
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $sql = "SELECT * FROM users WHERE id_user = ?";
    return dbQuerySingle($sql, [$_SESSION['user_id']]);
}

/**
 * Check if current user has role
 * 
 * @param string|array $roles Role(s) to check
 * @return bool True if user has role
 */
function hasRole($roles) {
    if (!isLoggedIn()) {
        return false;
    }
    
    $user = getCurrentUser();
    if (!$user) {
        return false;
    }
    
    if (is_string($roles)) {
        return $user['role'] === $roles;
    }
    
    if (is_array($roles)) {
        return in_array($user['role'], $roles);
    }
    
    return false;
}

/**
 * Authenticate user
 * 
 * @param string $username Username
 * @param string $password Password
 * @return bool True if authentication successful
 */
function authenticate($username, $password) {
    $sql = "SELECT * FROM users WHERE username = ? AND is_active = TRUE";
    $user = dbQuerySingle($sql, [$username]);
    
    if (!$user) {
        return false;
    }
    
    // Compare plain text password (not secure, per user request)
    if ($password === $user['password']) {
        // Set session variables
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['last_activity'] = time();
        
        // Create a new session record
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + SESSION_LIFETIME);
        
        $sql = "INSERT INTO sessions (id_user, session_token, expires_at) VALUES (?, ?, ?)";
        dbExecute($sql, [$user['id_user'], $token, $expires]);
        
        // Set session cookie
        setcookie('session_token', $token, time() + SESSION_LIFETIME, '/', '', false, true);
        
        return true;
    }
    
    return false;
}

/**
 * Register new user
 * 
 * @param array $userData User data
 * @return int|bool User ID or false if registration failed
 */
function registerUser($userData) {
    // Check if username or email already exists
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $existingUser = dbQuerySingle($sql, [$userData['username'], $userData['email']]);
    
    if ($existingUser) {
        return false;
    }
    
    // Use plain password without hashing (not recommended)
    $plainPassword = $userData['password'];
    
    // Begin transaction
    dbBeginTransaction();
    
    try {
        // Insert into specific role table if needed
        $referenceId = null;
        
        if ($userData['role'] === 'patient') {
            $sql = "INSERT INTO pasien (nama_pasien, alamat) VALUES (?, ?) RETURNING id_pasien";
            $result = dbQuerySingle($sql, [$userData['name'], $userData['address'] ?? '']);
            $referenceId = $result['id_pasien'];
        } elseif ($userData['role'] === 'doctor') {
            $sql = "INSERT INTO dokter (nama_dokter) VALUES (?) RETURNING id_dokter";
            $result = dbQuerySingle($sql, [$userData['name']]);
            $referenceId = $result['id_dokter'];
        } elseif ($userData['role'] === 'cashier') {
            $sql = "INSERT INTO petugas_kasir (nama_kasir) VALUES (?) RETURNING id_kasir";
            $result = dbQuerySingle($sql, [$userData['name']]);
            $referenceId = $result['id_kasir'];
        }
        
        // Insert into users table
        $sql = "INSERT INTO users (username, password, email, role, id_reference) VALUES (?, ?, ?, ?, ?) RETURNING id_user";
        $result = dbQuerySingle($sql, [
            $userData['username'],
            $plainPassword,
            $userData['email'],
            $userData['role'],
            $referenceId
        ]);
        
        // Commit transaction
        dbCommit();
        
        return $result['id_user'];
    } catch (Exception $e) {
        // Rollback transaction
        dbRollback();
        
        if (DEBUG_MODE) {
            die("Registration failed: " . $e->getMessage());
        } else {
            return false;
        }
    }
}

/**
 * Logout user
 * 
 * @return void
 */
function logout() {
    error_log("DEBUG: logout() called");
    // Delete session record
    if (isset($_SESSION['user_id']) && isset($_COOKIE['session_token'])) {
        $sql = "DELETE FROM sessions WHERE id_user = ? AND session_token = ?";
        dbExecute($sql, [$_SESSION['user_id'], $_COOKIE['session_token']]);
        error_log("DEBUG: session record deleted for user_id=".$_SESSION['user_id']);
    }
    
    // Unset all session variables
    $_SESSION = [];
    error_log("DEBUG: session variables cleared");
    
    // Delete the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        // Override path to '/basis-data/' to match app base path
        setcookie(
            session_name(),
            '',
            time() - 42000,
            '/basis-data/',
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
        error_log("DEBUG: session cookie deleted with path /basis-data/");
    }
    
    // Delete the session token cookie
    setcookie('session_token', '', time() - 42000, '/', '', false, true);
    error_log("DEBUG: session_token cookie deleted");
    
    // Destroy the session
    session_destroy();
    error_log("DEBUG: session destroyed");
}

/**
 * Check session validity
 * 
 * @return void
 */
function checkSession() {
    // Check if user is logged in
    if (!isLoggedIn()) {
        return;
    }
    
    // Check session timeout
    $timeout = SESSION_LIFETIME;
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
        logout();
        setFlashMessage('Your session has expired. Please log in again.', 'warning');
        redirect('/basis-data/login');
    }
    
    // Update last activity time
    $_SESSION['last_activity'] = time();
    
    // Check session token validity
    if (!isset($_COOKIE['session_token'])) {
        logout();
        setFlashMessage('Session invalid. Please log in again.', 'warning');
        redirect('/basis-data/login');
    }
    
    $sql = "SELECT * FROM sessions WHERE id_user = ? AND session_token = ? AND expires_at > NOW()";
    $session = dbQuerySingle($sql, [$_SESSION['user_id'], $_COOKIE['session_token']]);
    
    if (!$session) {
        logout();
        setFlashMessage('Session invalid. Please log in again.', 'warning');
        redirect('/basis-data/login');
    }
}

/**
 * Require authentication
 * 
 * @param string|array $roles Required role(s)
 * @return void
 */
function requireAuth($roles = null) {
    // Check session validity
    checkSession();
    
    // Check if user is logged in
    if (!isLoggedIn()) {
        setFlashMessage('Please log in to access this page.', 'warning');
        redirect('/basis-data/login?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    }
    
    // Check role if specified
    if ($roles !== null && !hasRole($roles)) {
        setFlashMessage('You do not have permission to access this page.', 'error');
        redirect('/');
    }
}

/**
 * Redirect if already authenticated
 * 
 * @param string $destination Destination URL
 * @return void
 */
function redirectIfAuthenticated($destination = '/') {
    if (isLoggedIn()) {
        redirect($destination);
    }
}
?>