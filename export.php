<?php
require_once 'config/config.php';
require_once 'middleware/auth.php';
requireLogin();

require_once APP_ROOT . '/app/models/Student.php';

$format = $_GET['format'] ?? 'excel';
$type = $_GET['type'] ?? 'students';

$student = new Student();
$data = $student->readAll();

if ($format == 'excel') {
    exportToExcel($data);
} elseif ($format == 'pdf') {
    exportToPDF($data);
}

function exportToExcel($data) {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="students_export_' . date('Y-m-d') . '.xls"');
    
    echo '<html>';
    echo '<head><title>Students Export</title></head>';
    echo '<body>';
    echo '<table border="1">';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Name</th>';
    echo '<th>Email</th>';
    echo '<th>Phone</th>';
    echo '<th>Gender</th>';
    echo '<th>Grade</th>';
    echo '<th>Created At</th>';
    echo '</tr>';
    
    while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . htmlspecialchars($row['name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['email']) . '</td>';
        echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
        echo '<td>' . ucfirst($row['gender']) . '</td>';
        echo '<td>' . htmlspecialchars($row['grade']) . '</td>';
        echo '<td>' . $row['created_at'] . '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
    echo '</body>';
    echo '</html>';
}

function exportToPDF($data) {
    // You'll need to install TCPDF or FPDF library
    // For now, we'll create a simple HTML version that can be printed as PDF
    header('Content-Type: text/html');
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Students Export PDF</title>
        <style>
            body { font-family: Arial, sans-serif; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th { background-color: #4CAF50; color: white; padding: 10px; text-align: left; }
            td { padding: 8px; border-bottom: 1px solid #ddd; }
            tr:hover { background-color: #f5f5f5; }
            h1 { color: #333; }
        </style>
    </head>
    <body>
        <h1>Students List - <?php echo date('Y-m-d'); ?></h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Grade</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $data->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo ucfirst($row['gender']); ?></td>
                    <td><?php echo htmlspecialchars($row['grade']); ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <p style="text-align: center; margin-top: 30px;">Generated on <?php echo date('Y-m-d H:i:s'); ?></p>
    </body>
    </html>
    <?php
}
?>