<?php
// Define APP_ROOT if not defined
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(dirname(dirname(__DIR__))));
}

// Ensure config is loaded
if (!isset($current_lang) || !isset($lang)) {
    $config_path = APP_ROOT . '/config/config.php';
    if (file_exists($config_path)) {
        require_once $config_path;
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang ?? 'en'; ?>" dir="<?php echo ($current_lang ?? 'en') == 'ar' ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo defined('APP_NAME') ? APP_NAME : 'Student Management System'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        <?php if(isset($current_lang) && $current_lang == 'ar'): ?>
        body {
            font-family: 'Cairo', sans-serif;
        }
        .navbar-nav {
            padding-right: 0;
        }
        .me-auto {
            margin-right: 0 !important;
            margin-left: auto !important;
        }
        <?php endif; ?>
    </style>
</head>
<body>