<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Debugging: Check what's being submitted
error_log(print_r($_POST, true));
error_log(print_r($_FILES, true));

// Sanitize inputs
$name = sanitizeInput($_POST['name']);
$email = sanitizeInput($_POST['email']);
$comment = sanitizeInput($_POST['comment']);

// Initialize media paths
$photoPath = null;
$videoPath = null;

// Handle photo upload if provided
if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $photoUpload = handleFileUpload($_FILES['photo'], 'photo');
    if (!$photoUpload['success']) {
        $_SESSION['error'] = 'Photo upload failed: ' . $photoUpload['message'];
        header('Location: index.php');
        exit;
    }
    $photoPath = $photoUpload['path'];
}

// Handle video upload if provided
if (!empty($_FILES['video']['name']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
    $videoUpload = handleFileUpload($_FILES['video'], 'video');
    if (!$videoUpload['success']) {
        // Clean up already uploaded photo if video fails
        if ($photoPath && file_exists($photoPath)) {
            unlink($photoPath);
        }
        $_SESSION['error'] = 'Video upload failed: ' . $videoUpload['message'];
        header('Location: index.php');
        exit;
    }
    $videoPath = $videoUpload['path'];
}

// Insert feedback into database
try {
    $stmt = $pdo->prepare("INSERT INTO feedback (name, email, comment, photo_path, video_path, status) 
                          VALUES (:name, :email, :comment, :photo_path, :video_path, 'pending')");
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':comment' => $comment,
        ':photo_path' => $photoPath,
        ':video_path' => $videoPath
    ]);
    
    header('Location: thankyou.php');
    exit;
} catch (PDOException $e) {
    // Clean up uploaded files if DB insert fails
    if ($photoPath && file_exists($photoPath)) unlink($photoPath);
    if ($videoPath && file_exists($videoPath)) unlink($videoPath);
    
    $_SESSION['error'] = 'Failed to submit feedback. Please try again. Error: ' . $e->getMessage();
    header('Location: index.php');
    exit;
}