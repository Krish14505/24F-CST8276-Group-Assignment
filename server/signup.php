<?php
require_once(dirname(__DIR__) . '/database/database.php');
require_once(dirname(__DIR__) . '/database/db_credentials.php');


$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $db = db_connect();

    // Check if email already exists
    $check_sql = "SELECT * FROM Users WHERE email = ?";
    $check_stmt = mysqli_prepare($db, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "s", $email);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    
    if (mysqli_fetch_assoc($check_result)) {
        $error_message = "Email is already registered.";
    } else {
        $sql = "INSERT INTO Users (username, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: /24F-CST8277-Google/signin_page.php");

            exit();
        } else {
            $error_message = "Error: Could not register user.";
        }
    }

    db_disconnect($db);
}
?>
