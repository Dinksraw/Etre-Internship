<?php
require_once __DIR__ . '/includes/config.php';

try {
    // $pdo is initialized in config.php
    $stmt = $pdo->query("SELECT VERSION() as version");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Connected successfully to MySQL! Database Version: " . htmlspecialchars($result['version']);
} catch (PDOException $e) {
    die("Connection failed: " . htmlspecialchars($e->getMessage()));
}
?>