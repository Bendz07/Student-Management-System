<?php
// Define APP_ROOT if not defined
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__)); // This points to the app directory's parent
}

// Now safely include the model
require_once APP_ROOT . '/app/models/Student.php';

class StudentController {
    private $student;
    private $perPage = 10;

    public function __construct() {
        $this->student = new Student();
    }

    /**
     * Get all students (original method)
     */
    public function index() {
        return $this->student->readAll();
    }

    /**
     * Get paginated students with search
     * @param int $page Current page number
     * @param int $perPage Items per page
     * @param string $search Search term
     * @return PDOStatement
     */
    public function getPaginatedStudents($page = 1, $perPage = 10, $search = '') {
        $offset = ($page - 1) * $perPage;
        
        $query = "SELECT * FROM students ";
        
        if (!empty($search)) {
            $query .= "WHERE name LIKE :search OR email LIKE :search OR phone LIKE :search ";
        }
        
        $query .= "ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->student->getConnection()->prepare($query);
        
        if (!empty($search)) {
            $searchTerm = "%{$search}%";
            $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        }
        
        $stmt->bindParam(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }

    /**
     * Get total count of students (with search)
     * @param string $search Search term
     * @return int Total count
     */
    public function getTotalCount($search = '') {
        $query = "SELECT COUNT(*) as total FROM students ";
        
        if (!empty($search)) {
            $query .= "WHERE name LIKE :search OR email LIKE :search OR phone LIKE :search ";
            $stmt = $this->student->getConnection()->prepare($query);
            $searchTerm = "%{$search}%";
            $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        } else {
            $stmt = $this->student->getConnection()->prepare($query);
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }

    /**
     * Generate pagination HTML links
     * @param int $currentPage Current page number
     * @param int $totalPages Total number of pages
     * @param string $search Search term
     * @param string $baseUrl Base URL for pagination links
     * @return string HTML pagination links
     */
    public function generatePaginationLinks($currentPage, $totalPages, $search = '', $baseUrl = '') {
        if ($totalPages <= 1) {
            return '';
        }
        
        $links = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
        
        // Previous button
        if ($currentPage > 1) {
            $prevPage = $currentPage - 1;
            $links .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . $prevPage . '&search=' . urlencode($search) . '" aria-label="Previous">';
            $links .= '<span aria-hidden="true">&laquo; Previous</span></a></li>';
        } else {
            $links .= '<li class="page-item disabled"><span class="page-link" aria-label="Previous">';
            $links .= '<span aria-hidden="true">&laquo; Previous</span></span></li>';
        }
        
        // Page numbers
        $startPage = max(1, $currentPage - 2);
        $endPage = min($totalPages, $currentPage + 2);
        
        if ($startPage > 1) {
            $links .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=1&search=' . urlencode($search) . '">1</a></li>';
            if ($startPage > 2) {
                $links .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }
        
        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i == $currentPage) {
                $links .= '<li class="page-item active"><span class="page-link">' . $i . ' <span class="sr-only">(current)</span></span></li>';
            } else {
                $links .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . $i . '&search=' . urlencode($search) . '">' . $i . '</a></li>';
            }
        }
        
        if ($endPage < $totalPages) {
            if ($endPage < $totalPages - 1) {
                $links .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            $links .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . $totalPages . '&search=' . urlencode($search) . '">' . $totalPages . '</a></li>';
        }
        
        // Next button
        if ($currentPage < $totalPages) {
            $nextPage = $currentPage + 1;
            $links .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . $nextPage . '&search=' . urlencode($search) . '" aria-label="Next">';
            $links .= '<span aria-hidden="true">Next &raquo;</span></a></li>';
        } else {
            $links .= '<li class="page-item disabled"><span class="page-link" aria-label="Next">';
            $links .= '<span aria-hidden="true">Next &raquo;</span></span></li>';
        }
        
        $links .= '</ul></nav>';
        
        return $links;
    }

    /**
     * Create new student
     * @param array $data Student data
     * @return bool Success or failure
     */
    public function create($data) {
        // Validate required fields
        if (empty($data['name']) || empty($data['email'])) {
            $_SESSION['error'] = "Name and email are required fields.";
            return false;
        }
        
        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Invalid email format.";
            return false;
        }
        
        // Check if email already exists
        if ($this->student->emailExists($data['email'])) {
            $_SESSION['error'] = "Email already exists.";
            return false;
        }
        
        $this->student->name = $data['name'];
        $this->student->email = $data['email'];
        $this->student->phone = $data['phone'] ?? null;
        $this->student->address = $data['address'] ?? null;
        $this->student->birth_date = !empty($data['birth_date']) ? $data['birth_date'] : null;
        $this->student->gender = $data['gender'] ?? 'male';
        $this->student->grade = $data['grade'] ?? null;
        
        return $this->student->create();
    }

    /**
     * Get student by ID for editing
     * @param int $id Student ID
     * @return array|false Student data or false
     */
    public function edit($id) {
        $this->student->id = $id;
        return $this->student->readOne();
    }

    /**
     * Update student
     * @param int $id Student ID
     * @param array $data Updated student data
     * @return bool Success or failure
     */
    public function update($id, $data) {
        // Validate required fields
        if (empty($data['name']) || empty($data['email'])) {
            $_SESSION['error'] = "Name and email are required fields.";
            return false;
        }
        
        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Invalid email format.";
            return false;
        }
        
        // Check if email already exists for another student
        $existingStudent = $this->student->findByEmail($data['email']);
        if ($existingStudent && $existingStudent['id'] != $id) {
            $_SESSION['error'] = "Email already exists for another student.";
            return false;
        }
        
        $this->student->id = $id;
        $this->student->name = $data['name'];
        $this->student->email = $data['email'];
        $this->student->phone = $data['phone'] ?? null;
        $this->student->address = $data['address'] ?? null;
        $this->student->birth_date = !empty($data['birth_date']) ? $data['birth_date'] : null;
        $this->student->gender = $data['gender'] ?? 'male';
        $this->student->grade = $data['grade'] ?? null;
        
        return $this->student->update();
    }

    /**
     * Delete student
     * @param int $id Student ID
     * @return bool Success or failure
     */
    public function delete($id) {
        $this->student->id = $id;
        return $this->student->delete();
    }

    /**
     * Search students
     * @param string $keyword Search keyword
     * @return PDOStatement
     */
    public function search($keyword) {
        return $this->student->search($keyword);
    }

    /**
     * Update student profile picture
     * @param int $studentId Student ID
     * @param string $picturePath Path to profile picture
     * @return bool Success or failure
     */
    public function updateProfilePicture($studentId, $picturePath) {
        $query = "UPDATE students 
                  SET profile_picture = :picture 
                  WHERE id = :id";
        
        $stmt = $this->student->getConnection()->prepare($query);
        $stmt->bindParam(':picture', $picturePath);
        $stmt->bindParam(':id', $studentId);
        
        return $stmt->execute();
    }

    /**
     * Get students by grade
     * @param string $grade Grade level
     * @return PDOStatement
     */
    public function getStudentsByGrade($grade) {
        $query = "SELECT * FROM students 
                  WHERE grade = :grade 
                  ORDER BY name ASC";
        
        $stmt = $this->student->getConnection()->prepare($query);
        $stmt->bindParam(':grade', $grade);
        $stmt->execute();
        
        return $stmt;
    }

    /**
     * Get recent students
     * @param int $limit Number of students to return
     * @return PDOStatement
     */
    public function getRecentStudents($limit = 5) {
        $query = "SELECT * FROM students 
                  ORDER BY created_at DESC 
                  LIMIT :limit";
        
        $stmt = $this->student->getConnection()->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }

    /**
     * Export students data
     * @param string $format Export format (csv, excel, pdf)
     * @param string $search Search term for filtering
     * @return mixed Export data
     */
    public function exportStudents($format = 'csv', $search = '') {
        $query = "SELECT * FROM students ";
        
        if (!empty($search)) {
            $query .= "WHERE name LIKE :search OR email LIKE :search ";
            $stmt = $this->student->getConnection()->prepare($query);
            $searchTerm = "%{$search}%";
            $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        } else {
            $stmt = $this->student->getConnection()->prepare($query);
        }
        
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        switch ($format) {
            case 'csv':
                return $this->generateCSV($students);
            case 'excel':
                return $this->generateExcel($students);
            case 'pdf':
                return $this->generatePDF($students);
            default:
                return $students;
        }
    }

    /**
     * Generate CSV export
     * @param array $students Students data
     * @return string CSV content
     */
    private function generateCSV($students) {
        $output = fopen('php://temp', 'w');
        
        // Add headers
        fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'Gender', 'Grade', 'Created At']);
        
        // Add data
        foreach ($students as $student) {
            fputcsv($output, [
                $student['id'],
                $student['name'],
                $student['email'],
                $student['phone'],
                $student['gender'],
                $student['grade'],
                $student['created_at']
            ]);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }

    /**
     * Generate Excel export (HTML format for Excel)
     * @param array $students Students data
     * @return string HTML content
     */
    private function generateExcel($students) {
        $html = '<html>';
        $html .= '<head><meta charset="UTF-8"></head>';
        $html .= '<body>';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<th>ID</th>';
        $html .= '<th>Name</th>';
        $html .= '<th>Email</th>';
        $html .= '<th>Phone</th>';
        $html .= '<th>Gender</th>';
        $html .= '<th>Grade</th>';
        $html .= '<th>Created At</th>';
        $html .= '</tr>';
        
        foreach ($students as $student) {
            $html .= '<tr>';
            $html .= '<td>' . $student['id'] . '</td>';
            $html .= '<td>' . htmlspecialchars($student['name']) . '</td>';
            $html .= '<td>' . htmlspecialchars($student['email']) . '</td>';
            $html .= '<td>' . htmlspecialchars($student['phone']) . '</td>';
            $html .= '<td>' . ucfirst($student['gender']) . '</td>';
            $html .= '<td>' . htmlspecialchars($student['grade']) . '</td>';
            $html .= '<td>' . $student['created_at'] . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        $html .= '</body>';
        $html .= '</html>';
        
        return $html;
    }

    /**
     * Generate PDF export (simplified HTML for PDF conversion)
     * @param array $students Students data
     * @return string HTML content
     */
    private function generatePDF($students) {
        $html = '<!DOCTYPE html>';
        $html .= '<html>';
        $html .= '<head>';
        $html .= '<style>';
        $html .= 'body { font-family: Arial, sans-serif; }';
        $html .= 'h1 { color: #333; text-align: center; }';
        $html .= 'table { width: 100%; border-collapse: collapse; margin-top: 20px; }';
        $html .= 'th { background-color: #4CAF50; color: white; padding: 10px; text-align: left; }';
        $html .= 'td { padding: 8px; border-bottom: 1px solid #ddd; }';
        $html .= 'tr:hover { background-color: #f5f5f5; }';
        $html .= '.date { text-align: center; color: #666; margin-bottom: 20px; }';
        $html .= '</style>';
        $html .= '</head>';
        $html .= '<body>';
        $html .= '<h1>Students List</h1>';
        $html .= '<div class="date">Generated on: ' . date('Y-m-d H:i:s') . '</div>';
        $html .= '<table>';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>ID</th>';
        $html .= '<th>Name</th>';
        $html .= '<th>Email</th>';
        $html .= '<th>Phone</th>';
        $html .= '<th>Gender</th>';
        $html .= '<th>Grade</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        foreach ($students as $student) {
            $html .= '<tr>';
            $html .= '<td>' . $student['id'] . '</td>';
            $html .= '<td>' . htmlspecialchars($student['name']) . '</td>';
            $html .= '<td>' . htmlspecialchars($student['email']) . '</td>';
            $html .= '<td>' . htmlspecialchars($student['phone']) . '</td>';
            $html .= '<td>' . ucfirst($student['gender']) . '</td>';
            $html .= '<td>' . htmlspecialchars($student['grade']) . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</body>';
        $html .= '</html>';
        
        return $html;
    }

    /**
     * Get statistics about students
     * @return array Statistics
     */
    public function getStatistics() {
        $stats = [];
        
        // Total students
        $stats['total'] = $this->getTotalCount();
        
        // Gender distribution
        $query = "SELECT gender, COUNT(*) as count FROM students GROUP BY gender";
        $stmt = $this->student->getConnection()->prepare($query);
        $stmt->execute();
        $stats['gender'] = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $stats['gender'][$row['gender']] = $row['count'];
        }
        
        // Grade distribution
        $query = "SELECT grade, COUNT(*) as count FROM students GROUP BY grade ORDER BY grade";
        $stmt = $this->student->getConnection()->prepare($query);
        $stmt->execute();
        $stats['grades'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // New students this month
        $query = "SELECT COUNT(*) as count FROM students 
                  WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        $stmt = $this->student->getConnection()->prepare($query);
        $stmt->execute();
        $stats['new_this_month'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        return $stats;
    }

    /**
     * Bulk delete students
     * @param array $ids Array of student IDs
     * @return bool Success or failure
     */
    public function bulkDelete($ids) {
        if (empty($ids)) {
            return false;
        }
        
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "DELETE FROM students WHERE id IN ($placeholders)";
        
        $stmt = $this->student->getConnection()->prepare($query);
        return $stmt->execute($ids);
    }

    /**
     * Validate student data
     * @param array $data Student data
     * @return array Validation errors (empty if valid)
     */
    public function validate($data) {
        $errors = [];
        
        // Name validation
        if (empty($data['name'])) {
            $errors['name'] = "Name is required";
        } elseif (strlen($data['name']) < 3) {
            $errors['name'] = "Name must be at least 3 characters";
        } elseif (strlen($data['name']) > 100) {
            $errors['name'] = "Name must be less than 100 characters";
        }
        
        // Email validation
        if (empty($data['email'])) {
            $errors['email'] = "Email is required";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format";
        }
        
        // Phone validation (optional)
        if (!empty($data['phone']) && !preg_match('/^[0-9+\-\s]+$/', $data['phone'])) {
            $errors['phone'] = "Invalid phone number format";
        }
        
        // Birth date validation
        if (!empty($data['birth_date'])) {
            $date = DateTime::createFromFormat('Y-m-d', $data['birth_date']);
            if (!$date || $date->format('Y-m-d') !== $data['birth_date']) {
                $errors['birth_date'] = "Invalid date format";
            } elseif ($date > new DateTime()) {
                $errors['birth_date'] = "Birth date cannot be in the future";
            }
        }
        
        return $errors;
    }
}
?>