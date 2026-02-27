<?php
echo "<h2>Path Debugging</h2>";

echo "<h3>Current File:</h3>";
echo "__FILE__: " . __FILE__ . "<br>";
echo "__DIR__: " . __DIR__ . "<br>";

echo "<h3>Calculated Paths:</h3>";
echo "APP_ROOT (should be " . __DIR__ . "): " . __DIR__ . "<br>";

echo "<h3>Check Important Files:</h3>";
$files_to_check = [
    'config/config.php',
    'config/database.php',
    'app/models/User.php',
    'app/controllers/AuthController.php',
    'app/views/auth/login.php',
    'app/views/layout/header.php'
];

foreach ($files_to_check as $file) {
    $full_path = __DIR__ . '/' . $file;
    $exists = file_exists($full_path) ? '✓ EXISTS' : '✗ NOT FOUND';
    echo "$file: $exists<br>";
}

echo "<h3>Session Status:</h3>";
echo "Session ID: " . (session_id() ?: 'Not started') . "<br>";
?>