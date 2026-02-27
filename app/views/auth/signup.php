<?php
// Define APP_ROOT if not defined
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(dirname(dirname(__DIR__))));
}

// Load configuration
require_once APP_ROOT . '/config/config.php';
?>
<?php include APP_ROOT . '/app/views/layout/header.php'; ?>
<?php include APP_ROOT . '/app/views/layout/navbar.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="fas fa-user-plus"></i> 
                    <?php echo $lang['signup'] ?? 'Sign Up'; ?>
                </h4>
            </div>
            <div class="card-body">
                <?php if(isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach($_SESSION['errors'] as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php 
                    unset($_SESSION['errors']);
                endif; 
                ?>

                <?php
                $form_data = $_SESSION['form_data'] ?? [];
                unset($_SESSION['form_data']);
                ?>

                <form action="<?php echo APP_URL; ?>/signup.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">
                            <?php echo $lang['username'] ?? 'Username'; ?> *
                        </label>
                        <input type="text" class="form-control" id="username" name="username" 
                               value="<?php echo htmlspecialchars($form_data['username'] ?? ''); ?>" 
                               required minlength="3" pattern="[a-zA-Z0-9_]+">
                        <small class="text-muted">Username must be at least 3 characters and can only contain letters, numbers, and underscores</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <?php echo $lang['email'] ?? 'Email'; ?> *
                        </label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <?php echo $lang['password'] ?? 'Password'; ?> *
                        </label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="6">
                        <small class="text-muted"><?php echo $lang['password_hint'] ?? 'Minimum 6 characters'; ?></small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">
                            <?php echo $lang['confirm_password'] ?? 'Confirm Password'; ?> *
                        </label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" name="signup" class="btn btn-success">
                            <i class="fas fa-user-plus"></i> 
                            <?php echo $lang['signup'] ?? 'Sign Up'; ?>
                        </button>
                    </div>
                    
                    <div class="text-center mt-3">
                        <p>
                            <?php echo $lang['already_have_account'] ?? 'Already have an account?'; ?> 
                            <a href="<?php echo APP_URL; ?>/login.php">
                                <?php echo $lang['login'] ?? 'Login'; ?>
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include APP_ROOT . '/app/views/layout/footer.php'; ?>