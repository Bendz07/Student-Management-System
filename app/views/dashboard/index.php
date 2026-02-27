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

// Include the Student model directly (simpler for dashboard)
require_once APP_ROOT . '/app/models/Student.php';

// Create student object
$student = new Student();

// Get statistics
$totalStudents = $student->getTotalCount();
$recentStudents = $student->getRecentStudents(5);

// Get gender distribution
$genderQuery = "SELECT gender, COUNT(*) as count FROM students GROUP BY gender";
$genderStmt = $student->getConnection()->prepare($genderQuery);
$genderStmt->execute();
$genderStats = [];
while ($row = $genderStmt->fetch(PDO::FETCH_ASSOC)) {
    $genderStats[$row['gender']] = $row['count'];
}

// Get new students this month
$newStudentsQuery = "SELECT COUNT(*) as count FROM students 
                     WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
$newStmt = $student->getConnection()->prepare($newStudentsQuery);
$newStmt->execute();
$newThisMonth = $newStmt->fetch(PDO::FETCH_ASSOC)['count'];
?>
<?php include APP_ROOT . '/app/views/layout/header.php'; ?>
<?php include APP_ROOT . '/app/views/layout/navbar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <h1><i class="fas fa-tachometer-alt"></i> <?php echo $lang['dashboard'] ?? 'Dashboard'; ?></h1>
        <hr>
    </div>
</div>

<div class="row">
    <!-- Total Students Card -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title"><?php echo $lang['total_students'] ?? 'Total Students'; ?></h5>
                        <h2 class="mb-0"><?php echo $totalStudents; ?></h2>
                    </div>
                    <i class="fas fa-users fa-3x"></i>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?php echo APP_URL; ?>/app/views/students/index.php" class="text-white">
                    <?php echo $lang['view_details'] ?? 'View Details'; ?> <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- New Students Card -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title"><?php echo $lang['new_students'] ?? 'New Students'; ?></h5>
                        <h2 class="mb-0"><?php echo $newThisMonth; ?></h2>
                    </div>
                    <i class="fas fa-user-plus fa-3x"></i>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?php echo APP_URL; ?>/app/views/students/create.php" class="text-white">
                    <?php echo $lang['add_new'] ?? 'Add New'; ?> <i class="fas fa-plus-circle"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Male Students Card -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title"><?php echo $lang['male'] ?? 'Male'; ?> Students</h5>
                        <h2 class="mb-0"><?php echo $genderStats['male'] ?? 0; ?></h2>
                    </div>
                    <i class="fas fa-male fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Female Students Card -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title"><?php echo $lang['female'] ?? 'Female'; ?> Students</h5>
                        <h2 class="mb-0"><?php echo $genderStats['female'] ?? 0; ?></h2>
                    </div>
                    <i class="fas fa-female fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><?php echo $lang['recent_students'] ?? 'Recent Students'; ?></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo $lang['name'] ?? 'Name'; ?></th>
                                <th><?php echo $lang['email'] ?? 'Email'; ?></th>
                                <th><?php echo $lang['grade'] ?? 'Grade'; ?></th>
                                <th><?php echo $lang['actions'] ?? 'Actions'; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($recentStudents && $recentStudents->rowCount() > 0): ?>
                                <?php while($row = $recentStudents->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['grade']); ?></td>
                                    <td>
                                        <a href="<?php echo APP_URL; ?>/app/views/students/edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center"><?php echo $lang['no_students'] ?? 'No students found'; ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?php echo APP_URL; ?>/app/views/students/index.php" class="btn btn-primary btn-sm">
                    <?php echo $lang['view_all'] ?? 'View All Students'; ?> <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Quick Actions Card -->
        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-bolt"></i> <?php echo $lang['quick_actions'] ?? 'Quick Actions'; ?></h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo APP_URL; ?>/app/views/students/create.php" class="btn btn-success">
                        <i class="fas fa-user-plus"></i> <?php echo $lang['add_new_student'] ?? 'Add New Student'; ?>
                    </a>
                    <a href="<?php echo APP_URL; ?>/export.php?format=excel" class="btn btn-info">
                        <i class="fas fa-file-excel"></i> <?php echo $lang['export_excel'] ?? 'Export to Excel'; ?>
                    </a>
                    <a href="<?php echo APP_URL; ?>/export.php?format=pdf" class="btn btn-danger">
                        <i class="fas fa-file-pdf"></i> <?php echo $lang['export_pdf'] ?? 'Export to PDF'; ?>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Statistics Card -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-chart-pie"></i> <?php echo $lang['statistics'] ?? 'Statistics'; ?></h5>
            </div>
            <div class="card-body">
                <canvas id="genderChart" style="max-height: 200px;"></canvas>
                <hr>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo $lang['total_students'] ?? 'Total Students'; ?>
                        <span class="badge bg-primary rounded-pill"><?php echo $totalStudents; ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo $lang['new_this_month'] ?? 'New This Month'; ?>
                        <span class="badge bg-success rounded-pill"><?php echo $newThisMonth; ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js for statistics -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gender distribution chart
    const ctx = document.getElementById('genderChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['<?php echo $lang['male'] ?? 'Male'; ?>', '<?php echo $lang['female'] ?? 'Female'; ?>'],
            datasets: [{
                data: [<?php echo $genderStats['male'] ?? 0; ?>, <?php echo $genderStats['female'] ?? 0; ?>],
                backgroundColor: ['#36A2EB', '#FF6384'],
                hoverBackgroundColor: ['#36A2EB', '#FF6384']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>

<?php include APP_ROOT . '/app/views/layout/footer.php'; ?>