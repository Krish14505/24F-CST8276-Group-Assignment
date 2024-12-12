<?php
// Include database connection and credentials
require_once(dirname(__DIR__) . '/database/database.php');
require_once(dirname(__DIR__) . '/database/db_credentials.php');


// Start the session if it's not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error_message = ""; // Initialize an error message variable

// Handle form submission
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
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        db_disconnect($db); 
        header("Location: /24F-CST8277-Google/dashboard.php");

        exit();
    } else {
        $error_message = "Invalid email or password.";
    }

    db_disconnect($db);
}
?>
