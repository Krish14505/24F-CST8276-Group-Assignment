<?php include('header.php'); ?>
<?php include('server/signup.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="css/signinup.css">
</head>
<body>
    <div class="contentForm">
        <h2>Sign Up</h2>

        <!-- Display error message -->
        <?php if (!empty($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form action="signup_page.php" method="POST">
            <div class="inputField">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br>
            </div>
            <div class="inputField">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br>
            </div>
            <div class="inputField">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br>
            </div>
            <div class="button">
                <button type="submit">Sign Up</button>
            </div>
        </form>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
