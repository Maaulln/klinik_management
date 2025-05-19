<?php
// Admin controller
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Require admin authentication
requireAuth(['admin']);

// Handle admin routes
$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

switch ($action) {
    case 'dashboard':
        showDashboard();
        break;
    case 'patients':
        handlePatients();
        break;
    case 'doctors':
        handleDoctors();
        break;
    case 'reports':
        handleReports();
        break;
    case 'settings':
        handleSettings();
        break;
    default:
        http_response_code(404);
        require_once __DIR__ . '/../views/404.php';
        break;
}

/**
 * Show admin dashboard
 */
function showDashboard() {
    $pageTitle = 'Admin Dashboard';
    
    // Get dashboard statistics
    $patientCount = dbQuerySingle("SELECT COUNT(*) as count FROM pasien")['count'];
    $doctorCount = dbQuerySingle("SELECT COUNT(*) as count FROM dokter")['count'];
    $appointmentCount = dbQuerySingle("SELECT COUNT(*) as count FROM registrasi")['count'];
    $transactionCount = dbQuerySingle("SELECT COUNT(*) as count FROM transaksi")['count'];
    
    // Recent patients
    $recentPatients = dbQuery("
        SELECT p.*, r.waktu_registrasi 
        FROM pasien p
        LEFT JOIN registrasi r ON p.id_pasien = r.id_pasien
        ORDER BY r.waktu_registrasi DESC
        LIMIT 5
    ");
    
    // Recent transactions
    $recentTransactions = dbQuery("
        SELECT t.*, p.nama_pasien, k.nama_kasir
        FROM transaksi t
        JOIN pasien p ON t.id_pasien = p.id_pasien
        JOIN petugas_kasir k ON t.id_kasir = k.id_kasir
        ORDER BY t.waktu_transaksi DESC
        LIMIT 5
    ");
    
    require_once __DIR__ . '/../views/admin/dashboard.php';
}

/**
 * Handle patients management
 */
function handlePatients() {
    $subAction = isset($_GET['sub_action']) ? $_GET['sub_action'] : 'list';
    
    switch ($subAction) {
        case 'list':
            listPatients();
            break;
        case 'add':
            addPatient();
            break;
        case 'edit':
            editPatient();
            break;
        case 'delete':
            deletePatient();
            break;
        case 'view':
            viewPatient();
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . '/../views/404.php';
            break;
    }
}

/**
 * List patients
 */
function listPatients() {
    $pageTitle = 'Patients';
    
    // Pagination
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = 10;
    $offset = ($page - 1) * $perPage;
    
    // Search
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $searchWhere = '';
    $searchParams = [];
    
    if (!empty($search)) {
        $searchWhere = " WHERE nama_pasien ILIKE ? OR alamat ILIKE ?";
        $searchParams = ["%$search%", "%$search%"];
    }
    
    // Get total count
    $countSql = "SELECT COUNT(*) as count FROM pasien" . $searchWhere;
    $totalCount = dbQuerySingle($countSql, $searchParams)['count'];
    $totalPages = ceil($totalCount / $perPage);
    
    // Get patients
    $sql = "SELECT * FROM pasien" . $searchWhere . " ORDER BY id_pasien DESC LIMIT ? OFFSET ?";
    $params = array_merge($searchParams, [$perPage, $offset]);
    $patients = dbQuery($sql, $params);
    
    require_once __DIR__ . '/../views/admin/patients/list.php';
}

/**
 * Add patient
 */
function addPatient() {
    $pageTitle = 'Add Patient';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlashMessage('Invalid form submission. Please try again.', 'error');
            redirect('/basis-data/admin?action=patients&sub_action=add');
        }
        
        // Validate input
        $name = trim($_POST['name'] ?? '');
        $address = trim($_POST['address'] ?? '');
        
        if (empty($name)) {
            setFlashMessage('Patient name is required.', 'error');
            redirect('/basis-data/admin?action=patients&sub_action=add');
        }
        
        // Insert patient
        $sql = "INSERT INTO pasien (nama_pasien, alamat) VALUES (?, ?)";
        $result = dbExecute($sql, [$name, $address]);
        
        if ($result) {
            setFlashMessage('Patient added successfully.', 'success');
            redirect('/basis-data/admin?action=patients');
        } else {
            setFlashMessage('Failed to add patient.', 'error');
            redirect('/basis-data/admin?action=patients&sub_action=add');
        }
    }
    
    require_once __DIR__ . '/../views/admin/patients/add.php';
}

/**
 * Edit patient
 */
function editPatient() {
    $pageTitle = 'Edit Patient';
    
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($id <= 0) {
        setFlashMessage('Invalid patient ID.', 'error');
        redirect('/basis-data/admin?action=patients');
    }
    
    // Get patient
    $patient = dbQuerySingle("SELECT * FROM pasien WHERE id_pasien = ?", [$id]);
    
    if (!$patient) {
        setFlashMessage('Patient not found.', 'error');
        redirect('/basis-data/admin?action=patients');
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlashMessage('Invalid form submission. Please try again.', 'error');
            redirect('/basis-data/admin?action=patients&sub_action=edit&id=' . $id);
        }
        
        // Validate input
        $name = trim($_POST['name'] ?? '');
        $address = trim($_POST['address'] ?? '');
        
        if (empty($name)) {
            setFlashMessage('Patient name is required.', 'error');
            redirect('/basis-data/admin?action=patients&sub_action=edit&id=' . $id);
        }
        
        // Update patient
        $sql = "UPDATE pasien SET nama_pasien = ?, alamat = ? WHERE id_pasien = ?";
        $result = dbExecute($sql, [$name, $address, $id]);
        
        if ($result) {
            setFlashMessage('Patient updated successfully.', 'success');
            redirect('/basis-data/admin?action=patients');
        } else {
            setFlashMessage('Failed to update patient.', 'error');
            redirect('/basis-data/admin?action=patients&sub_action=edit&id=' . $id);
        }
    }
    
    require_once __DIR__ . '/../views/admin/patients/edit.php';
}

/**
 * Delete patient
 */
function deletePatient() {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($id <= 0) {
        setFlashMessage('Invalid patient ID.', 'error');
        redirect('/basis-data/admin?action=patients');
    }
    
    // Check if patient exists
    $patient = dbQuerySingle("SELECT * FROM pasien WHERE id_pasien = ?", [$id]);
    
    if (!$patient) {
        setFlashMessage('Patient not found.', 'error');
        redirect('/basis-data/admin?action=patients');
    }
    
    // Check if patient has related records
    $hasMedicalRecords = dbQuerySingle("SELECT COUNT(*) as count FROM catatan_medik WHERE id_pasien = ?", [$id])['count'] > 0;
    $hasRegistrations = dbQuerySingle("SELECT COUNT(*) as count FROM registrasi WHERE id_pasien = ?", [$id])['count'] > 0;
    $hasTransactions = dbQuerySingle("SELECT COUNT(*) as count FROM transaksi WHERE id_pasien = ?", [$id])['count'] > 0;
    
    if ($hasMedicalRecords || $hasRegistrations || $hasTransactions) {
        setFlashMessage('Cannot delete patient with related records.', 'error');
        redirect('/basis-data/admin?action=patients');
    }
    
    // Delete patient
    $sql = "DELETE FROM pasien WHERE id_pasien = ?";
    $result = dbExecute($sql, [$id]);
    
    if ($result) {
        setFlashMessage('Patient deleted successfully.', 'success');
    } else {
        setFlashMessage('Failed to delete patient.', 'error');
    }
    
    redirect('/basis-data/admin?action=patients');
}

/**
 * View patient details
 */
function viewPatient() {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($id <= 0) {
        setFlashMessage('Invalid patient ID.', 'error');
        redirect('/basis-data/admin?action=patients');
    }
    
    // Get patient
    $patient = dbQuerySingle("SELECT * FROM pasien WHERE id_pasien = ?", [$id]);
    
    if (!$patient) {
        setFlashMessage('Patient not found.', 'error');
        redirect('/basis-data/admin?action=patients');
    }
    
    $pageTitle = 'Patient Details: ' . $patient['nama_pasien'];
    
    // Get medical records
    $medicalRecords = dbQuery("
        SELECT cm.*, d.nama_dokter
        FROM catatan_medik cm
        JOIN dokter d ON cm.id_dokter = d.id_dokter
        WHERE cm.id_pasien = ?
        ORDER BY cm.tanggal_catatan DESC
    ", [$id]);
    
    // Get registrations
    $registrations = dbQuery("
        SELECT *
        FROM registrasi
        WHERE id_pasien = ?
        ORDER BY waktu_registrasi DESC
    ", [$id]);
    
    // Get transactions
    $transactions = dbQuery("
        SELECT t.*, k.nama_kasir
        FROM transaksi t
        JOIN petugas_kasir k ON t.id_kasir = k.id_kasir
        WHERE t.id_pasien = ?
        ORDER BY t.waktu_transaksi DESC
    ", [$id]);
    
    require_once __DIR__ . '/../views/admin/patients/view.php';
}

/**
 * Handle doctors management
 */
function handleDoctors() {
    $subAction = isset($_GET['sub_action']) ? $_GET['sub_action'] : 'list';
    
    switch ($subAction) {
        case 'list':
            listDoctors();
            break;
        case 'add':
            addDoctor();
            break;
        case 'edit':
            editDoctor();
            break;
        case 'delete':
            deleteDoctor();
            break;
        case 'view':
            viewDoctor();
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . '/../views/404.php';
            break;
    }
}

/**
 * List doctors
 */
function listDoctors() {
    $pageTitle = 'Doctors';
    
    // Pagination
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = 10;
    $offset = ($page - 1) * $perPage;
    
    // Search
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $searchWhere = '';
    $searchParams = [];
    
    if (!empty($search)) {
        $searchWhere = " WHERE nama_dokter ILIKE ?";
        $searchParams = ["%$search%"];
    }
    
    // Get total count
    $countSql = "SELECT COUNT(*) as count FROM dokter" . $searchWhere;
    $totalCount = dbQuerySingle($countSql, $searchParams)['count'];
    $totalPages = ceil($totalCount / $perPage);
    
    // Get doctors
    $sql = "SELECT * FROM dokter" . $searchWhere . " ORDER BY id_dokter DESC LIMIT ? OFFSET ?";
    $params = array_merge($searchParams, [$perPage, $offset]);
    $doctors = dbQuery($sql, $params);
    
    require_once __DIR__ . '/../views/admin/doctors/list.php';
}

/**
 * Handle reports
 */
function handleReports() {
    $subAction = isset($_GET['sub_action']) ? $_GET['sub_action'] : 'financial';
    
    switch ($subAction) {
        case 'financial':
            financialReport();
            break;
        case 'patient':
            patientReport();
            break;
        case 'doctor':
            doctorReport();
            break;
        case 'medication':
            medicationReport();
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . '/../views/404.php';
            break;
    }
}

/**
 * Financial report
 */
function financialReport() {
    $pageTitle = 'Financial Report';
    
    // Get date range
    $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
    $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
    
    // Get transactions
    $transactions = dbQuery("
        SELECT t.*, p.nama_pasien, k.nama_kasir
        FROM transaksi t
        JOIN pasien p ON t.id_pasien = p.id_pasien
        JOIN petugas_kasir k ON t.id_kasir = k.id_kasir
        WHERE DATE(t.waktu_transaksi) BETWEEN ? AND ?
        ORDER BY t.waktu_transaksi DESC
    ", [$startDate, $endDate]);
    
    // Calculate totals
    $total = 0;
    foreach ($transactions as $transaction) {
        $total += $transaction['harga'];
    }
    
    require_once __DIR__ . '/../views/admin/reports/financial.php';
}

/**
 * Handle settings
 */
function handleSettings() {
    $pageTitle = 'Settings';
    require_once __DIR__ . '/../views/admin/settings.php';
}
?>