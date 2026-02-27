<?php
// Ensure APP_ROOT is defined
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(dirname(dirname(__DIR__))));
}

// Ensure APP_URL is defined
if (!defined('APP_URL')) {
    define('APP_URL', 'http://localhost/student-management-system');
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="<?php echo APP_URL; ?>/index.php">
    <i class="fas fa-graduation-cap"></i> 
    <?php echo $lang['app_name'] ?? 'Student Management System'; ?>
</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if(isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo APP_URL; ?>/app/views/dashboard/index.php">
                        <i class="fas fa-tachometer-alt"></i> <?php echo $lang['dashboard'] ?? 'Dashboard'; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo APP_URL; ?>/app/views/students/index.php">
                        <i class="fas fa-users"></i> <?php echo $lang['students'] ?? 'Students'; ?>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-language"></i> <?php echo ($current_lang ?? 'en') == 'ar' ? 'العربية' : 'English'; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="?lang=en">English</a></li>
                        <li><a class="dropdown-item" href="?lang=ar">العربية</a></li>
                    </ul>
                </li>
                <?php if(isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <span class="nav-link">
                        <i class="fas fa-user"></i> <?php echo $_SESSION['username'] ?? 'User'; ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo APP_URL; ?>/logout.php">
                        <i class="fas fa-sign-out-alt"></i> <?php echo $lang['logout'] ?? 'Logout'; ?>
                    </a>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo APP_URL; ?>/login.php">
                        <i class="fas fa-sign-in-alt"></i> <?php echo $lang['login'] ?? 'Login'; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo APP_URL; ?>/signup.php">
                        <i class="fas fa-user-plus"></i> <?php echo $lang['signup'] ?? 'Sign Up'; ?>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-4">