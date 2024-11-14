<!-- header.php -->

<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Navigation</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="location.php">Location Sharing</a></li>
                <li><a href="routes.php">Routes</a></li>
                
                <!-- Check if the user is logged in -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- If logged in, show the username and link to dashboard -->
                    <li><a href="dashboard.php">Welcome, <?= htmlspecialchars($_SESSION['username']) ?></a></li>
                    <li><a href="server/logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="signin_page.php">Profile</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
</body>
</html>
