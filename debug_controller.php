<?php
// Define APP_ROOT first
define('APP_ROOT', __DIR__);

echo "<h2>🔍 StudentController Debugger</h2>";
echo "APP_ROOT: " . APP_ROOT . "<br><br>";

// Check controller path
$controllerPath = APP_ROOT . '/app/controllers/StudentController.php';
echo "Controller path: " . $controllerPath . "<br>";
echo "File exists: " . (file_exists($controllerPath) ? '✅ YES' : '❌ NO') . "<br><br>";

if (file_exists($controllerPath)) {
    echo "✅ Controller file found!<br>";
    echo "File size: " . filesize($controllerPath) . " bytes<br>";
    echo "Last modified: " . date("Y-m-d H:i:s", filemtime($controllerPath)) . "<br><br>";
    
    // Show first few lines of the file
    echo "First 10 lines of the file:<br>";
    echo "<pre style='background:#f4f4f4; padding:10px; border:1px solid #ccc;'>";
    $lines = file($controllerPath);
    for ($i = 0; $i < min(10, count($lines)); $i++) {
        echo htmlspecialchars($lines[$i]);
    }
    echo "</pre><br>";
    
    // Include the file and check class
    echo "Including controller file...<br>";
    require_once $controllerPath;
    
    echo "Class 'StudentController' exists: " . (class_exists('StudentController') ? '✅ YES' : '❌ NO') . "<br>";
    
    if (class_exists('StudentController')) {
        echo "<br>✅ Class found! Methods available:<br>";
        $methods = get_class_methods('StudentController');
        echo "<ul>";
        foreach ($methods as $method) {
            echo "<li>" . $method . "</li>";
        }
        echo "</ul>";
        
        // Try to instantiate
        try {
            $controller = new StudentController();
            echo "<br>✅ Successfully instantiated StudentController<br>";
        } catch (Exception $e) {
            echo "<br>❌ Error instantiating: " . $e->getMessage() . "<br>";
        }
    }
} else {
    echo "❌ Controller file NOT found!<br><br>";
    
    // Check if directory exists
    $controllerDir = APP_ROOT . '/app/controllers';
    if (is_dir($controllerDir)) {
        echo "Controllers directory exists. Contents:<br>";
        $files = scandir($controllerDir);
        echo "<ul>";
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                echo "<li>" . $file . "</li>";
            }
        }
        echo "</ul>";
    } else {
        echo "❌ Controllers directory does not exist!<br>";
        echo "Tried to access: " . $controllerDir . "<br>";
    }
}

// Check if Student model exists
echo "<br><br>--- Checking Student Model ---<br>";
$modelPath = APP_ROOT . '/app/models/Student.php';
echo "Model path: " . $modelPath . "<br>";
echo "File exists: " . (file_exists($modelPath) ? '✅ YES' : '❌ NO') . "<br>";

if (file_exists($modelPath)) {
    require_once $modelPath;
    echo "Class 'Student' exists: " . (class_exists('Student') ? '✅ YES' : '❌ NO') . "<br>";
}
?>