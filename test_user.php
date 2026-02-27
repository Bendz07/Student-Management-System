<?php
require_once 'config/config.php';
require_once 'app/models/User.php';

echo "<h2>Testing User Model</h2>";

$user = new User();

// Test username exists
$test_username = "admin"; // Change this to test different usernames
echo "<h3>Testing username: $test_username</h3>";
if ($user->usernameExists($test_username)) {
    echo "Username '$test_username' EXISTS<br>";
} else {
    echo "Username '$test_username' is AVAILABLE<br>";
}

// Test email exists
$test_email = "admin@example.com"; // Change this to test different emails
echo "<h3>Testing email: $test_email</h3>";
if ($user->emailExists($test_email)) {
    echo "Email '$test_email' EXISTS<br>";
} else {
    echo "Email '$test_email' is AVAILABLE<br>";
}

// Show all users
echo "<h3>All Users in Database:</h3>";
$stmt = $user->readAll();
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th></tr>";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['username'] . "</td>";
    echo "<td>" . $row['email'] . "</td>";
    echo "<td>" . $row['role'] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>