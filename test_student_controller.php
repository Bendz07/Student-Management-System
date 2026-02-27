<?php
require_once 'config/config.php';

echo "<h2>Testing StudentController</h2>";

// Try to autoload
if (class_exists('StudentController')) {
    echo "✅ StudentController loaded via autoloader<br>";
    $controller = new StudentController();
    echo "✅ Controller instantiated<br>";
} else {
    echo "❌ Autoloader failed. Trying manual include...<br>";
    
    // Manual include
    $path = __DIR__ . '/app/controllers/StudentController.php';
    if (file_exists($path)) {
        require_once $path;
        echo "✅ File included from: " . $path . "<br>";
        
        if (class_exists('StudentController')) {
            echo "✅ Class found after manual include<br>";
            $controller = new StudentController();
            echo "✅ Controller instantiated<br>";
        } else {
            echo "❌ Class still not found. Check the file content.<br>";
        }
    } else {
        echo "❌ File not found at: " . $path . "<br>";
    }
}
?>