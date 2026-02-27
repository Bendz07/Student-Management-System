<?php
require_once APP_ROOT . '/config/database.php';

class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $username;
    public $email;
    public $password;
    public $role;
    public $created_at;
    private $error_message = '';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getError() {
        return $this->error_message;
    }

    public function login($username, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username OR email = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->username = $row['username'];
                $this->email = $row['email'];
                $this->role = $row['role'];
                return true;
            }
        }
        $this->error_message = "Invalid username or password";
        return false;
    }

    public function usernameExists($username) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    public function emailExists($email) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    public function create() {
        // Check if username already exists
        if ($this->usernameExists($this->username)) {
            $this->error_message = "Username already exists";
            return false;
        }
        
        // Check if email already exists
        if ($this->emailExists($this->email)) {
            $this->error_message = "Email already exists";
            return false;
        }
        
        $query = "INSERT INTO " . $this->table . " 
                  (username, email, password, role) 
                  VALUES (:username, :email, :password, :role)";
        
        $stmt = $this->conn->prepare($query);
        
        // Hash password
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
        
        // Bind data
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $this->role);
        
        if ($stmt->execute()) {
            return true;
        } else {
            $this->error_message = "Database error occurred";
            return false;
        }
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET username = :username, email = :email, role = :role 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function findByUsername($username) {
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Add these methods to your existing User class

public function saveResetToken($email, $token, $expires) {
    $query = "UPDATE " . $this->table . " 
              SET reset_token = :token, reset_expires = :expires 
              WHERE email = :email";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':expires', $expires);
    $stmt->bindParam(':email', $email);
    
    return $stmt->execute();
}

public function verifyResetToken($token) {
    $query = "SELECT email FROM " . $this->table . " 
              WHERE reset_token = :token AND reset_expires > NOW() 
              LIMIT 1";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function updatePassword($email, $password) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $query = "UPDATE " . $this->table . " 
              SET password = :password 
              WHERE email = :email";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':email', $email);
    
    return $stmt->execute();
}

public function deleteResetToken($token) {
    $query = "UPDATE " . $this->table . " 
              SET reset_token = NULL, reset_expires = NULL 
              WHERE reset_token = :token";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':token', $token);
    
    return $stmt->execute();
}

public function findById($id) {
    $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function updateLastLogin($userId) {
    $query = "UPDATE " . $this->table . " SET last_login = NOW() WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $userId);
    return $stmt->execute();
}
}
?>