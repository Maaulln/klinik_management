<?php
// Controller pasien
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Wajib login sebagai pasien
requireAuth(['patient']);

// Ambil data user dan pasien yang sedang login
$user = getCurrentUser();
$patient = dbQuerySingle("
    SELECT p.* 
    FROM pasien p
    JOIN users u ON p.id_pasien = u.id_reference
    WHERE u.id_user = ? AND u.role = 'patient'
", [$user['id_user']]);

if (!$patient) {
    setFlashMessage('Data pasien tidak ditemukan.', 'error');
    logout();
    redirect('/basis-data/login');
}

// Routing utama pasien
$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

switch ($action) {
    case 'dashboard':
        showDashboard();
        break;
    case 'appointments':
        handleAppointments();
        break;
    case 'medical-records':
        handleMedicalRecords();
        break;
    case 'billing':
        handleBilling();
        break;
    case 'profile':
        handleProfile();
        break;
    case 'messages':
        handleMessages();
        break;
    default:
        http_response_code(404);
        require_once 'views/404.php';
        break;
}

/**
 * Menampilkan dashboard pasien
 */
function showDashboard() {
    global $patient;
    $pageTitle = 'Dashboard Pasien';
    
    // Query untuk mengambil 5 appointment mendatang milik pasien
    $upcomingAppointments = dbQuery("
       SELECT a.*, d.nama_dokter
        FROM appointments a
        JOIN dokter d ON a.id_dokter = d.id_dokter
        WHERE a.id_pasien = ? 
          AND (a.tanggal_janji > CURRENT_DATE OR (a.tanggal_janji = CURRENT_DATE AND a.waktu_janji > CURRENT_TIME))
          AND a.status IN ('scheduled', 'pending', 'rescheduled')
        ORDER BY a.tanggal_janji ASC, a.waktu_janji ASC
        LIMIT 5
    ", [$patient['id_pasien']]);

    // Query untuk mengambil 5 appointment masa lalu milik pasien
    $pastAppointments = dbQuery("
        SELECT a.*, d.nama_dokter
        FROM appointments a
        JOIN dokter d ON a.id_dokter = d.id_dokter
        WHERE a.id_pasien = ?
          AND (a.tanggal_janji < CURRENT_DATE OR (a.tanggal_janji = CURRENT_DATE AND a.waktu_janji <= CURRENT_TIME))
          AND a.status IN ('completed', 'cancelled')
        ORDER BY a.tanggal_janji DESC, a.waktu_janji DESC
        LIMIT 5
    ", [$patient['id_pasien']]);
    
    // Query untuk mengambil 5 catatan medis terbaru pasien
    $recentMedicalRecords = dbQuery("
        SELECT cm.*, d.nama_dokter
        FROM catatan_medik cm
        JOIN dokter d ON cm.id_dokter = d.id_dokter
        WHERE cm.id_pasien = ?
        ORDER BY cm.tanggal_catatan DESC
        LIMIT 5
    ", [$patient['id_pasien']]);
    
    // Query untuk mengambil 5 transaksi terakhir pasien
    $recentTransactions = dbQuery("
        SELECT t.*, k.nama_kasir
        FROM transaksi t
        JOIN petugas_kasir k ON t.id_kasir = k.id_kasir
        WHERE t.id_pasien = ?
        ORDER BY t.waktu_transaksi DESC
        LIMIT 5
    ", [$patient['id_pasien']]);
    
    require_once __DIR__ . '/../views/patient/dashboard.php';
}

/**
 * Handler fitur appointment pasien
 */
function handleAppointments() {
    global $patient;
    $subAction = isset($_GET['sub_action']) ? $_GET['sub_action'] : 'list';
    
    switch ($subAction) {
        case 'list':
            listAppointments();
            break;
        case 'request':
            requestAppointment();
            break;
        case 'view':
            viewAppointment();
            break;
        case 'cancel':
            cancelAppointment();
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . '/../views/404.php';
            break;
    }
}

/**
 * Menampilkan daftar appointment pasien
 */
function listAppointments() {
    global $patient;
    $pageTitle = 'Janji Temu Saya';
    
    // Query untuk mengambil semua appointment milik pasien
    $appointments = dbQuery("
        SELECT a.*, d.nama_dokter
        FROM appointments a
        JOIN dokter d ON a.id_dokter = d.id_dokter
        WHERE a.id_pasien = ?
        ORDER BY a.tanggal_janji DESC
    ", [$patient['id_pasien']]);

    $upcomingAppointments = [];
    $pastAppointments = [];
    $now = date('Y-m-d H:i:s');

    foreach ($appointments as $appointment) {
        $appointmentDateTime = $appointment['tanggal_janji'] . ' ' . $appointment['waktu_janji'];
        if (($appointment['status'] === 'scheduled' || $appointment['status'] === 'pending' || $appointment['status'] === 'rescheduled') &&
            strtotime($appointmentDateTime) > strtotime($now)) {
            $upcomingAppointments[] = $appointment;
        } elseif ($appointment['status'] !== 'scheduled') {
            $pastAppointments[] = $appointment;
        }
    }

    // Pass these arrays to the view
    require_once __DIR__ . '/../views/patient/appointments/list.php';
    
    require_once __DIR__ . '/../views/patient/appointments/list.php';
}

/**
 * Proses request appointment baru oleh pasien
 */
function requestAppointment() {
    global $patient;
    $pageTitle = 'Request Janji Temu';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validasi CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlashMessage('Form tidak valid. Silakan coba lagi.', 'error');
            redirect('/basis-data/patient?action=appointments&sub_action=request');
        }

        // Validasi input form
        $appointmentDate = $_POST['appointment_date'] ?? '';
        $appointmentTime = $_POST['appointment_time'] ?? '';
        $id_dokter = $_POST['id_dokter'] ?? '';

        if (empty($appointmentDate) || empty($appointmentTime) || empty($id_dokter)) {
            setFlashMessage('Silakan pilih dokter, tanggal, dan waktu janji temu.', 'error');
            redirect('/basis-data/patient?action=appointments&sub_action=request');
        }

        // Validasi format tanggal dan waktu
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $appointmentDate)) {
            setFlashMessage('Format tanggal tidak valid. Gunakan format YYYY-MM-DD.', 'error');
            redirect('/basis-data/patient?action=appointments&sub_action=request');
        }
        if (!preg_match('/^\d{2}:\d{2}$/', $appointmentTime)) {
            setFlashMessage('Format waktu tidak valid. Gunakan format HH:MM.', 'error');
            redirect('/basis-data/patient?action=appointments&sub_action=request');
        }

        // Gabungkan tanggal dan waktu
        $tanggal_janji = $appointmentDate;
        $waktu_janji = $appointmentTime . ':00';

        // Cek apakah appointment di masa depan
        $appointmentTimestamp = strtotime($tanggal_janji . ' ' . $waktu_janji);
        if ($appointmentTimestamp <= time()) {
            setFlashMessage('Janji temu harus diatur untuk waktu yang akan datang.', 'error');
            redirect('/basis-data/patient?action=appointments&sub_action=request');
        }

        // Query untuk insert appointment baru ke tabel appointments
        $sql = "INSERT INTO appointments (id_pasien, id_dokter, tanggal_janji, waktu_janji, status) VALUES (?, ?, ?, ?, ?)";
        $result = dbExecute($sql, [
            $patient['id_pasien'],
            $id_dokter,
            $tanggal_janji,
            $waktu_janji,
            'scheduled'
        ]);

        if ($result) {
            setFlashMessage('Janji temu berhasil diajukan.', 'success');
            redirect('/basis-data/patient?action=appointments');
        } else {
            setFlashMessage('Gagal mengajukan janji temu.', 'error');
            redirect('/basis-data/patient?action=appointments&sub_action=request');
        }
    }

    // Query untuk mengambil daftar dokter untuk form
    $doctors = dbQuery("SELECT * FROM dokter ORDER BY nama_dokter ASC");
    require_once __DIR__ . '/../views/patient/appointments/request.php';
}

/**
 * Menampilkan detail appointment pasien
 */
function viewAppointment() {
    global $patient;
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($id <= 0) {
        setFlashMessage('ID appointment tidak valid.', 'error');
        redirect('/basis-data/patient?action=appointments');
    }

    // Query untuk mengambil data appointment berdasarkan id dan id_pasien
    $appointment = dbQuerySingle("
        SELECT a.*, d.nama_dokter
        FROM appointments a
        JOIN dokter d ON a.id_dokter = d.id_dokter
        WHERE a.id_appointment = ? AND a.id_pasien = ?
    ", [$id, $patient['id_pasien']]);

    if (!$appointment) {
        setFlashMessage('Appointment tidak ditemukan.', 'error');
        redirect('/basis-data/patient?action=appointments');
    }

    $pageTitle = 'Detail Janji Temu';
    require_once __DIR__ . '/../views/patient/appointments/view.php';
}

/**
 * Membatalkan appointment (menghapus data appointment)
 */
function cancelAppointment() {
    global $patient;
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($id <= 0) {
        setFlashMessage('ID appointment tidak valid.', 'error');
        redirect('/basis-data/patient?action=appointments');
    }

    // Query untuk menghapus appointment berdasarkan id dan id_pasien
    $result = dbExecute(
        "DELETE FROM appointments WHERE id_appointment = ? AND id_pasien = ?",
        [$id, $patient['id_pasien']]
    );

    if ($result) {
        setFlashMessage('Appointment berhasil dibatalkan.', 'success');
    } else {
        setFlashMessage('Gagal membatalkan appointment.', 'error');
    }
    redirect('/basis-data/patient?action=appointments');
}

/**
 * Handler fitur catatan medis pasien
 */
function handleMedicalRecords() {
    global $patient;
    $subAction = isset($_GET['sub_action']) ? $_GET['sub_action'] : 'list';
    
    switch ($subAction) {
        case 'list':
            listMedicalRecords();
            break;
        case 'view':
            viewMedicalRecord();
            break;
        default:
            http_response_code(404);
            require_once 'views/404.php';
            break;
    }
}

/**
 * Menampilkan daftar catatan medis pasien
 */
function listMedicalRecords() {
    global $patient;
    $pageTitle = 'Catatan Medis Saya';
    
    // Query untuk mengambil semua catatan medis pasien
    $medicalRecords = dbQuery("
        SELECT cm.*, d.nama_dokter
        FROM catatan_medik cm
        JOIN dokter d ON cm.id_dokter = d.id_dokter
        WHERE cm.id_pasien = ?
        ORDER BY cm.tanggal_catatan DESC
    ", [$patient['id_pasien']]);
    
    require_once __DIR__ . '/../views/patient/medical-records/list.php';
}

/**
 * Menampilkan detail catatan medis pasien
 */
function viewMedicalRecord() {
    global $patient;
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($id <= 0) {
        setFlashMessage('ID catatan medis tidak valid.', 'error');
        redirect('/patient?action=medical-records');
    }
    
    // Query untuk mengambil data catatan medis berdasarkan id dan id_pasien
    $record = dbQuerySingle("
        SELECT cm.*, d.nama_dokter
        FROM catatan_medik cm
        JOIN dokter d ON cm.id_dokter = d.id_dokter
        WHERE cm.id_catatan = ? AND cm.id_pasien = ?
    ", [$id, $patient['id_pasien']]);
    
    if (!$record) {
        setFlashMessage('Catatan medis tidak ditemukan atau Anda tidak memiliki akses.', 'error');
        redirect('/patient?action=medical-records');
    }
    
    $pageTitle = 'Catatan Medis: ' . formatDate($record['tanggal_catatan']);
    
    // Query untuk mengambil resep obat pada catatan medis ini
    $prescriptions = dbQuery("
        SELECT ro.*, rod.jumlah, rod.aturan_pakai, o.nama_obat, o.harga_obat
        FROM resep_obat ro
        JOIN resep_obat_detail rod ON ro.id_resep = rod.id_resep
        JOIN obat o ON rod.id_obat = o.id_obat
        WHERE ro.id_catatan = ?
        ORDER BY ro.tanggal_resep DESC
    ", [$id]);
    
    require_once __DIR__ . '/../views/patient/medical-records/view.php';
}

/**
 * Handler fitur billing pasien
 */
function handleBilling() {
    global $patient;
    $subAction = isset($_GET['sub_action']) ? $_GET['sub_action'] : 'list';
    
    switch ($subAction) {
        case 'list':
            listBilling();
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . '/../views/404.php';
            break;
    }
}

/**
 * Menampilkan daftar transaksi/billing pasien
 */
function listBilling() {
    global $patient;
    $pageTitle = 'Riwayat Pembayaran Saya';
    
    // Query untuk mengambil semua transaksi pasien
    $transactions = dbQuery("
        SELECT t.*, k.nama_kasir
        FROM transaksi t
        JOIN petugas_kasir k ON t.id_kasir = k.id_kasir
        WHERE t.id_pasien = ?
        ORDER BY t.waktu_transaksi DESC
    ", [$patient['id_pasien']]);
    
    require_once __DIR__ . '/../views/patient/billing/list.php';
}

/**
 * Handler fitur profil pasien
 */
function handleProfile() {
    global $patient, $user;
    $pageTitle = 'Profil Saya';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validasi CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlashMessage('Form tidak valid. Silakan coba lagi.', 'error');
            redirect('/patient?action=profile');
        }
        
        // Validasi input
        $name = trim($_POST['name'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($name)) {
            setFlashMessage('Nama wajib diisi.', 'error');
            redirect('/patient?action=profile');
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            setFlashMessage('Email wajib diisi dan harus valid.', 'error');
            redirect('/patient?action=profile');
        }
        
        // Mulai transaksi database
        dbBeginTransaction();
        
        try {
            // Query untuk update data pasien
            $sqlPatient = "UPDATE pasien SET nama_pasien = ?, alamat = ? WHERE id_pasien = ?";
            dbExecute($sqlPatient, [$name, $address, $patient['id_pasien']]);
            
            // Jika email diubah, cek apakah sudah digunakan user lain
            if ($email !== $user['email']) {
                $existingUser = dbQuerySingle("SELECT * FROM users WHERE email = ? AND id_user != ?", [$email, $user['id_user']]);
                
                if ($existingUser) {
                    throw new Exception('Email sudah digunakan oleh akun lain.');
                }
                
                // Query untuk update email user
                $sqlEmail = "UPDATE users SET email = ? WHERE id_user = ?";
                dbExecute($sqlEmail, [$email, $user['id_user']]);
            }
            
            // Update password jika diisi
            if (!empty($currentPassword) && !empty($newPassword)) {
                // Verifikasi password lama
                if (!password_verify($currentPassword, $user['password'])) {
                    throw new Exception('Password lama salah.');
                }
                
                // Validasi password baru sama
                if ($newPassword !== $confirmPassword) {
                    throw new Exception('Password baru tidak sama.');
                }
                
                // Validasi panjang password baru
                if (strlen($newPassword) < 8) {
                    throw new Exception('Password baru minimal 8 karakter.');
                }
                
                // Query untuk update password user
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $sqlPassword = "UPDATE users SET password = ? WHERE id_user = ?";
                dbExecute($sqlPassword, [$hashedPassword, $user['id_user']]);
            }
            
            // Commit transaksi database
            dbCommit();
            
            setFlashMessage('Profil berhasil diupdate.', 'success');
            redirect('/patient?action=profile');
        } catch (Exception $e) {
            // Rollback jika gagal
            dbRollback();
            
            setFlashMessage($e->getMessage(), 'error');
            redirect('/patient?action=profile');
        }
    }
    
    require_once __DIR__ . '/../views/patient/profile.php';
}

/**
 * Handler fitur pesan pasien
 */
function handleMessages() {
    global $patient;
    $pageTitle = 'Pesan Saya';
    require_once __DIR__ . '/../views/patient/messages.php';
}
?>