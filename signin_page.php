<!-- signin.php -->

<?php include('header.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="css/signinup.css">
</head>
<body>
    <div class="contentForm">
        <h2 class="HeaderForm" style="padding: 10px;">Sign In</h2>
        
        <!-- Sign In Form -->
        <form action="server/signin.php" method="POST">

            <div class="inputField">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br>
            </div>
            <div class="inputField">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br>
            </div>

            <div class="button">
                <button type="submit">Log In</button>
            </div>
        </form>

        <p>Don't have an account? <a href="signup_page.php">Create an account</a></p>
    </div>

    <?php include('footer.php'); ?>
    <script src="js/location.js"></script>
</body>
</html>
