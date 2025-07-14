<?php if (!isset($hide_header)): ?>
<header class="admin-header">
    <div class="container">
        <h1>
            <i class="fas fa-cog"></i> ETRE Admin Panel
        </h1>
        <div class="admin-actions">
            <span class="welcome-msg">
                <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?>
            </span>
            <a href="dashboard.php" class="btn">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="logout.php" class="btn btn-logout">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</header>
<?php endif; ?><?php if (!isset($hide_header)): ?>
<header class="admin-header">
    <div class="container">
        <h1>
            <i class="fas fa-cog"></i> ETRE Admin Panel
        </h1>
        <div class="admin-actions">
            <span class="welcome-msg">
                <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?>
            </span>
            <a href="dashboard.php" class="btn">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="logout.php" class="btn btn-logout">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</header>
<?php endif; ?>