<?php
// Include database connection and credentials
require_once('../database/database.php');
require_once('../database/db_credentials.php');

// Start the session to manage session variables
session_start(); 

// Handle form submission when the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']); 
    $password = trim($_POST['password']); 

    // Connect to the database
    $db = db_connect();

    // Prepare the SQL query to find the user by email
    $sql = "SELECT * FROM Users WHERE email = ?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    // Check if user exists and verify the password
    if ($user && password_verify($password, $user['password'])) {
        // Set session variables for the logged-in user
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        
        // Close database connection and redirect to the dashboard
        db_disconnect($db); 
        header("Location: ../dashboard.php"); 
        exit();
    } else {
        echo "<p>Invalid email or password.</p>";
    }

    // Close the database connection
    db_disconnect($db);
}
?>
