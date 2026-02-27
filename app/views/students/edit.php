<?php
// Define APP_ROOT if not defined
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(dirname(dirname(__DIR__))));
}

// Load configuration directly
require_once APP_ROOT . '/config/config.php';
require_once APP_ROOT . '/middleware/auth.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header("Location: " . APP_URL . "/login.php");
    exit();
}

require_once APP_ROOT . '/app/controllers/StudentController.php';
$studentController = new StudentController();

if(!isset($_GET['id'])) {
    $_SESSION['error'] = $lang['student_not_found'] ?? 'Student not found!';
    header("Location: index.php");
    exit();
}

$student = $studentController->edit($_GET['id']);
if(!$student) {
    $_SESSION['error'] = $lang['student_not_found'] ?? 'Student not found!';
    header("Location: index.php");
    exit();
}
?>
<?php include APP_ROOT . '/app/views/layout/header.php'; ?>
<?php include APP_ROOT . '/app/views/layout/navbar.php'; ?>

<!-- Rest of your students/edit.php code remains the same -->
<div class="row">
    <div class="col-md-12">
        <h2><i class="fas fa-edit"></i> <?php echo $lang['edit_student'] ?? 'Edit Student'; ?></h2>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><?php echo $lang['edit_student_information'] ?? 'Edit Student Information'; ?></h5>
            </div>
            <div class="card-body">
                <form action="<?php echo APP_URL; ?>/app/controllers/process_student.php" method="POST">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label"><?php echo $lang['name'] ?? 'Name'; ?> *</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?php echo htmlspecialchars($student['name']); ?>" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label"><?php echo $lang['email'] ?? 'Email'; ?> *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($student['email']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label"><?php echo $lang['phone'] ?? 'Phone'; ?></label>
                            <input type="text" class="form-control" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($student['phone']); ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label"><?php echo $lang['address'] ?? 'Address'; ?></label>
                        <textarea class="form-control" id="address" name="address" rows="2"><?php echo htmlspecialchars($student['address']); ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="birth_date" class="form-label"><?php echo $lang['birth_date'] ?? 'Birth Date'; ?></label>
                            <input type="date" class="form-control" id="birth_date" name="birth_date" 
                                   value="<?php echo $student['birth_date']; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label"><?php echo $lang['gender'] ?? 'Gender'; ?></label>
                            <select class="form-control" id="gender" name="gender">
                                <option value="male" <?php echo ($student['gender'] == 'male') ? 'selected' : ''; ?>>
                                    <?php echo $lang['male'] ?? 'Male'; ?>
                                </option>
                                <option value="female" <?php echo ($student['gender'] == 'female') ? 'selected' : ''; ?>>
                                    <?php echo $lang['female'] ?? 'Female'; ?>
                                </option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="grade" class="form-label"><?php echo $lang['grade'] ?? 'Grade'; ?></label>
                        <input type="text" class="form-control" id="grade" name="grade" 
                               value="<?php echo htmlspecialchars($student['grade']); ?>">
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" name="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> <?php echo $lang['update'] ?? 'Update'; ?>
                        </button>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> <?php echo $lang['cancel'] ?? 'Cancel'; ?>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include APP_ROOT . '/app/views/layout/footer.php'; ?>