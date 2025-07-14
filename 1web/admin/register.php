<?php
// Enable full error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include necessary files with absolute paths
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

// Verify admin authentication
if (!isAdminLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Initialize variables
$error = '';
$success = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm_password']);

    // Validate inputs
    if (empty($username) || empty($password)) {
        $error = 'Username and password are required';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters';
    } else {
        try {
            // Check if username exists
            $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = ?");
            $stmt->execute([$username]);
            
            if ($stmt->fetch()) {
                $error = 'Username already exists';
            } else {
                // Create password hash
                $hash = password_hash($password, PASSWORD_BCRYPT);
                if (!$hash) {
                    throw new Exception('Failed to generate password hash');
                }

                // Insert new admin
                $stmt = $pdo->prepare("INSERT INTO admins (username, password_hash, created_at) VALUES (?, ?, NOW())");
                $stmt->execute([$username, $hash]);

                // Verify insertion
                $newId = $pdo->lastInsertId();
                if (!$newId) {
                    throw new Exception('Failed to get new admin ID');
                }

                $success = 'Admin created successfully';
                $_SESSION['success'] = $success;
                header('Location: dashboard.php');
                exit;
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
            error_log("Admin registration error: " . $e->getMessage());
        } catch (Exception $e) {
            $error = $e->getMessage();
            error_log("Admin registration error: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
       <header class="admin-header">
        <div class="container">
            <h1>ETRE Admin registration Form</h1>
        </div>
    </header>

    
    <main class="container">
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title">
                    <i class="fas fa-user-plus"></i> Register New Admin
                </h2>
                <a href="dashboard.php" class="btn btn-cancel">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <form method="post" class="admin-form">
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i> Username
                    </label>
                    <input type="text" id="username" name="username" required 
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input type="password" id="password" name="password" required minlength="8">
                    <small>Minimum 8 characters</small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">
                        <i class="fas fa-lock"></i> Confirm Password
                    </label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Register Admin
                </button>
            </form>
        </div>
    </main>

    <script>
        // Client-side password validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;
            
            if (password !== confirm) {
                alert('Passwords do not match!');
                e.preventDefault();
            }
            
            if (password.length < 8) {
                alert('Password must be at least 8 characters!');
                e.preventDefault();
            }
        });
    </script>
</body>
</html>