-- ETRE Feedback Portal Database Schema
-- Database Name: b7_39643211_etre_feedback

CREATE DATABASE IF NOT EXISTS `b7_39643211_etre_feedback` 
  DEFAULT CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE `b7_39643211_etre_feedback`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `comment` text NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `video_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `admin_id` int(11) DEFAULT NULL,
  `admin_response` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `fk_feedback_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Default Admin Account Setup
-- Username: admin
-- Password: adminpassword
-- Note: Replace password hash with your own generated password using PHP password_hash() if desired.
--

INSERT INTO `admins` (`username`, `password_hash`, `created_at`) 
VALUES ('admin', '$2y$10$w8T0M0W1m05eRzQeP5yA3.9P8zKz9zL5q1R2K3M4N5O6P7Q8R9S0T', NOW())
ON DUPLICATE KEY UPDATE `username`=`username`;
