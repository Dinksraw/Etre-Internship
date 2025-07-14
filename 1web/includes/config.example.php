<?php
// Example Database Configuration
// Copy this file or set environment variables in production

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'your_db_user');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'your_db_password');
define('DB_NAME', getenv('DB_NAME') ?: 'b7_39643211_etre_feedback');

// File upload configuration
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_PHOTO_TYPES', ['image/jpeg', 'image/png', 'image/gif']);
define('ALLOWED_VIDEO_TYPES', ['video/mp4', 'video/webm']);
define('PHOTO_UPLOAD_DIR', __DIR__ . '/../assets/uploads/photos/');
define('VIDEO_UPLOAD_DIR', __DIR__ . '/../assets/uploads/videos/');

if (!file_exists(PHOTO_UPLOAD_DIR)) {
    mkdir(PHOTO_UPLOAD_DIR, 0755, true);
}
if (!file_exists(VIDEO_UPLOAD_DIR)) {
    mkdir(VIDEO_UPLOAD_DIR, 0755, true);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
