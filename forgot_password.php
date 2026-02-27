<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    
    require_once APP_ROOT . '/app/models/User.php';
    $user = new User();
    
    if ($user->emailExists($email)) {
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Save token to database
        $user->saveResetToken($email, $token, $expires);
        
        // Send reset email
        $resetLink = APP_URL . "/reset_password.php?token=" . $token;
        
        // In a real application, you would send an email here
        // For demo, we'll show the link
        $_SESSION['reset_link'] = $resetLink;
        $_SESSION['success'] = "Password reset link generated. Check your email.";
    } else {
        $_SESSION['error'] = "Email not found in our system.";
    }
}
?>
<?php include APP_ROOT . '/app/views/layout/header.php'; ?>
<?php include APP_ROOT . '/app/views/layout/navbar.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning">
                <h4 class="mb-0">Forgot Password</h4>
            </div>
            <div class="card-body">
                <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                
                <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <?php if(isset($_SESSION['reset_link'])): ?>
                        <br><a href="<?php echo $_SESSION['reset_link']; unset($_SESSION['reset_link']); ?>" class="btn btn-sm btn-primary mt-2">Click here to reset password</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-warning">Send Reset Link</button>
                    <a href="login.php" class="btn btn-secondary">Back to Login</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include APP_ROOT . '/app/views/layout/footer.php'; ?>