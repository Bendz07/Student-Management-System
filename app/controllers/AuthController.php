<?php
require_once APP_ROOT . '/app/models/User.php';

class AuthController {
    private $user;

    public function __construct() {
        $this->user = new User();
    }

    public function login($username, $password) {
        if ($this->user->login($username, $password)) {
            $_SESSION['user_id'] = $this->user->id;
            $_SESSION['username'] = $this->user->username;
            $_SESSION['user_role'] = $this->user->role;
            return true;
        }
        return false;
    }

    public function logout() {
        session_unset();
        session_destroy();
        return true;
    }

    public function register($data) {
        $this->user->username = $data['username'];
        $this->user->email = $data['email'];
        $this->user->password = $data['password'];
        $this->user->role = 'user';
        
        return $this->user->create();
    }

    public function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }
}
?>