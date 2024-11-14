<!-- signup.php -->

<?php include('header.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div id="header"></div>
    <div class="contentForm">
        <h2>Sign Up</h2>
    <form action="server/signup.php" method="POST">
        
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

    <script src="js/button_google.js"></script>
</body>
</html>
