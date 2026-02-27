<?php
// Define APP_ROOT if not defined
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

// Simple autoloader
spl_autoload_register(function ($class_name) {
    // List of directories to search
    $directories = [
        APP_ROOT . '/app/controllers/',
        APP_ROOT . '/app/models/',
        APP_ROOT . '/middleware/'
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Session management
session_start();

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'student_management');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application configuration
define('APP_NAME', 'Student Management System');
define('APP_URL', 'http://localhost/student-management-system');

// Timezone
date_default_timezone_set('Africa/Algiers');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
require_once APP_ROOT . '/config/database.php';

// Language configuration
$available_langs = ['ar', 'en'];
$default_lang = 'en';

// Set language
if (isset($_GET['lang']) && in_array($_GET['lang'], $available_langs)) {
    $_SESSION['lang'] = $_GET['lang'];
}

$current_lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : $default_lang;

// Load language file
$lang_file = APP_ROOT . '/lang/' . $current_lang . '.php';
if (file_exists($lang_file)) {
    require_once $lang_file;
} else {
    // Fallback to English
    require_once APP_ROOT . '/lang/en.php';
}
?>