<?php
// Define APP_ROOT if not defined
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(dirname(__DIR__)));
}

// Load configuration
require_once APP_ROOT . '/config/config.php';
require_once APP_ROOT . '/middleware/auth.php';
require_once APP_ROOT . '/app/controllers/StudentController.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header("Location: " . APP_URL . "/login.php");
    exit();
}

$studentController = new StudentController();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch($action) {
        case 'create':
            if ($studentController->create($_POST)) {
                $_SESSION['success'] = $lang['student_added'] ?? 'Student added successfully!';
            } else {
                $_SESSION['error'] = $lang['error_occurred'] ?? 'An error occurred. Please try again.';
            }
            break;
            
        case 'update':
            if (isset($_POST['id']) && $studentController->update($_POST['id'], $_POST)) {
                $_SESSION['success'] = $lang['student_updated'] ?? 'Student updated successfully!';
            } else {
                $_SESSION['error'] = $lang['error_occurred'] ?? 'An error occurred. Please try again.';
            }
            break;
    }
    
    header("Location: " . APP_URL . "/app/views/students/index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'] ?? 0;
    
    if ($id && $studentController->delete($id)) {
        $_SESSION['success'] = $lang['student_deleted'] ?? 'Student deleted successfully!';
    } else {
        $_SESSION['error'] = $lang['error_occurred'] ?? 'An error occurred. Please try again.';
    }
    
    header("Location: " . APP_URL . "/app/views/students/index.php");
    exit();
}

// If we get here, redirect to index
header("Location: " . APP_URL . "/app/views/students/index.php");
exit();
?>