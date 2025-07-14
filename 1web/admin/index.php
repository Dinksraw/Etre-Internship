<?php
// Start session and include necessary files
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Check if admin is already logged in
if (isAdminLoggedIn()) {
    // Redirect to dashboard if logged in
    header('Location: dashboard.php');
} else {
    // Redirect to login page if not logged in
    header('Location: login.php');
}

// Ensure no further code is executed
exit;
?><?php
// Start session and include necessary files
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Check if admin is already logged in
if (isAdminLoggedIn()) {
    // Redirect to dashboard if logged in
    header('Location: dashboard.php');
} else {
    // Redirect to login page if not logged in
    header('Location: login.php');
}

// Ensure no further code is executed
exit;
?>