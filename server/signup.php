<?php
require_once('../database/database.php');
require_once('../database/db_credentials.php');

// Handle form submission when the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs to prevent injection attacks
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password for security

    // Connect to the database
    $db = db_connect();

    $sql = "INSERT INTO Users (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);

    // Execute the query and check if the user is successfully inserted
    if (mysqli_stmt_execute($stmt)) {
        // Redirect the user to the signin page after successful registration
        header("Location: ../signin_page.php");
        exit();
    } else {
        echo "Error: Could not register user.";
    }

    db_disconnect($db);
}
?>
