<?php
require_once('database.php');
require_once('db_credentials.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $db = db_connect();

    $sql = "INSERT INTO Users (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: login.html");
        exit();
    } else {
        echo "Error: Could not register user.";
    }

    db_disconnect($db);
}
?>
