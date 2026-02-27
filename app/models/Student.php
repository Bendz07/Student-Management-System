<?php
// Check if APP_ROOT is defined
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

require_once APP_ROOT . '/config/database.php';

class Student {
    private $conn;
    private $table = 'students';

    public $id;
    public $name;
    public $email;
    public $phone;
    public $address;
    public $birth_date;
    public $gender;
    public $grade;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (name, email, phone, address, birth_date, gender, grade) 
                  VALUES (:name, :email, :phone, :address, :birth_date, :gender, :grade)";
        
        $stmt = $this->conn->prepare($query);
        
        // Bind data
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':birth_date', $this->birth_date);
        $stmt->bindParam(':gender', $this->gender);
        $stmt->bindParam(':grade', $this->grade);
        
        return $stmt->execute();
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
                  SET name = :name, email = :email, phone = :phone, 
                      address = :address, birth_date = :birth_date, 
                      gender = :gender, grade = :grade 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':birth_date', $this->birth_date);
        $stmt->bindParam(':gender', $this->gender);
        $stmt->bindParam(':grade', $this->grade);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function search($keyword) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE name LIKE :keyword OR email LIKE :keyword 
                  ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $keyword = "%{$keyword}%";
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt;
    }

    public function getTotalCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getRecentStudents($limit = 5) {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    // Add to app/models/Student.php
public function getConnection() {
    return $this->conn;
}

public function emailExists($email) {
    $query = "SELECT COUNT(*) as count FROM students WHERE email = :email";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'] > 0;
}

public function findByEmail($email) {
    $query = "SELECT * FROM students WHERE email = :email LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}





}
?>