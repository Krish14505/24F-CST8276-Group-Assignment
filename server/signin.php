<!-- signin.php -->

<?php
require_once('../database/database.php');
require_once('../database/db_credentials.php');
session_start(); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']); // Sanitize inputs
    $password = trim($_POST['password']);

    $db = db_connect();

    // Find the user by email
    $sql = "SELECT * FROM Users WHERE email = ?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    // Verify user's password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id']; // Store user ID
        $_SESSION['username'] = $user['username']; // Store the username
        db_disconnect($db); // Close connection
        header("Location: ../dashboard.php"); 
        exit();
    } else {
        echo "<p>Invalid email or password.</p>";
    }

    db_disconnect($db);
}
?>
