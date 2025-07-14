<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

if (!isAdminLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Handle feedback actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve'])) {
        $feedbackId = (int)$_POST['feedback_id'];
        $stmt = $pdo->prepare("UPDATE feedback SET status = 'approved' WHERE id = :id");
        $stmt->execute([':id' => $feedbackId]);
    } elseif (isset($_POST['reject'])) {
        $feedbackId = (int)$_POST['feedback_id'];
        $stmt = $pdo->prepare("UPDATE feedback SET status = 'rejected' WHERE id = :id");
        $stmt->execute([':id' => $feedbackId]);
    } elseif (isset($_POST['delete'])) {
        $feedbackId = (int)$_POST['feedback_id'];
        
        // First get the feedback to delete any associated files
        $stmt = $pdo->prepare("SELECT photo_path, video_path FROM feedback WHERE id = :id");
        $stmt->execute([':id' => $feedbackId]);
        $feedback = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Delete files if they exist
        if ($feedback['photo_path'] && file_exists($feedback['photo_path'])) {
            unlink($feedback['photo_path']);
        }
        if ($feedback['video_path'] && file_exists($feedback['video_path'])) {
            unlink($feedback['video_path']);
        }
        
        // Delete from database
        $stmt = $pdo->prepare("DELETE FROM feedback WHERE id = :id");
        $stmt->execute([':id' => $feedbackId]);
    }
}

// Add this to your admin dashboard
$pendingFeedback = $pdo->query("
    SELECT f.*, a.username as admin_name 
    FROM feedback f
    LEFT JOIN admins a ON f.admin_id = a.id
    WHERE f.admin_response IS NULL
    ORDER BY f.created_at DESC
")->fetchAll();

// Get all feedback for dashboard
$stmt = $pdo->prepare("SELECT * FROM feedback ORDER BY created_at DESC");
$stmt->execute();
$allFeedback = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ETRE Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1>ETRE Feedback Dashboard</h1>
            <nav>
                <span>Welcome, <?php echo $_SESSION['admin_username']; ?></span>
                <a href="logout.php" class="btn btn-logout">Logout</a>
                <a href="register.php" class="btn btn-logout">Add New Admin</a>
            </nav>
        </div>
    </header>

       <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Admin Users</h2>
        </div>
        <div class="admin-list">
            <?php
            $admins = $pdo->query("SELECT * FROM admins ORDER BY username")->fetchAll();
            foreach ($admins as $admin): ?>
                <div class="admin-item">
                    <span><?php echo htmlspecialchars($admin['username']); ?></span>
                    <small>Created: <?php echo date('M j, Y', strtotime($admin['created_at'])); ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>


    <main class="container">
        <section class="feedback-management">
            <h2>Feedback Management</h2>
            
            <div class="feedback-filters">
                <a href="?status=all" class="btn">All Feedback</a>
                <a href="?status=pending" class="btn">Pending</a>
                <a href="?status=approved" class="btn">Approved</a>
                <a href="?status=rejected" class="btn">Rejected</a>
            </div>
            
            <div class="feedback-list">
                <?php if (empty($allFeedback)): ?>
                    <p>No feedback submissions yet.</p>
                <?php else: ?>
                    <?php foreach ($allFeedback as $feedback): ?>
                        <div class="feedback-item status-<?php echo $feedback['status']; ?>">
                            <div class="feedback-header">
                                <h3><?php echo htmlspecialchars($feedback['name']); ?></h3>
                                <span class="email"><?php echo htmlspecialchars($feedback['email']); ?></span>
                                <span class="date"><?php echo date('M j, Y H:i', strtotime($feedback['created_at'])); ?></span>
                                <span class="status"><?php echo ucfirst($feedback['status']); ?></span>
                            </div>
                            
                            <div class="feedback-content">
                                <p><?php echo nl2br(htmlspecialchars($feedback['comment'])); ?></p>
                                
                                <?php if ($feedback['photo_path']): ?>
                                    <div class="media-preview">
                                        <img src="../<?php echo htmlspecialchars($feedback['photo_path']); ?>" alt="Attached photo">
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($feedback['video_path']): ?>
                                    <div class="media-preview">
                                        <video controls>
                                            <source src="../<?php echo htmlspecialchars($feedback['video_path']); ?>" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="feedback-actions">
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="feedback_id" value="<?php echo $feedback['id']; ?>">
                                    <?php if ($feedback['status'] !== 'approved'): ?>
                                        <button type="submit" name="approve" class="btn btn-approve">Approve</button>
                                    <?php endif; ?>
                                    <?php if ($feedback['status'] !== 'rejected'): ?>
                                        <button type="submit" name="reject" class="btn btn-reject">Reject</button>
                                    <?php endif; ?>
                                    <button type="submit" name="delete" class="btn btn-delete">Delete</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>
</html>