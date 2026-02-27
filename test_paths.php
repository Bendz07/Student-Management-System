<?php
require_once 'config/config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Links</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Test All Pages</h1>
        <div class="list-group">
            <a href="<?php echo APP_URL; ?>/login.php" class="list-group-item list-group-item-action">Login Page</a>
            <a href="<?php echo APP_URL; ?>/signup.php" class="list-group-item list-group-item-action">Signup Page</a>
            <a href="<?php echo APP_URL; ?>/index.php" class="list-group-item list-group-item-action">Home (redirects to dashboard if logged in)</a>
            <a href="<?php echo APP_URL; ?>/app/views/dashboard/index.php" class="list-group-item list-group-item-action">Dashboard</a>
            <a href="<?php echo APP_URL; ?>/app/views/students/index.php" class="list-group-item list-group-item-action">Students List</a>
            <a href="<?php echo APP_URL; ?>/app/views/students/create.php" class="list-group-item list-group-item-action">Create Student</a>
            <a href="<?php echo APP_URL; ?>/debug_paths.php" class="list-group-item list-group-item-action">Debug Paths</a>
            <a href="<?php echo APP_URL; ?>/cleanup_bootstrap.php" class="list-group-item list-group-item-action">Cleanup Bootstrap References</a>
        </div>
    </div>
</body>
</html>