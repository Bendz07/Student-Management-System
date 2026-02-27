<?php
require_once 'config/config.php';
require_once 'app/controllers/AuthController.php';

$auth = new AuthController();
$auth->logout();

header("Location: " . APP_URL . "/login.php");
exit();
?>
<?php include APP_ROOT . '/app/views/layout/footer.php'; ?>
<?php include APP_ROOT . '/app/views/layout/header.php'; ?>
<?php include APP_ROOT . '/app/views/layout/navbar.php'; ?>
<?php include APP_ROOT . '/app/views/auth/login.php'; ?>
<?php include APP_ROOT . '/app/views/layout/footer.php'; ?>
