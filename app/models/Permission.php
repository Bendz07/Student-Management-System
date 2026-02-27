<?php
require_once APP_ROOT . '/config/database.php';

class Permission {
    private $conn;
    private $table = 'permissions';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function hasPermission($role, $permission) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " 
                  WHERE role = :role AND permission = :permission";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':permission', $permission);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
    
    public function getPermissionsByRole($role) {
        $query = "SELECT permission FROM " . $this->table . " WHERE role = :role";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
        
        $permissions = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $permissions[] = $row['permission'];
        }
        return $permissions;
    }
    
    public function addPermission($role, $permission) {
        $query = "INSERT INTO " . $this->table . " (role, permission) VALUES (:role, :permission)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':permission', $permission);
        return $stmt->execute();
    }
    
    public function removePermission($role, $permission) {
        $query = "DELETE FROM " . $this->table . " WHERE role = :role AND permission = :permission";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':permission', $permission);
        return $stmt->execute();
    }
}
?>