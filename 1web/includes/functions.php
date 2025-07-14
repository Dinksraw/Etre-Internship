<?php
require_once 'config.php';

function sanitizeInput($data) {
    return htmlspecialchars(stripcslashes(trim($data)));
}

function handleFileUpload($file, $type = 'photo') {
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [
            'success' => false,
            'message' => 'Upload error: ' . getUploadError($file['error'])
        ];
    }

    // Verify file size
    if ($file['size'] > MAX_FILE_SIZE) {
        return [
            'success' => false,
            'message' => 'File exceeds maximum size of ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB'
        ];
    }

    // Verify file type
    $fileType = mime_content_type($file['tmp_name']);
    $allowedTypes = $type === 'photo' ? ALLOWED_PHOTO_TYPES : ALLOWED_VIDEO_TYPES;
    
    if (!in_array($fileType, $allowedTypes)) {
        return [
            'success' => false,
            'message' => 'Invalid file type. Allowed: ' . implode(', ', $allowedTypes)
        ];
    }

    // Create upload directory if it doesn't exist
    $uploadDir = $type === 'photo' ? PHOTO_UPLOAD_DIR : VIDEO_UPLOAD_DIR;
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . strtolower($extension);
    $destination = $uploadDir . $filename;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return [
            'success' => false,
            'message' => 'Failed to move uploaded file'
        ];
    }

    return [
        'success' => true,
        'path' => $destination
    ];
}

function getUploadError($errorCode) {
    $errors = [
        UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive in php.ini',
        UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive in HTML form',
        UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION => 'File upload stopped by PHP extension'
    ];
    return $errors[$errorCode] ?? 'Unknown upload error';
}

function getFeedbackItems($status = 'approved', $limit = null) {
    global $pdo;
    
    try {
        $sql = "SELECT * FROM feedback WHERE status = :status ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT :limit";
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        
        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error in getFeedbackItems: " . $e->getMessage());
        return [];
    }
}
?>