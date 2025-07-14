<?php require_once 'includes/config.php'; require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ETRE Feedback</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
    <header>
    <div class="container" style="display: flex; align-items: center; gap: 10px;">
        <img src="assets/images/logos.png" alt="ETRE Logo"
             style="height: 80px; background-color: #15064dff; padding: 5px; border-radius: 200px;">
        <div>
            <h1>ETRE Feedback System</h1>
            <p>Share your thoughts and suggestions with us</p>
        </div>
    </header>

    <main class="container">
        <section class="feedback-form">
            <h2>Submit Your Feedback</h2>
            <form action="submit-feedback.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="comment">Your Feedback:</label>
                    <textarea id="comment" name="comment" rows="5" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="photo">Upload Photo (optional):</label>
                    <input type="file" id="photo" name="photo" accept="image/*">
                </div>
                
                <div class="form-group">
                    <label for="video">Upload Video (optional):</label>
                    <input type="file" id="video" name="video" accept="video/*">
                </div>
                
                <button type="submit" class="btn">Submit Feedback</button>
            </form>
        </section>
        
        <section class="recent-feedback">
            <h2>Recent Feedback</h2>
            <div class="feedback-list">
                <?php
                $feedbacks = getFeedbackItems('approved', 5);
                if (empty($feedbacks)) {
                    echo '<p>No feedback yet. Be the first to share!</p>';
                } else {
                    foreach ($feedbacks as $feedback) {
                        echo '<div class="feedback-item">';
                        echo '<h3>' . htmlspecialchars($feedback['name']) . '</h3>';
                        echo '<p class="date">' . date('M j, Y', strtotime($feedback['created_at'])) . '</p>';
                        echo '<p>' . nl2br(htmlspecialchars($feedback['comment'])) . '</p>';
                        
                        if ($feedback['photo_path']) {
                            echo '<div class="media-preview">';
                            echo '<img src="' . htmlspecialchars($feedback['photo_path']) . '" alt="Attached photo">';
                            echo '</div>';
                        }
                         
                      if (!empty($feedback['video_path'])) {
    echo '<div class="media-preview">';
    echo '<video controls width="100%">';
    echo '<source src="' . htmlspecialchars($feedback['video_path']) . '" type="video/mp4">';
    echo 'Your browser does not support HTML5 video.';
    echo '</video>';
    echo '</div>';
}
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </section>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> ETRE Highway. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>