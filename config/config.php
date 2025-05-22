<?php
define('APP_NAME', 'Klinik Management System');
define('APP_URL', 'http://localhost:8080');
define('DEBUG_MODE', true);

define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'klinik_db');
define('DB_USER', 'postgres');
define('DB_PASS', '1234');

// Session settings
define('SESSION_LIFETIME', 86400);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Time zone
date_default_timezone_set('UTC');

// Load environment variables if .env file exists
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value);
            putenv(sprintf('%s=%s', trim($name), trim($value)));
        }
    }
}
?>