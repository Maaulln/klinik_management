<?php
// Home controller
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Home page
$pageTitle = APP_NAME;
require_once __DIR__ . '/../views/home.php';
?>