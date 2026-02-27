<?php
echo "<h2>Setting up Fresh Database</h2>";

try {
    // Connect to MySQL without database
    $pdo = new PDO("mysql:host=localhost;port=3306", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connected to MySQL<br>";
    
    // Read SQL file
    $sql = file_get_contents('fresh_database.sql');
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    // Execute each statement
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
                echo "✓ Executed: " . substr($statement, 0, 50) . "...<br>";
            } catch (PDOException $e) {
                echo "✗ Error: " . $e->getMessage() . "<br>";
            }
        }
    }
    
    echo "<h3 style='color: green;'>✓ Database setup completed successfully!</h3>";
    
    // Display connection info
    echo "<h4>Database Information:</h4>";
    echo "<ul>";
    echo "<li><strong>Database Name:</strong> student_management</li>";
    echo "<li><strong>Host:</strong> localhost</li>";
    echo "<li><strong>Username:</strong> root</li>";
    echo "<li><strong>Password:</strong> (empty)</li>";
    echo "</ul>";
    
    // Display default users
    $pdo->exec("USE student_management");
    $users = $pdo->query("SELECT username, email, role FROM users")->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h4>Default Users Created:</h4>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Username</th><th>Email</th><th>Role</th><th>Default Password</th></tr>";
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['username'] . "</td>";
        echo "<td>" . $user['email'] . "</td>";
        echo "<td>" . $user['role'] . "</td>";
        echo "<td>admin123</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<p><strong>Note:</strong> All users have the same default password: <code>admin123</code></p>";
    echo "<p><strong>Important:</strong> Change these passwords after first login!</p>";
    
} catch (PDOException $e) {
    echo "<h3 style='color: red;'>✗ Database setup failed: " . $e->getMessage() . "</h3>";
    echo "<p>Please make sure MySQL is running in XAMPP Control Panel.</p>";
}
?>