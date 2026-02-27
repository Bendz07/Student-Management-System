<?php
require_once '../../config/config.php';
require_once '../../middleware/auth.php';
requireLogin();

require_once APP_ROOT . '/app/controllers/StudentController.php';
require_once APP_ROOT . '/app/controllers/UploadController.php';

$studentId = $_GET['id'] ?? 0;
$studentController = new StudentController();
$student = $studentController->edit($studentId);

if (!$student) {
    $_SESSION['error'] = "Student not found";
    header("Location: index.php");
    exit();
}

$uploadController = new UploadController();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $result = $uploadController->uploadProfilePicture($_FILES['profile_picture'], $studentId);
    
    if ($result['success']) {
        // Update student record with new picture path
        $studentController->updateProfilePicture($studentId, $result['filepath']);
        $_SESSION['success'] = "Profile picture uploaded successfully";
        header("Location: edit.php?id=" . $studentId);
        exit();
    } else {
        $errors = $result['errors'];
    }
}
?>
<?php include '../layout/header.php'; ?>
<?php include '../layout/navbar.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Upload Profile Picture for <?php echo htmlspecialchars($student['name']); ?></h4>
            </div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <?php if (!empty($student['profile_picture'])): ?>
                <div class="text-center mb-4">
                    <img src="<?php echo APP_URL . '/' . $student['profile_picture']; ?>" 
                         alt="Current Profile Picture" 
                         class="img-thumbnail rounded-circle" 
                         style="width: 150px; height: 150px; object-fit: cover;">
                    <p class="mt-2">Current Picture</p>
                </div>
                <?php endif; ?>

                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="profile_picture" class="form-label">Choose Profile Picture</label>
                        <input type="file" class="form-control" id="profile_picture" name="profile_picture" 
                               accept="image/jpeg,image/png,image/gif" required>
                        <small class="text-muted">
                            Allowed formats: JPG, PNG, GIF. Max size: 5MB. Max dimensions: 2000x2000 pixels.
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <img id="preview" src="#" alt="Preview" style="display: none; max-width: 200px; max-height: 200px;" class="img-thumbnail">
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Upload Picture</button>
                        <a href="edit.php?id=<?php echo $studentId; ?>" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('profile_picture').addEventListener('change', function(e) {
    const preview = document.getElementById('preview');
    const file = e.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});
</script>

<?php include '../layout/footer.php'; ?>