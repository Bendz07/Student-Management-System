<?php
// Define APP_ROOT first - this is the most important fix
if (!defined('APP_ROOT')) {
    // Calculate from current file path
    // Current file: C:/xampp1/htdocs/student-management-system/app/views/students/index.php
    // Need to go up 3 levels to get to root
    define('APP_ROOT', dirname(dirname(dirname(__DIR__))));
}

// Now load configuration
$configPath = APP_ROOT . '/config/config.php';
if (file_exists($configPath)) {
    require_once $configPath;
} else {
    die("Configuration file not found at: " . $configPath);
}

// Load auth middleware
require_once APP_ROOT . '/middleware/auth.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header("Location: " . APP_URL . "/login.php");
    exit();
}

// Now include the controller - APP_ROOT is now defined
$controllerPath = APP_ROOT . '/app/controllers/StudentController.php';
if (!file_exists($controllerPath)) {
    die("Controller file not found at: " . $controllerPath);
}

require_once $controllerPath;

if (!class_exists('StudentController')) {
    die("Class 'StudentController' not found after including file.");
}

$studentController = new StudentController();

// Handle pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Get students with pagination
$students = $studentController->getPaginatedStudents($page, $perPage, $search);
$totalStudents = $studentController->getTotalCount($search);
$totalPages = ceil($totalStudents / $perPage);
?>
<?php include APP_ROOT . '/app/views/layout/header.php'; ?>
<?php include APP_ROOT . '/app/views/layout/navbar.php'; ?>

<!-- Rest of your HTML code remains the same -->
<!-- ... -->