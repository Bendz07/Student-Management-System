<?php
// Define APP_ROOT if not defined
if (!defined('APP_ROOT')) {
    define('APP_ROOT', __DIR__);
}

require_once APP_ROOT . '/config/config.php';
require_once APP_ROOT . '/app/controllers/AuthController.php';

$auth = new AuthController();

// Redirect if already logged in
if ($auth->isAuthenticated()) {
    header("Location: " . APP_URL . "/index.php");
    exit();
}

$errors = [];
$form_data = [];

// Handle signup form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Store form data for repopulating
    $form_data = [
        'username' => $username,
        'email' => $email
    ];
    
    // Validation
    // Username validation
    if (empty($username)) {
        $errors[] = $lang['username_required'] ?? 'Username is required';
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = "Username can only contain letters, numbers, and underscores";
    }
    
    // Email validation
    if (empty($email)) {
        $errors[] = $lang['email_required'] ?? 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = $lang['email_invalid'] ?? 'Please enter a valid email address';
    }
    
    // Password validation
    if (empty($password)) {
        $errors[] = $lang['password_required'] ?? 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors[] = $lang['password_min_length'] ?? 'Password must be at least 6 characters';
    }
    
    // Confirm password validation
    if ($password !== $confirm_password) {
        $errors[] = $lang['password_mismatch'] ?? 'Passwords do not match';
    }
    
    // If no validation errors, try to create user
    if (empty($errors)) {
        require_once APP_ROOT . '/app/models/User.php';
        $user = new User();
        
        // Check if username exists
        if ($user->usernameExists($username)) {
            $errors[] = $lang['username_exists'] ?? 'Username already exists';
        }
        
        // Check if email exists
        if ($user->emailExists($email)) {
            $errors[] = $lang['email_exists'] ?? 'Email already exists';
        }
        
        // If still no errors, create the user
        if (empty($errors)) {
            $user->username = $username;
            $user->email = $email;
            $user->password = $password;
            $user->role = 'user'; // Default role
            
            // Try to create user
            if ($user->create()) {
                $_SESSION['success'] = $lang['registration_success'] ?? 'Registration successful! Please login.';
                header("Location: " . APP_URL . "/login.php");
                exit();
            } else {
                // Get error from user model
                $error_msg = $user->getError();
                if (!empty($error_msg)) {
                    $errors[] = $error_msg;
                } else {
                    $errors[] = $lang['registration_failed'] ?? 'Registration failed. Please try again.';
                }
            }
        }
    }
    
    // If there are errors, store them in session
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $form_data;
    }
}

// Include the signup view
include APP_ROOT . '/app/views/auth/signup.php';
?>