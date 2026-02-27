<?php
require_once 'config/config.php';
require_once 'app/models/Student.php';

echo "<h2>Testing Student Model</h2>";

$student = new Student();

// Test readAll method
echo "<h3>Testing readAll() method:</h3>";
$result = $student->readAll();
if ($result) {
    echo "✓ readAll() method exists and works<br>";
    echo "Total students found: " . $result->rowCount() . "<br>";
} else {
    echo "✗ readAll() method failed<br>";
}

// Test getTotalCount method
echo "<h3>Testing getTotalCount() method:</h3>";
$total = $student->getTotalCount();
echo "Total count: " . $total . "<br>";

// Test getRecentStudents method
echo "<h3>Testing getRecentStudents() method:</h3>";
$recent = $student->getRecentStudents(3);
if ($recent) {
    echo "✓ getRecentStudents() method works<br>";
    echo "Recent students found: " . $recent->rowCount() . "<br>";
}

// Show all students if any
if ($result && $result->rowCount() > 0) {
    echo "<h3>All Students:</h3>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Grade</th></tr>";
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['grade']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No students found in database. Please add some students first.</p>";
}
?>