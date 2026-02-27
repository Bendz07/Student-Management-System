<?php
require_once 'config/config.php';
require_once 'middleware/auth.php';

if (isLoggedIn()) {
    header("Location: " . APP_URL . "/app/views/dashboard/index.php");
    exit();
} else {
    header("Location: " . APP_URL . "/login.php");
    exit();
}
?>

