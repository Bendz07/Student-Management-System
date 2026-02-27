<?php
require_once 'config/config.php';

$token = $_GET['token'] ?? '';

if (empty($token)) {
    header("Location: login.php");
    exit();
}

require_once APP_ROOT . '/app/models/User.php';
$user = new User();

// Verify token
$resetData = $user->verifyResetToken($token);

if (!$resetData) {
    $_SESSION['error'] = "Invalid or expired reset token.";
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    $errors = [];
    
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    if (empty($errors)) {
        if ($user->updatePassword($resetData['email'], $password)) {
            $user->deleteResetToken($token);
            $_SESSION['success'] = "Password updated successfully. Please login.";
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Failed to update password";
        }
    }
    
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
    }
}
?>
<?php include APP_ROOT . '/app/views/layout/header.php'; ?>
<?php include APP_ROOT . '/app/views/layout/navbar.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Reset Password</h4>
            </div>
            <div class="card-body">
                <?php if(isset($_SESSION['errors'])): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach($_SESSION['errors'] as $error): ?>
                        <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errors']); endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="8">
                        <small class="text-muted">Minimum 8 characters with at least one uppercase, one lowercase, one number, and one special character</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include APP_ROOT . '/app/views/layout/footer.php'; ?>