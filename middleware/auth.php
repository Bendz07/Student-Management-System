<?php
// Define APP_URL if not defined
if (!defined('APP_URL')) {
    define('APP_URL', 'http://localhost/student-management-system');
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: " . APP_URL . "/login.php");
        exit();
    }
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function requireAdmin() {
    if (!isAdmin()) {
        header("Location: " . APP_URL . "/index.php");
        exit();
    }
}
?>