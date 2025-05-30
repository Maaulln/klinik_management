<?php
// Fungsi-fungsi autentikasi
require_once __DIR__ . "/../config/database.php";

/**
 * Mengecek apakah user sudah login
 * 
 * @return bool True jika sudah login
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Mengambil data user yang sedang login
 * 
 * @return array|null Data user atau null jika belum login
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    // Query untuk mengambil data user berdasarkan id_user dari session
    $sql = "SELECT * FROM users WHERE id_user = ?";
    return dbQuerySingle($sql, [$_SESSION['user_id']]);
}

/**
 * Mengecek apakah user memiliki role tertentu
 * 
 * @param string|array $roles Role yang dicek
 * @return bool True jika user memiliki role tersebut
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
 * Autentikasi user berdasarkan username dan password
 * 
 * @param string $username Username
 * @param string $password Password
 * @return bool True jika autentikasi berhasil
 */
function authenticate($username, $password) {
    // Query untuk mengambil user aktif berdasarkan username
    $sql = "SELECT * FROM users WHERE username = ? AND is_active = TRUE";
    $user = dbQuerySingle($sql, [$username]);
    
    if (!$user) {
        return false;
    }
    
    // Bandingkan password secara plain text (tidak aman, hanya contoh)
    if ($password === $user['password']) {
        // Set session user
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['last_activity'] = time();
        
        // Membuat record session baru di tabel sessions
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + SESSION_LIFETIME);
        $sql = "INSERT INTO sessions (id_user, session_token, expires_at) VALUES (?, ?, ?)";
        dbExecute($sql, [$user['id_user'], $token, $expires]);
        
        // Set cookie session_token
        setcookie('session_token', $token, time() + SESSION_LIFETIME, '/', '', false, true);
        
        return true;
    }
    return false;
}

/**
 * Registrasi user baru
 * 
 * @param array $userData Data user baru
 * @return int|bool ID user baru atau false jika gagal
 */
function registerUser($userData) {
    // Query untuk cek apakah username atau email sudah ada
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $existingUser = dbQuerySingle($sql, [$userData['username'], $userData['email']]);
    
    if ($existingUser) {
        return false;
    }
    
    // Password disimpan secara plain text (tidak direkomendasikan)
    $plainPassword = $userData['password'];
    
    // Mulai transaksi database
    dbBeginTransaction();
    try {
        // Insert ke tabel sesuai role (misal: pasien, dokter, kasir)
        $referenceId = null;
        if ($userData['role'] === 'patient') {
            // Query untuk insert ke tabel pasien
            $sql = "INSERT INTO pasien (nama_pasien, alamat) VALUES (?, ?) RETURNING id_pasien";
            $result = dbQuerySingle($sql, [$userData['name'], $userData['address'] ?? '']);
            $referenceId = $result['id_pasien'];
        } elseif ($userData['role'] === 'doctor') {
            // Query untuk insert ke tabel dokter
            $sql = "INSERT INTO dokter (nama_dokter) VALUES (?) RETURNING id_dokter";
            $result = dbQuerySingle($sql, [$userData['name']]);
            $referenceId = $result['id_dokter'];
        } elseif ($userData['role'] === 'cashier') {
            // Query untuk insert ke tabel petugas kasir
            $sql = "INSERT INTO petugas_kasir (nama_kasir) VALUES (?) RETURNING id_kasir";
            $result = dbQuerySingle($sql, [$userData['name']]);
            $referenceId = $result['id_kasir'];
        }
        
        // Query untuk insert ke tabel users
        $sql = "INSERT INTO users (username, password, email, role, id_reference) VALUES (?, ?, ?, ?, ?) RETURNING id_user";
        $result = dbQuerySingle($sql, [
            $userData['username'],
            $plainPassword,
            $userData['email'],
            $userData['role'],
            $referenceId
        ]);
        
        // Commit transaksi database
        dbCommit();
        return $result['id_user'];
    } catch (Exception $e) {
        // Rollback jika gagal
        dbRollback();
        if (DEBUG_MODE) {
            die("Registrasi gagal: " . $e->getMessage());
        } else {
            return false;
        }
    }
}

/**
 * Logout user dari sistem
 * 
 * @return void
 */
function logout() {
    // Hapus record session di database
    if (isset($_SESSION['user_id']) && isset($_COOKIE['session_token'])) {
        $sql = "DELETE FROM sessions WHERE id_user = ? AND session_token = ?";
        dbExecute($sql, [$_SESSION['user_id'], $_COOKIE['session_token']]);
    }
    // Hapus semua variabel session
    $_SESSION = [];
    // Hapus cookie session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            '/basis-data/',
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    // Hapus cookie session_token
    setcookie('session_token', '', time() - 42000, '/', '', false, true);
    // Destroy session
    session_destroy();
}

/**
 * Mengecek validitas session user
 * 
 * @return void
 */
function checkSession() {
    // Cek apakah user sudah login
    if (!isLoggedIn()) {
        return;
    }
    // Cek timeout session
    $timeout = SESSION_LIFETIME;
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
        logout();
        setFlashMessage('Sesi Anda telah berakhir. Silakan login kembali.', 'warning');
        redirect('/basis-data/login');
    }
    // Update waktu aktivitas terakhir
    $_SESSION['last_activity'] = time();
    // Cek validitas session_token
    if (!isset($_COOKIE['session_token'])) {
        logout();
        setFlashMessage('Session tidak valid. Silakan login kembali.', 'warning');
        redirect('/basis-data/login');
    }
    // Query untuk cek session aktif di tabel sessions
    $sql = "SELECT * FROM sessions WHERE id_user = ? AND session_token = ? AND expires_at > NOW()";
    $session = dbQuerySingle($sql, [$_SESSION['user_id'], $_COOKIE['session_token']]);
    if (!$session) {
        logout();
        setFlashMessage('Session tidak valid. Silakan login kembali.', 'warning');
        redirect('/basis-data/login');
    }
}

/**
 * Wajib login untuk mengakses halaman tertentu
 * 
 * @param string|array $roles Role yang diizinkan
 * @return void
 */
function requireAuth($roles = null) {
    // Cek validitas session
    checkSession();
    // Jika belum login, redirect ke login
    if (!isLoggedIn()) {
        setFlashMessage('Silakan login untuk mengakses halaman ini.', 'warning');
        redirect('/basis-data/login?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    }
    // Jika role tidak sesuai, redirect ke halaman utama
    if ($roles !== null && !hasRole($roles)) {
        setFlashMessage('Anda tidak memiliki izin untuk mengakses halaman ini.', 'error');
        redirect('/');
    }
}

/**
 * Redirect jika sudah login
 * 
 * @param string $destination URL tujuan
 * @return void
 */
function redirectIfAuthenticated($destination = '/') {
    if (isLoggedIn()) {
        redirect($destination);
    }
}
?>