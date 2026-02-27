-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2026 at 10:22 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_management`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `CleanupExpiredResets` ()   BEGIN
    DELETE FROM password_resets WHERE expires_at < NOW();
    UPDATE users SET reset_token = NULL, reset_expires = NULL 
    WHERE reset_expires < NOW();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetPaginatedStudents` (IN `page` INT, IN `per_page` INT, IN `search_term` VARCHAR(100))   BEGIN
    DECLARE offset_val INT;
    SET offset_val = (page - 1) * per_page;
    
    IF search_term IS NULL OR search_term = '' THEN
        SELECT * FROM students 
        ORDER BY created_at DESC 
        LIMIT per_page OFFSET offset_val;
        
        SELECT COUNT(*) as total FROM students;
    ELSE
        SET search_term = CONCAT('%', search_term, '%');
        SELECT * FROM students 
        WHERE name LIKE search_term OR email LIKE search_term
        ORDER BY created_at DESC 
        LIMIT per_page OFFSET offset_val;
        
        SELECT COUNT(*) as total FROM students 
        WHERE name LIKE search_term OR email LIKE search_term;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `active_students`
-- (See below for the actual view)
--
CREATE TABLE `active_students` (
`id` int(11)
,`name` varchar(100)
,`email` varchar(100)
,`phone` varchar(20)
,`address` text
,`birth_date` date
,`gender` enum('male','female')
,`grade` varchar(50)
,`profile_picture` varchar(255)
,`created_at` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `password_resets`
--
DELIMITER $$
CREATE TRIGGER `before_password_reset_insert` BEFORE INSERT ON `password_resets` FOR EACH ROW BEGIN
    -- Delete expired tokens for this email
    DELETE FROM password_resets 
    WHERE email = NEW.email AND expires_at < NOW();
    
    -- Update user table
    UPDATE users 
    SET reset_token = NEW.token, reset_expires = NEW.expires_at
    WHERE email = NEW.email;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `role` varchar(50) NOT NULL,
  `permission` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `role`, `permission`) VALUES
(8, 'admin', 'delete_data'),
(6, 'admin', 'export_data'),
(4, 'admin', 'manage_parents'),
(7, 'admin', 'manage_settings'),
(2, 'admin', 'manage_students'),
(3, 'admin', 'manage_teachers'),
(1, 'admin', 'manage_users'),
(9, 'admin', 'view_all'),
(5, 'admin', 'view_reports'),
(23, 'parent', 'contact_teachers'),
(20, 'parent', 'view_children'),
(22, 'parent', 'view_children_attendance'),
(21, 'parent', 'view_children_grades'),
(19, 'student', 'update_own_profile'),
(18, 'student', 'view_own_attendance'),
(17, 'student', 'view_own_grades'),
(16, 'student', 'view_own_profile'),
(14, 'teacher', 'add_attendance'),
(11, 'teacher', 'add_grades'),
(12, 'teacher', 'edit_grades'),
(13, 'teacher', 'view_own_classes'),
(15, 'teacher', 'view_reports'),
(10, 'teacher', 'view_students');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `grade` varchar(50) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `phone`, `address`, `birth_date`, `gender`, `grade`, `profile_picture`, `created_at`) VALUES
(1, 'أحمد محمد', 'ahmed@example.com', '0123456789', 'القاهرة، مصر', NULL, 'male', 'الصف العاشر', NULL, '2026-02-27 16:35:55'),
(2, 'سارة أحمد', 'sara@example.com', '0123456790', 'الإسكندرية، مصر', NULL, 'female', 'الصف الحادي عشر', NULL, '2026-02-27 16:35:55'),
(3, 'محمد علي', 'mohamed@example.com', '0123456791', 'الجيزة، مصر', NULL, 'male', 'الصف التاسع', NULL, '2026-02-27 16:35:55'),
(4, 'فاطمة عمر', 'fatma@example.com', '0123456792', 'المنصورة، مصر', NULL, 'female', 'الصف الثاني عشر', NULL, '2026-02-27 16:35:55'),
(5, 'يوسف حسن', 'youssef@example.com', '0123456793', 'أسوان، مصر', NULL, 'male', 'الصف العاشر', NULL, '2026-02-27 16:35:55'),
(6, 'Bennadji Abdelali', 'abdel.contact@gmail.com', '+213673331012', 'jhjh jhb jgvjh j kjh kh ', '2026-02-18', 'male', 'استاذ ', NULL, '2026-02-27 21:06:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','teacher','student','parent') DEFAULT 'student',
  `profile_picture` varchar(255) DEFAULT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `profile_picture`, `reset_token`, `reset_expires`, `last_login`, `created_at`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$YourHashedPasswordHere', 'admin', NULL, NULL, NULL, NULL, '2026-02-27 16:35:55'),
(2, 'teacher1', 'teacher1@example.com', '$2y$10$YourHashedPasswordHere', 'teacher', NULL, NULL, NULL, NULL, '2026-02-27 16:35:55'),
(3, 'teacher2', 'teacher2@example.com', '$2y$10$YourHashedPasswordHere', 'teacher', NULL, NULL, NULL, NULL, '2026-02-27 16:35:55'),
(4, 'parent1', 'parent1@example.com', '$2y$10$YourHashedPasswordHere', 'parent', NULL, NULL, NULL, NULL, '2026-02-27 16:35:55'),
(5, 'parent2', 'parent2@example.com', '$2y$10$YourHashedPasswordHere', 'parent', NULL, NULL, NULL, NULL, '2026-02-27 16:35:55'),
(6, 'admin1', 'abdel.contact@gmail.com', '$2y$10$aBon7OGwgiT2LFsOU6TBoeWtAyaXb5Vmc83HRl/XyOazmN1jn17LW', '', NULL, NULL, NULL, NULL, '2026-02-27 20:34:49');

-- --------------------------------------------------------

--
-- Stand-in structure for view `user_statistics`
-- (See below for the actual view)
--
CREATE TABLE `user_statistics` (
`role` enum('admin','teacher','student','parent')
,`total` bigint(21)
,`active_last_week` bigint(21)
);

-- --------------------------------------------------------

--
-- Structure for view `active_students`
--
DROP TABLE IF EXISTS `active_students`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `active_students`  AS SELECT `students`.`id` AS `id`, `students`.`name` AS `name`, `students`.`email` AS `email`, `students`.`phone` AS `phone`, `students`.`address` AS `address`, `students`.`birth_date` AS `birth_date`, `students`.`gender` AS `gender`, `students`.`grade` AS `grade`, `students`.`profile_picture` AS `profile_picture`, `students`.`created_at` AS `created_at` FROM `students` WHERE `students`.`created_at` >= current_timestamp() - interval 30 day ;

-- --------------------------------------------------------

--
-- Structure for view `user_statistics`
--
DROP TABLE IF EXISTS `user_statistics`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `user_statistics`  AS SELECT `users`.`role` AS `role`, count(0) AS `total`, count(case when `users`.`last_login` >= current_timestamp() - interval 7 day then 1 end) AS `active_last_week` FROM `users` GROUP BY `users`.`role` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `token` (`token`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_role_permission` (`role`,`permission`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_students_name` (`name`),
  ADD KEY `idx_students_email` (`email`),
  ADD KEY `idx_students_grade` (`grade`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_username` (`username`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `idx_users_role` (`role`),
  ADD KEY `idx_users_last_login` (`last_login`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `cleanup_expired_tokens` ON SCHEDULE EVERY 1 HOUR STARTS '2026-02-27 17:35:55' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    CALL CleanupExpiredResets();
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
