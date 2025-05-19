<?php
// Patient controller
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Require patient authentication
requireAuth(['patient']);

// Get current user and patient info
$user = getCurrentUser();
$patient = dbQuerySingle("
    SELECT p.* 
    FROM pasien p
    JOIN users u ON p.id_pasien = u.id_reference
    WHERE u.id_user = ? AND u.role = 'patient'
", [$user['id_user']]);

if (!$patient) {
    setFlashMessage('Patient record not found.', 'error');
    logout();
    redirect('/basis-data/login');
}

// Handle patient routes
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
 * Show patient dashboard
 */
function showDashboard() {
    global $patient;
    $pageTitle = 'Patient Dashboard';
    
    // Get upcoming appointments
    $upcomingRegistrations = dbQuery("
        SELECT r.*
        FROM registrasi r
        WHERE r.id_pasien = ? AND r.waktu_registrasi > NOW()
        ORDER BY r.waktu_registrasi ASC
        LIMIT 5
    ", [$patient['id_pasien']]);
    
    // Get recent medical records
    $recentMedicalRecords = dbQuery("
        SELECT cm.*, d.nama_dokter
        FROM catatan_medik cm
        JOIN dokter d ON cm.id_dokter = d.id_dokter
        WHERE cm.id_pasien = ?
        ORDER BY cm.tanggal_catatan DESC
        LIMIT 5
    ", [$patient['id_pasien']]);
    
    // Get recent transactions
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
 * Handle appointments
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
 * List appointments
 */
function listAppointments() {
    global $patient;
    $pageTitle = 'My Appointments';
    
    // Get all appointments
    $registrations = dbQuery("
        SELECT r.*
        FROM registrasi r
        WHERE r.id_pasien = ?
        ORDER BY r.waktu_registrasi DESC
    ", [$patient['id_pasien']]);
    
    require_once __DIR__ . '/../views/patient/appointments/list.php';
}

/**
 * Request appointment
 */
function requestAppointment() {
    global $patient;
    $pageTitle = 'Request Appointment';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlashMessage('Invalid form submission. Please try again.', 'error');
            redirect('/patient?action=appointments&sub_action=request');
        }
        
        // Validate input
        $appointmentDate = $_POST['appointment_date'] ?? '';
        $appointmentTime = $_POST['appointment_time'] ?? '';
        
        if (empty($appointmentDate) || empty($appointmentTime)) {
            setFlashMessage('Please provide both date and time for your appointment.', 'error');
            redirect('/patient?action=appointments&sub_action=request');
        }
        
        // Validate date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $appointmentDate)) {
            setFlashMessage('Invalid date format. Please use YYYY-MM-DD format.', 'error');
            redirect('/patient?action=appointments&sub_action=request');
        }
        
        // Validate time format
        if (!preg_match('/^\d{2}:\d{2}$/', $appointmentTime)) {
            setFlashMessage('Invalid time format. Please use HH:MM format.', 'error');
            redirect('/patient?action=appointments&sub_action=request');
        }
        
        // Combine date and time
        $appointmentDateTime = $appointmentDate . ' ' . $appointmentTime . ':00';
        
        // Check if appointment is in the future
        $appointmentTimestamp = strtotime($appointmentDateTime);
        $currentTimestamp = time();
        
        if ($appointmentTimestamp <= $currentTimestamp) {
            setFlashMessage('Appointment must be scheduled for a future date and time.', 'error');
            redirect('/patient?action=appointments&sub_action=request');
        }
        
        // Insert appointment
        $sql = "INSERT INTO registrasi (waktu_registrasi, id_pasien) VALUES (?, ?)";
        $result = dbExecute($sql, [$appointmentDateTime, $patient['id_pasien']]);
        
        if ($result) {
            setFlashMessage('Appointment requested successfully.', 'success');
            redirect('/patient?action=appointments');
        } else {
            setFlashMessage('Failed to request appointment.', 'error');
            redirect('/patient?action=appointments&sub_action=request');
        }
    }
    
    require_once __DIR__ . '/../views/patient/appointments/request.php';
}

/**
 * Handle medical records
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
 * List medical records
 */
function listMedicalRecords() {
    global $patient;
    $pageTitle = 'My Medical Records';
    
    // Get all medical records
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
 * View medical record
 */
function viewMedicalRecord() {
    global $patient;
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($id <= 0) {
        setFlashMessage('Invalid medical record ID.', 'error');
        redirect('/patient?action=medical-records');
    }
    
    // Get medical record
    $record = dbQuerySingle("
        SELECT cm.*, d.nama_dokter
        FROM catatan_medik cm
        JOIN dokter d ON cm.id_dokter = d.id_dokter
        WHERE cm.id_catatan = ? AND cm.id_pasien = ?
    ", [$id, $patient['id_pasien']]);
    
    if (!$record) {
        setFlashMessage('Medical record not found or you do not have permission to view it.', 'error');
        redirect('/patient?action=medical-records');
    }
    
    $pageTitle = 'Medical Record: ' . formatDate($record['tanggal_catatan']);
    
    // Get prescriptions
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
 * Handle billing
 */
function handleBilling() {
    global $patient;
    $subAction = isset($_GET['sub_action']) ? $_GET['sub_action'] : 'list';
    
    switch ($subAction) {
        case 'list':
            listBilling();
            break;
        case 'view':
            viewBill();
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . '/../views/404.php';
            break;
    }
}

/**
 * List billing
 */
function listBilling() {
    global $patient;
    $pageTitle = 'My Billing History';
    
    // Get all transactions
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
 * Handle profile
 */
function handleProfile() {
    global $patient, $user;
    $pageTitle = 'My Profile';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlashMessage('Invalid form submission. Please try again.', 'error');
            redirect('/patient?action=profile');
        }
        
        // Validate input
        $name = trim($_POST['name'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($name)) {
            setFlashMessage('Name is required.', 'error');
            redirect('/patient?action=profile');
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            setFlashMessage('Valid email is required.', 'error');
            redirect('/patient?action=profile');
        }
        
        // Begin transaction
        dbBeginTransaction();
        
        try {
            // Update patient
            $sqlPatient = "UPDATE pasien SET nama_pasien = ?, alamat = ? WHERE id_pasien = ?";
            dbExecute($sqlPatient, [$name, $address, $patient['id_pasien']]);
            
            // Check if email is changed
            if ($email !== $user['email']) {
                // Check if email is already in use
                $existingUser = dbQuerySingle("SELECT * FROM users WHERE email = ? AND id_user != ?", [$email, $user['id_user']]);
                
                if ($existingUser) {
                    throw new Exception('Email is already in use by another account.');
                }
                
                // Update email
                $sqlEmail = "UPDATE users SET email = ? WHERE id_user = ?";
                dbExecute($sqlEmail, [$email, $user['id_user']]);
            }
            
            // Update password if provided
            if (!empty($currentPassword) && !empty($newPassword)) {
                // Verify current password
                if (!password_verify($currentPassword, $user['password'])) {
                    throw new Exception('Current password is incorrect.');
                }
                
                // Validate password match
                if ($newPassword !== $confirmPassword) {
                    throw new Exception('New passwords do not match.');
                }
                
                // Validate password strength
                if (strlen($newPassword) < 8) {
                    throw new Exception('New password must be at least 8 characters long.');
                }
                
                // Update password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $sqlPassword = "UPDATE users SET password = ? WHERE id_user = ?";
                dbExecute($sqlPassword, [$hashedPassword, $user['id_user']]);
            }
            
            // Commit transaction
            dbCommit();
            
            setFlashMessage('Profile updated successfully.', 'success');
            redirect('/patient?action=profile');
        } catch (Exception $e) {
            // Rollback transaction
            dbRollback();
            
            setFlashMessage($e->getMessage(), 'error');
            redirect('/patient?action=profile');
        }
    }
    
    require_once __DIR__ . '/../views/patient/profile.php';
}

/**
 * Handle messages
 */
function handleMessages() {
    global $patient;
    $pageTitle = 'My Messages';
    require_once __DIR__ . '/../views/patient/messages.php';
}
?>