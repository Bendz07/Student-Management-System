<?php
require_once APP_ROOT . '/app/models/Permission.php';

function checkPermission($requiredPermission) {
    if (!isset($_SESSION['user_role'])) {
        header("Location: " . APP_URL . "/login.php");
        exit();
    }
    
    $permission = new Permission();
    if (!$permission->hasPermission($_SESSION['user_role'], $requiredPermission)) {
        $_SESSION['error'] = "You don't have permission to access this page.";
        header("Location: " . APP_URL . "/index.php");
        exit();
    }
}

function getUserPermissions() {
    if (!isset($_SESSION['user_role'])) {
        return [];
    }
    
    $permission = new Permission();
    return $permission->getPermissionsByRole($_SESSION['user_role']);
}

function can($permission) {
    if (!isset($_SESSION['user_role'])) {
        return false;
    }
    
    $perm = new Permission();
    return $perm->hasPermission($_SESSION['user_role'], $permission);
}
?>