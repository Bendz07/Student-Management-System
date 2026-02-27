<?php
echo "<h2>Cleaning up bootstrap.php references</h2>";

function scanAndFix($dir) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file == '.' || $file == '..') continue;
        
        $path = $dir . '/' . $file;
        
        if (is_dir($path)) {
            scanAndFix($path);
        } elseif (pathinfo($path, PATHINFO_EXTENSION) == 'php') {
            $content = file_get_contents($path);
            if (strpos($content, 'bootstrap.php') !== false) {
                echo "Found in: $path<br>";
                
                // Create backup
                copy($path, $path . '.backup');
                echo "Backup created: $path.backup<br>";
                
                // Remove bootstrap.php reference
                $new_content = preg_replace(
                    '/require_once\s+[\'"]\.\.\/\.\.\/bootstrap\.php[\'"];\s*/',
                    "// Define APP_ROOT if not defined\nif (!defined('APP_ROOT')) {\n    define('APP_ROOT', dirname(dirname(dirname(__DIR__))));\n}\n\n// Load configuration directly\nrequire_once APP_ROOT . '/config/config.php';\n",
                    $content
                );
                
                file_put_contents($path, $new_content);
                echo "Fixed: $path<br>";
                echo "-------------------<br>";
            }
        }
    }
}

// Start scanning from app/views directory
if (is_dir('app/views')) {
    scanAndFix('app/views');
} else {
    echo "app/views directory not found!";
}

echo "<h3>Cleanup complete!</h3>";
echo "<p>Please check your files. Backups were created with .backup extension.</p>";
?>