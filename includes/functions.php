<?php
// Fungsi-fungsi bantu (helper) untuk aplikasi

/**
 * Melakukan redirect ke URL tertentu
 * 
 * @param string $url URL tujuan redirect
 * @return void
 */
function redirect($url) {
    header('Location: ' . $url);
    exit();
}

/**
 * Membuat token CSRF untuk keamanan form
 * 
 * @return string Token CSRF
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Memverifikasi token CSRF
 * 
 * @param string $token Token yang akan diverifikasi
 * @return bool True jika token valid
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Menampilkan pesan flash (notifikasi sementara)
 * 
 * @return string HTML pesan flash
 */
function flashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'info';
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        
        $colors = [
            'success' => 'bg-green-100 border-green-500 text-green-700',
            'error' => 'bg-red-100 border-red-500 text-red-700',
            'warning' => 'bg-yellow-100 border-yellow-500 text-yellow-700',
            'info' => 'bg-blue-100 border-blue-500 text-blue-700'
        ];
        
        $color = $colors[$type] ?? $colors['info'];
        
        return "<div class='border-l-4 p-4 mb-4 {$color}' role='alert'>
                  <p>{$message}</p>
                </div>";
    }
    return '';
}

/**
 * Set pesan flash (notifikasi sementara)
 * 
 * @param string $message Pesan yang akan ditampilkan
 * @param string $type Jenis pesan (success, error, warning, info)
 * @return void
 */
function setFlashMessage($message, $type = 'info') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

/**
 * Membersihkan input dari karakter berbahaya
 * 
 * @param string $input Input yang akan dibersihkan
 * @return string Input yang sudah aman
 */
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Format tanggal ke bentuk yang diinginkan
 * 
 * @param string $date Tanggal yang akan diformat
 * @param string $format Format output tanggal
 * @return string Tanggal yang sudah diformat
 */
function formatDate($date, $format = 'Y-m-d') {
    $datetime = new DateTime($date);
    return $datetime->format($format);
}

/**
 * Mengecek apakah request berasal dari AJAX
 * 
 * @return bool True jika request AJAX
 */
function isAjax() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Mengirim response JSON ke client
 * 
 * @param mixed $data Data yang akan dikirim
 * @param int $status Kode status HTTP
 * @return void
 */
function jsonResponse($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

/**
 * Mengambil URL saat ini
 * 
 * @return string URL saat ini
 */
function getCurrentUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 * Membuat HTML pagination untuk navigasi halaman
 * 
 * @param int $currentPage Halaman saat ini
 * @param int $totalPages Total halaman
 * @param string $baseUrl URL dasar untuk link pagination
 * @return string HTML pagination
 */
function pagination($currentPage, $totalPages, $baseUrl) {
    if ($totalPages <= 1) {
        return '';
    }
    
    $html = '<div class="flex justify-center mt-4">';
    $html .= '<nav class="inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">';
    
    // Tombol halaman sebelumnya
    $prevDisabled = $currentPage <= 1 ? 'disabled' : '';
    $prevUrl = $currentPage <= 1 ? '#' : $baseUrl . '?page=' . ($currentPage - 1);
    $html .= '<a href="' . $prevUrl . '" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 ' . $prevDisabled . '">
                <span class="sr-only">Previous</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
              </a>';
    
    // Nomor halaman
    $range = 2;
    for ($i = max(1, $currentPage - $range); $i <= min($totalPages, $currentPage + $range); $i++) {
        $activeClass = $i === $currentPage ? 'bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50';
        $html .= '<a href="' . $baseUrl . '?page=' . $i . '" class="relative inline-flex items-center px-4 py-2 border text-sm font-medium ' . $activeClass . '">
                    ' . $i . '
                  </a>';
    }
    
    // Tombol halaman berikutnya
    $nextDisabled = $currentPage >= $totalPages ? 'disabled' : '';
    $nextUrl = $currentPage >= $totalPages ? '#' : $baseUrl . '?page=' . ($currentPage + 1);
    $html .= '<a href="' . $nextUrl . '" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 ' . $nextDisabled . '">
                <span class="sr-only">Next</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
              </a>';
    
    $html .= '</nav>';
    $html .= '</div>';
    
    return $html;
}
?>