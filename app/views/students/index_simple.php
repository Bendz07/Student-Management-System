<?php
// Define APP_ROOT if not defined
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(dirname(dirname(__DIR__))));
}

// Load configuration
require_once APP_ROOT . '/config/config.php';
require_once APP_ROOT . '/middleware/auth.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header("Location: " . APP_URL . "/login.php");
    exit();
}

// Use Student model directly instead of controller
require_once APP_ROOT . '/app/models/Student.php';

$student = new Student();

// Handle pagination manually
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$offset = ($page - 1) * $perPage;

// Build query
$query = "SELECT * FROM students ";
$countQuery = "SELECT COUNT(*) as total FROM students ";

if (!empty($search)) {
    $where = "WHERE name LIKE :search OR email LIKE :search OR phone LIKE :search ";
    $query .= $where;
    $countQuery .= $where;
}

$query .= "ORDER BY created_at DESC LIMIT :limit OFFSET :offset";

// Get students
$stmt = $student->conn->prepare($query);
if (!empty($search)) {
    $searchTerm = "%{$search}%";
    $stmt->bindParam(':search', $searchTerm);
}
$stmt->bindParam(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$students = $stmt;

// Get total count
$countStmt = $student->conn->prepare($countQuery);
if (!empty($search)) {
    $countStmt->bindParam(':search', $searchTerm);
}
$countStmt->execute();
$totalStudents = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalStudents / $perPage);
?>
<?php include APP_ROOT . '/app/views/layout/header.php'; ?>
<?php include APP_ROOT . '/app/views/layout/navbar.php'; ?>

<!-- Your existing HTML code here -->