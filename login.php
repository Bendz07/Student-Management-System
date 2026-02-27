<?php
// Define APP_ROOT if not defined
if (!defined('APP_ROOT')) {
    define('APP_ROOT', __DIR__);
}

require_once APP_ROOT . '/config/config.php';
require_once APP_ROOT . '/app/controllers/AuthController.php';

$auth = new AuthController();

// Redirect if already logged in
if ($auth->isAuthenticated()) {
    header("Location: " . APP_URL . "/index.php");
    exit();
}

// Handle login form submission
if (isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($auth->login($username, $password)) {
        header("Location: " . APP_URL . "/index.php");
        exit();
    } else {
        $_SESSION['error'] = $lang['invalid_credentials'] ?? 'Invalid username or password!';
    }
}

// Include the login view
include APP_ROOT . '/app/views/auth/login.php';
?>