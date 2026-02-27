<?php
class UploadController {
    private $uploadDir;
    private $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    private $maxFileSize = 5242880; // 5MB
    
    public function __construct() {
        $this->uploadDir = APP_ROOT . '/uploads/profile_pictures/';
        
        // Create directory if it doesn't exist
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }
    
    public function uploadProfilePicture($file, $studentId) {
        $errors = [];
        
        // Check if file was uploaded
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "No file uploaded or upload error occurred";
            return ['success' => false, 'errors' => $errors];
        }
        
        // Check file size
        if ($file['size'] > $this->maxFileSize) {
            $errors[] = "File size must be less than 5MB";
        }
        
        // Check file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $this->allowedTypes)) {
            $errors[] = "Only JPG, PNG, and GIF images are allowed";
        }
        
        // Check image dimensions
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo) {
            $width = $imageInfo[0];
            $height = $imageInfo[1];
            
            if ($width > 2000 || $height > 2000) {
                $errors[] = "Image dimensions must be less than 2000x2000 pixels";
            }
        }
        
        // If no errors, process upload
        if (empty($errors)) {
            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'student_' . $studentId . '_' . time() . '.' . $extension;
            $filepath = $this->uploadDir . $filename;
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                // Resize image if needed
                $this->resizeImage($filepath, 300, 300);
                
                return [
                    'success' => true,
                    'filename' => $filename,
                    'filepath' => 'uploads/profile_pictures/' . $filename
                ];
            } else {
                $errors[] = "Failed to move uploaded file";
            }
        }
        
        return ['success' => false, 'errors' => $errors];
    }
    
    private function resizeImage($filepath, $maxWidth, $maxHeight) {
        list($width, $height) = getimagesize($filepath);
        
        // Calculate new dimensions
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = $width * $ratio;
        $newHeight = $height * $ratio;
        
        // Create image resource based on type
        $extension = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $src = imagecreatefromjpeg($filepath);
                break;
            case 'png':
                $src = imagecreatefrompng($filepath);
                break;
            case 'gif':
                $src = imagecreatefromgif($filepath);
                break;
            default:
                return false;
        }
        
        // Create new image
        $dst = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG
        if ($extension == 'png') {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
            imagefilledrectangle($dst, 0, 0, $newWidth, $newHeight, $transparent);
        }
        
        // Resize
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        // Save
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($dst, $filepath, 90);
                break;
            case 'png':
                imagepng($dst, $filepath, 9);
                break;
            case 'gif':
                imagegif($dst, $filepath);
                break;
        }
        
        imagedestroy($src);
        imagedestroy($dst);
        
        return true;
    }
    
    public function deleteProfilePicture($filename) {
        $filepath = $this->uploadDir . $filename;
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        return false;
    }
}
?>