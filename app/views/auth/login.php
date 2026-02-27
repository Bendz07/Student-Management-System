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
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-sign-in-alt"></i> 
                    <?php echo $lang['login'] ?? 'Login'; ?>
                </h4>
            </div>
            <div class="card-body">
                <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <form action="<?php echo APP_URL; ?>/login.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">
                            <?php echo $lang['username'] ?? 'Username'; ?>
                        </label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <?php echo $lang['password'] ?? 'Password'; ?>
                        </label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" name="login" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i> 
                            <?php echo $lang['login'] ?? 'Login'; ?>
                        </button>
                    </div>
                    
                    <div class="text-center mt-3">
                        <p>
                            <?php echo $lang['dont_have_account'] ?? "Don't have an account?"; ?> 
                            <a href="<?php echo APP_URL; ?>/signup.php">
                                <?php echo $lang['signup'] ?? 'Sign Up'; ?>
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include APP_ROOT . '/app/views/layout/footer.php'; ?>