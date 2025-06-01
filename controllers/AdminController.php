<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Wajib login sebagai admin
requireAuth(['admin']);

// Routing utama admin
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
    case 'appointments':
        handleAppointments();
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

// Tampilkan dashboard admin
function showDashboard() {
    $pageTitle = 'Admin Dashboard';
    
    // Menghitung jumlah pasien
    $patientCount = dbQuerySingle("SELECT COUNT(*) as count FROM pasien")['count'];
    // Menghitung jumlah dokter
    $doctorCount = dbQuerySingle("SELECT COUNT(*) as count FROM dokter")['count'];
    // Menghitung jumlah appointment
    $appointmentCount = dbQuerySingle("SELECT COUNT(*) as count FROM appointments")['count'];
    // Menghitung jumlah transaksi
    $transactionCount = dbQuerySingle("SELECT COUNT(*) as count FROM transaksi")['count'];
    
    // Mengambil 5 pasien terbaru
    $recentPatients = dbQuery("
        SELECT p.*, r.waktu_registrasi 
        FROM pasien p
        LEFT JOIN registrasi r ON p.id_pasien = r.id_pasien
        ORDER BY r.waktu_registrasi DESC
        LIMIT 5
    ");
    
    // Mengambil 5 transaksi terbaru
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

// Handler manajemen pasien
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

// Menampilkan daftar pasien
function listPatients() {
    $pageTitle = 'Patients';
    
    // Pagination
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = 10;
    $offset = ($page - 1) * $perPage;
    
    // Pencarian pasien
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $searchWhere = '';
    $searchParams = [];
    
    if (!empty($search)) {
        $searchWhere = " WHERE nama_pasien ILIKE ? OR alamat ILIKE ?";
        $searchParams = ["%$search%", "%$search%"];
    }
    
    // Menghitung total pasien
    $countSql = "SELECT COUNT(*) as count FROM pasien" . $searchWhere;
    $totalCount = dbQuerySingle($countSql, $searchParams)['count'];
    $totalPages = ceil($totalCount / $perPage);
    
    // Mengambil data pasien
    $sql = "SELECT * FROM pasien" . $searchWhere . " ORDER BY id_pasien DESC LIMIT ? OFFSET ?";
    $params = array_merge($searchParams, [$perPage, $offset]);
    $patients = dbQuery($sql, $params);
    
    require_once __DIR__ . '/../views/admin/patients/list.php';
}

// Menambah pasien baru
function addPatient() {
    $pageTitle = 'Add Patient';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validasi CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlashMessage('Form tidak valid. Silakan coba lagi.', 'error');
            redirect('/basis-data/admin?action=patients&sub_action=add');
        }
        
        // Validasi input
        $name = trim($_POST['name'] ?? '');
        $address = trim($_POST['address'] ?? '');
        
        if (empty($name)) {
            setFlashMessage('Nama pasien wajib diisi.', 'error');
            redirect('/basis-data/admin?action=patients&sub_action=add');
        }
        
        // Query insert pasien baru
        $sql = "INSERT INTO pasien (nama_pasien, alamat) VALUES (?, ?)";
        $result = dbExecute($sql, [$name, $address]);
        
        if ($result) {
            setFlashMessage('Pasien berhasil ditambahkan.', 'success');
            redirect('/basis-data/admin?action=patients');
        } else {
            setFlashMessage('Gagal menambah pasien.', 'error');
            redirect('/basis-data/admin?action=patients&sub_action=add');
        }
    }
    
    require_once __DIR__ . '/../views/admin/patients/add.php';
}

// Mengedit data pasien
function editPatient() {
    $pageTitle = 'Edit Patient';
    
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($id <= 0) {
        setFlashMessage('ID pasien tidak valid.', 'error');
        redirect('/basis-data/admin?action=patients');
    }
    
    // Mengambil data pasien
    $patient = dbQuerySingle("SELECT * FROM pasien WHERE id_pasien = ?", [$id]);
    
    if (!$patient) {
        setFlashMessage('Pasien tidak ditemukan.', 'error');
        redirect('/basis-data/admin?action=patients');
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validasi CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlashMessage('Form tidak valid. Silakan coba lagi.', 'error');
            redirect('/basis-data/admin?action=patients&sub_action=edit&id=' . $id);
        }
        
        // Validasi input
        $name = trim($_POST['name'] ?? '');
        $address = trim($_POST['address'] ?? '');
        
        if (empty($name)) {
            setFlashMessage('Nama pasien wajib diisi.', 'error');
            redirect('/basis-data/admin?action=patients&sub_action=edit&id=' . $id);
        }
        
        // Query update data pasien
        $sql = "UPDATE pasien SET nama_pasien = ?, alamat = ? WHERE id_pasien = ?";
        $result = dbExecute($sql, [$name, $address, $id]);
        
        if ($result) {
            setFlashMessage('Data pasien berhasil diupdate.', 'success');
            redirect('/basis-data/admin?action=patients');
        } else {
            setFlashMessage('Gagal update data pasien.', 'error');
            redirect('/basis-data/admin?action=patients&sub_action=edit&id=' . $id);
        }
    }
    
    require_once __DIR__ . '/../views/admin/patients/edit.php';
}

// Menghapus data pasien
function deletePatient() {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($id <= 0) {
        setFlashMessage('ID pasien tidak valid.', 'error');
        redirect('/basis-data/admin?action=patients');
    }
    
    // Cek apakah pasien ada
    $patient = dbQuerySingle("SELECT * FROM pasien WHERE id_pasien = ?", [$id]);
    
    if (!$patient) {
        setFlashMessage('Pasien tidak ditemukan.', 'error');
        redirect('/basis-data/admin?action=patients');
    }
    
    // Cek apakah pasien punya relasi data lain
    $hasMedicalRecords = dbQuerySingle("SELECT COUNT(*) as count FROM catatan_medik WHERE id_pasien = ?", [$id])['count'] > 0;
    $hasRegistrations = dbQuerySingle("SELECT COUNT(*) as count FROM registrasi WHERE id_pasien = ?", [$id])['count'] > 0;
    $hasTransactions = dbQuerySingle("SELECT COUNT(*) as count FROM transaksi WHERE id_pasien = ?", [$id])['count'] > 0;
    
    if ($hasMedicalRecords || $hasRegistrations || $hasTransactions) {
        setFlashMessage('Tidak bisa hapus pasien yang masih punya data terkait.', 'error');
        redirect('/basis-data/admin?action=patients');
    }
    
    // Query hapus pasien
    $sql = "DELETE FROM pasien WHERE id_pasien = ?";
    $result = dbExecute($sql, [$id]);
    
    if ($result) {
        setFlashMessage('Pasien berhasil dihapus.', 'success');
    } else {
        setFlashMessage('Gagal menghapus pasien.', 'error');
    }
    
    redirect('/basis-data/admin?action=patients');
}

// Menampilkan detail pasien
function viewPatient() {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($id <= 0) {
        setFlashMessage('ID pasien tidak valid.', 'error');
        redirect('/basis-data/admin?action=patients');
    }
    
    // Mengambil data pasien
    $patient = dbQuerySingle("SELECT * FROM pasien WHERE id_pasien = ?", [$id]);
    
    if (!$patient) {
        setFlashMessage('Pasien tidak ditemukan.', 'error');
        redirect('/basis-data/admin?action=patients');
    }
    
    $pageTitle = 'Detail Pasien: ' . $patient['nama_pasien'];
    
    // Mengambil catatan medis pasien
    $medicalRecords = dbQuery("
        SELECT cm.*, d.nama_dokter
        FROM catatan_medik cm
        JOIN dokter d ON cm.id_dokter = d.id_dokter
        WHERE cm.id_pasien = ?
        ORDER BY cm.tanggal_catatan DESC
    ", [$id]);
    
    // Mengambil data registrasi pasien
    $registrations = dbQuery("
        SELECT *
        FROM registrasi
        WHERE id_pasien = ?
        ORDER BY waktu_registrasi DESC
    ", [$id]);
    
    // Mengambil transaksi pasien
    $transactions = dbQuery("
        SELECT t.*, k.nama_kasir
        FROM transaksi t
        JOIN petugas_kasir k ON t.id_kasir = k.id_kasir
        WHERE t.id_pasien = ?
        ORDER BY t.waktu_transaksi DESC
    ", [$id]);
    
    require_once __DIR__ . '/../views/admin/patients/view.php';
}

// Handler manajemen dokter
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
        default:
            http_response_code(404);
            require_once __DIR__ . '/../views/404.php';
            break;
    }
}

// Menambah dokter baru
function addDoctor() {
    $pageTitle = 'Add Doctor';
    
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validasi CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlashMessage('Form tidak valid. Silakan coba lagi.', 'error');
            redirect('/basis-data/admin?action=doctors&sub_action=add');
        }
        
        // Validasi input
        $name = trim($_POST['name'] ?? '');
        $specialization = trim($_POST['specialization'] ?? '');
        
        if (empty($name)) {
            setFlashMessage('Nama dokter wajib diisi.', 'error');
            redirect('/basis-data/admin?action=doctors&sub_action=add');
        }
        
        // Query insert dokter baru
        $sql = "INSERT INTO dokter (nama_dokter, specialization) VALUES (?, ?)";
        $result = dbExecute($sql, [$name, $specialization]);
        
        if ($result) {
            setFlashMessage('Dokter berhasil ditambahkan.', 'success');
            redirect('/basis-data/admin?action=doctors');
        } else {
            setFlashMessage('Gagal menambah dokter.', 'error');
            redirect('/basis-data/admin?action=doctors&sub_action=add');
        }
    }
    require_once __DIR__ . '/../views/admin/doctors/add.php';
}

// Mengedit data dokter
function editDoctor() {
    $pageTitle = 'Edit Doctor';
    
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($id <= 0) {
        setFlashMessage('ID dokter tidak valid.', 'error');
        redirect('/basis-data/admin?action=doctors');
    }
    
    // Mengambil data dokter
    $doctor = dbQuerySingle("SELECT * FROM dokter WHERE id_dokter = ?", [$id]);
    
    if (!$doctor) {
        setFlashMessage('Dokter tidak ditemukan.', 'error');
        redirect('/basis-data/admin?action=doctors');
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validasi CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlashMessage('Form tidak valid. Silakan coba lagi.', 'error');
            redirect('/basis-data/admin?action=doctors&sub_action=edit&id=' . $id);
        }
        
        // Validasi input
        $name = trim($_POST['name'] ?? '');
        $specialization = trim($_POST['specialization'] ?? '');
        
        if (empty($name)) {
            setFlashMessage('Nama dokter wajib diisi.', 'error');
            redirect('/basis-data/admin?action=doctors&sub_action=edit&id=' . $id);
        }
        
        // Query update data dokter
        $sql = "UPDATE dokter SET nama_dokter = ?, specialization = ? WHERE id_dokter = ?";
        $result = dbExecute($sql, [$name, $specialization, $id]);
        
        if ($result) {
            setFlashMessage('Data dokter berhasil diupdate.', 'success');
            redirect('/basis-data/admin?action=doctors');
        } else {
            setFlashMessage('Gagal update data dokter.', 'error');
            redirect('/basis-data/admin?action=doctors&sub_action=edit&id=' . $id);
        }
    }
    $name = $doctor['nama_dokter'];
    $specialization = $doctor['specialization'];
    require_once __DIR__ . '/../views/admin/doctors/edit.php';
}

// Menghapus data dokter
function deleteDoctor() {
    $id = isset($_GET['id'])? (int)$_GET['id'] : 0;
    if ($id <= 0) {
        setFlashMessage('ID dokter tidak valid.', 'error');
        redirect('/basis-data/admin?action=doctors');
    }

    // Cek apakah dokter masih digunakan di catatan medis
    $hasMedicalRecords = dbQuerySingle("SELECT COUNT(*) as count FROM catatan_medik WHERE id_dokter = ?", [$id])['count'] > 0;

    if ($hasMedicalRecords) {
        setFlashMessage('Tidak bisa dihapus karena masih digunakan di catatan medis.', 'error');
        redirect('/basis-data/admin?action=doctors');
    }

    // Mengambil data dokter
    $doctor = dbQuerySingle("SELECT * FROM dokter WHERE id_dokter = ?", [$id]);
    if (!$doctor) {
        setFlashMessage('Dokter tidak ditemukan.', 'error');
        redirect('/basis-data/admin?action=doctors');
    }
    // Query hapus dokter
    $sql = "DELETE FROM dokter WHERE id_dokter = ?";
    $result = dbExecute($sql, [$id]);
    if ($result) {
        setFlashMessage('Dokter berhasil dihapus.', 'success');
    } else {
        setFlashMessage('Gagal menghapus dokter.', 'error');
    }
    redirect('/basis-data/admin?action=doctors');
}

// Menampilkan daftar dokter
function listDoctors() {
    $pageTitle = 'Doctors';
    
    // Pagination
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = 10;
    $offset = ($page - 1) * $perPage;
    
    // Pencarian dokter
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $searchWhere = '';
    $searchParams = [];
    
    if (!empty($search)) {
        $searchWhere = " WHERE nama_dokter ILIKE ?";
        $searchParams = ["%$search%"];
    }
    
    // Menghitung total dokter
    $countSql = "SELECT COUNT(*) as count FROM dokter" . $searchWhere;
    $totalCount = dbQuerySingle($countSql, $searchParams)['count'];
    $totalPages = ceil($totalCount / $perPage);
    
    // Mengambil data dokter
    $sql = "SELECT * FROM dokter" . $searchWhere . " ORDER BY id_dokter DESC LIMIT ? OFFSET ?";
    $params = array_merge($searchParams, [$perPage, $offset]);
    $doctors = dbQuery($sql, $params);
    
    require_once __DIR__ . '/../views/admin/doctors/list.php';
}

// Handler manajemen appointment
function handleAppointments() {
    $subAction = isset($_GET['sub_action']) ? $_GET['sub_action'] : 'list';
    
    switch ($subAction) {
        case 'list':
            listAppointments();
            break;
        case 'edit':
            editAppointment();
            break;
        case 'delete':
            deleteAppointment();
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . '/../views/404.php';
            break;
    }
}


// Menghapus appointment
function deleteAppointment() {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($id <= 0) {
        setFlashMessage('ID appointment tidak valid.', 'error');
        redirect('/basis-data/admin?action=appointments');
    }
    
    // Cek apakah appointment ada
    $appointment = dbQuerySingle("SELECT * FROM appointments WHERE id_appointment = ?", [$id]);
    
    if (!$appointment) {
        setFlashMessage('Appointment tidak ditemukan.', 'error');
        redirect('/basis-data/admin?action=appointments');
    }
    
    // Query hapus appointment
    $sql = "DELETE FROM appointments WHERE id_appointment = ?";
    $result = dbExecute($sql, [$id]);
    
    if ($result) {
        setFlashMessage('Appointment berhasil dihapus.', 'success');
    } else {
        setFlashMessage('Gagal menghapus appointment.', 'error');
    }
    
    redirect('/basis-data/admin?action=appointments');
}

// Mengedit appointment
function editAppointment() {
    $pageTitle = 'Edit Appointment';
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($id <= 0) {
        setFlashMessage('ID appointment tidak valid.', 'error');
        redirect('/basis-data/admin?action=appointments');
    }

    // Ambil data appointment
    $appointment = dbQuerySingle("SELECT * FROM appointments WHERE id_appointment = ?", [$id]);

    if (!$appointment) {
        setFlashMessage('Appointment tidak ditemukan.', 'error');
        redirect('/basis-data/admin?action=appointments');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validasi CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlashMessage('Form tidak valid. Silakan coba lagi.', 'error');
            redirect('/basis-data/admin?action=appointments&sub_action=edit&id=' . $id);
        }

        // Validasi input
        $id_pasien = $_POST['id_pasien'] ?? '';
        $tanggal_janji = $_POST['tanggal_janji'] ?? '';
        $status = $_POST['status'] ?? '';

        if (empty($id_pasien) || empty($tanggal_janji) || empty($status)) {
            setFlashMessage('Semua field wajib diisi.', 'error');
            redirect('/basis-data/admin?action=appointments&sub_action=edit&id=' . $id);
        }

        // Update data appointment
        $sql = "UPDATE appointments SET id_pasien = ?, tanggal_janji = ?, status = ? WHERE id_appointment = ?";
        $result = dbExecute($sql, [$id_pasien, $tanggal_janji, $status, $id]);

        if ($result) {
            setFlashMessage('Appointment berhasil diupdate.', 'success');
            redirect('/basis-data/admin?action=appointments');
        } else {
            setFlashMessage('Gagal mengupdate appointment.', 'error');
            redirect('/basis-data/admin?action=appointments&sub_action=edit&id=' . $id);
        }
    }

    // Ambil daftar pasien untuk select option
    $patients = dbQuery("SELECT * FROM pasien ORDER BY nama_pasien ASC");

    require_once __DIR__ . '/../views/admin/appointments/edit.php';
}

// Menampilkan daftar appointment
function listAppointments() {
    $pageTitle = 'List Appointments';

    // Pagination
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = 10;
    $offset = ($page - 1) * $perPage;

    // Pencarian appointment
    $search = isset($_GET['search'])? $_GET['search'] : '';
    $searchWhere = '';
    $searchParams = [];

    if (!empty($search)) {
        $searchWhere = " WHERE p.nama_pasien ILIKE ? OR d.nama_dokter ILIKE ?";
        $searchParams = ["%$search%", "%$search%"];
    }

    // Menghitung total appointment
    $countSql = "SELECT COUNT(*) as count FROM appointments a
        JOIN pasien p ON a.id_pasien = p.id_pasien
        JOIN dokter d ON a.id_dokter = d.id_dokter" . $searchWhere;
    $totalCount = dbQuerySingle($countSql, $searchParams)['count'];
    $totalPages = ceil($totalCount / $perPage);

    // Mengambil data appointment
    $sql = "SELECT a.*, p.nama_pasien, d.nama_dokter
        FROM appointments a
        JOIN pasien p ON a.id_pasien = p.id_pasien
        JOIN dokter d ON a.id_dokter = d.id_dokter" . $searchWhere . "
        ORDER BY a.tanggal_janji DESC LIMIT ? OFFSET ?";
    $params = array_merge($searchParams, [$perPage, $offset]);
    $appointments = dbQuery($sql, $params);

    require_once __DIR__ . '/../views/admin/appointments/list.php';
}

// Handler laporan
function handleReports() {
    $subAction = isset($_GET['sub_action']) ? $_GET['sub_action'] : 'financial';
    
    switch ($subAction) {
        case 'financial':
            financialReport();
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . '/../views/404.php';
            break;
    }
}

// Laporan keuangan
function financialReport() {
    $pageTitle = 'Financial Report';
    
    // Ambil rentang tanggal
    $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
    $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
    
    // Mengambil data transaksi sesuai rentang tanggal
    $transactions = dbQuery("
        SELECT t.*, p.nama_pasien, k.nama_kasir
        FROM transaksi t
        JOIN pasien p ON t.id_pasien = p.id_pasien
        JOIN petugas_kasir k ON t.id_kasir = k.id_kasir
        WHERE DATE(t.waktu_transaksi) BETWEEN ? AND ?
        ORDER BY t.waktu_transaksi DESC
    ", [$startDate, $endDate]);
    
    // Hitung total transaksi
    $total = 0;
    foreach ($transactions as $transaction) {
        $total += $transaction['harga'];
    }
    
    require_once __DIR__ . '/../views/admin/reports/financial.php';
}

// Handler pengaturan aplikasi
function handleSettings() {
    $pageTitle = 'Settings';
    require_once __DIR__ . '/../views/admin/settings.php';
}
?>