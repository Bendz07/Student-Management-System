<?php
require_once '../config/config.php';
require_once '../middleware/auth.php';
require_once '../middleware/permission.php';

requireLogin();
checkPermission('manage_users');

require_once APP_ROOT . '/app/models/User.php';
require_once APP_ROOT . '/app/models/Permission.php';

$userModel = new User();
$permissionModel = new Permission();

// Handle role update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_role'])) {
    $userId = $_POST['user_id'];
    $newRole = $_POST['role'];
    
    $user = $userModel->findById($userId);
    if ($user) {
        $userModel->id = $userId;
        $userModel->username = $user['username'];
        $userModel->email = $user['email'];
        $userModel->role = $newRole;
        $userModel->update();
        
        $_SESSION['success'] = "User role updated successfully";
    }
}

$users = $userModel->readAll();
?>
<?php include '../app/views/layout/header.php'; ?>
<?php include '../app/views/layout/navbar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <h2>Manage User Roles</h2>
        <hr>
        
        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Current Role</th>
                        <th>Change Role</th>
                        <th>Permissions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $users->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <span class="badge bg-<?php 
                                echo $user['role'] == 'admin' ? 'danger' : 
                                    ($user['role'] == 'teacher' ? 'warning' : 'info'); 
                            ?>">
                                <?php echo ucfirst($user['role']); ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" class="d-flex">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <select name="role" class="form-select form-select-sm me-2" style="width: auto;">
                                    <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                    <option value="teacher" <?php echo $user['role'] == 'teacher' ? 'selected' : ''; ?>>Teacher</option>
                                    <option value="student" <?php echo $user['role'] == 'student' ? 'selected' : ''; ?>>Student</option>
                                    <option value="parent" <?php echo $user['role'] == 'parent' ? 'selected' : ''; ?>>Parent</option>
                                </select>
                                <button type="submit" name="update_role" class="btn btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info" 
                                    onclick="showPermissions('<?php echo $user['role']; ?>')">
                                View Permissions
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Permissions Modal -->
<div class="modal fade" id="permissionsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Role Permissions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="permissionsList">
                Loading...
            </div>
        </div>
    </div>
</div>

<script>
function showPermissions(role) {
    const permissions = {
        'admin': ['manage_users', 'manage_students', 'manage_teachers', 'manage_parents', 'view_reports', 'export_data', 'manage_settings'],
        'teacher': ['view_students', 'add_grades', 'view_own_classes'],
        'student': ['view_own_profile', 'view_own_grades'],
        'parent': ['view_children', 'view_children_grades']
    };
    
    const perms = permissions[role] || [];
    let html = '<h6>Role: ' + role.charAt(0).toUpperCase() + role.slice(1) + '</h6>';
    html += '<ul class="list-group mt-3">';
    
    perms.forEach(function(perm) {
        html += '<li class="list-group-item">' + perm.replace(/_/g, ' ') + '</li>';
    });
    
    html += '</ul>';
    
    document.getElementById('permissionsList').innerHTML = html;
    
    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('permissionsModal'));
    modal.show();
}
</script>

<?php include '../app/views/layout/footer.php'; ?>