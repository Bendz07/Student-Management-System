Student Management System
A comprehensive PHP/MySQL web application for managing student records with multi-language support, user authentication, and role-based permissions.

📋 Features
Core Features
User Authentication - Secure login/logout system with password hashing

Multi-language Support - English and Arabic with RTL layout support

Dashboard - Statistics overview with charts and recent students

Student Management - Full CRUD operations with pagination and search

Form Validation - Real-time client-side and server-side validation

Password Reset - Email-based password recovery with secure tokens

Advanced Features
Export Data - Export student lists to Excel and PDF formats

Profile Pictures - Upload and resize student photos

Role-Based Permissions - Admin, teacher, student, and parent roles

Bulk Operations - Delete multiple students at once

Statistics - Gender distribution charts and grade analytics

🏗️ Project Structure
text
student-management-system/
│
├── 📁 assets/
│   └── js/
│       └── validation.js              # Client-side form validation
│
├── 📁 config/
│   ├── database.php                    # Database connection class
│   └── config.php                       # Main configuration file
│
├── 📁 middleware/
│   ├── auth.php                         # Authentication middleware
│   └── permission.php                    # Role-based permissions
│
├── 📁 app/
│   ├── 📁 controllers/
│   │   ├── AuthController.php            # Login/Logout/Signup logic
│   │   ├── StudentController.php          # Student CRUD + Pagination
│   │   └── UploadController.php            # Profile picture upload
│   │
│   ├── 📁 models/
│   │   ├── User.php                       # User model with password reset
│   │   ├── Student.php                     # Student model
│   │   └── Permission.php                   # Permissions model
│   │
│   └── 📁 views/
│       ├── 📁 layout/
│       │   ├── header.php                  # HTML head section
│       │   ├── navbar.php                   # Main navigation bar
│       │   └── footer.php                    # Closing tags and scripts
│       │
│       ├── 📁 auth/
│       │   ├── login.php                    # Login form
│       │   └── signup.php                    # Registration form
│       │
│       ├── 📁 dashboard/
│       │   └── index.php                     # Dashboard with statistics
│       │
│       └── 📁 students/
│           ├── index.php                     # Paginated students list
│           ├── create.php                    # Add new student
│           ├── edit.php                      # Edit student
│           └── upload_picture.php             # Profile picture upload
│
├── 📁 uploads/
│   ├── 📁 profile_pictures/                 # Student profile images
│   └── .htaccess                             # Security for uploads
│
├── 📁 lang/
│   ├── en.php                                # English translations
│   └── ar.php                                 # Arabic translations
│
├── 📁 admin/
│   └── manage_roles.php                      # User roles management
│
├── 📁 database/
│   ├── fresh_database.sql                    # Complete database schema
│   └── database_upgrade.sql                   # Upgrade existing DB
│
├── 📁 root files/
│   ├── index.php                              # Main entry point
│   ├── login.php                              # Login handler
│   ├── signup.php                             # Signup handler
│   ├── logout.php                             # Logout handler
│   ├── forgot_password.php                    # Password reset request
│   ├── reset_password.php                     # Password reset form
│   ├── export.php                             # Excel/PDF export
│   ├── test_db.php                            # Database connection test
│   └── setup_fresh_database.php               # Fresh database installer
│
└── README.md                                   # Project documentation
💾 Database Schema
Tables
users - User accounts with roles and reset tokens

students - Student records with profile pictures

password_resets - Tracks password reset requests

permissions - Role-based permissions

🚀 Installation
Requirements
PHP 7.4 or higher

MySQL 5.7 or higher

XAMPP/WAMP/LAMP server

Web browser

Setup Instructions
Clone the repository

bash
git clone https://github.com/yourusername/student-management-system.git
Move to XAMPP htdocs

bash
cd student-management-system
# Copy to C:\xampp\htdocs\student-management-system
Start MySQL in XAMPP Control Panel

Install database

Open browser and navigate to:

text
http://localhost/student-management-system/setup_fresh_database.php
Configure database connection (if needed)
Edit config/database.php with your credentials:

php
private $host = "localhost";
private $db_name = "student_management";
private $username = "root";
private $password = "";
Login with default credentials

text
Username: admin
Password: admin123
🎯 Usage
User Roles
Admin - Full system access, user management

Teacher - View students, add grades

Student - View own profile and grades

Parent - View children's information

Key Operations
Add Student - Navigate to Students → Add New Student

Search - Use search box on students page

Export - Click Excel/PDF buttons on dashboard

Change Language - Use language switcher in navbar

Manage Users - Admin panel at /admin/manage_roles.php

🌐 Multi-language Support
The system supports both English and Arabic with:

RTL layout for Arabic

Complete translation files

Language persistence in session

Easy language switching

📊 Dashboard Features
Statistics Cards - Total students, new this month, gender distribution

Recent Students - Latest 5 additions

Quick Actions - Add student, export data

Gender Chart - Visual representation using Chart.js

🔒 Security Features
Password hashing with bcrypt

Session-based authentication

Role-based access control

SQL injection prevention with PDO prepared statements

XSS protection with htmlspecialchars

Secure file upload validation

🤝 Contributing
Fork the repository

Create feature branch (git checkout -b feature/AmazingFeature)

Commit changes (git commit -m 'Add AmazingFeature')

Push to branch (git push origin feature/AmazingFeature)

Open a Pull Request

📝 License
This project is open-source and available under the MIT License.

📧 Contact
For support or inquiries, please open an issue on GitHub or contact the development team.

Made with ❤️ for educational institutions


