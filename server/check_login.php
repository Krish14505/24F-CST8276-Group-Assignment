<?php
session_start();

// Check if the user is logged in and redirect accordingly
if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard.php");
} else {
    header("Location: ../signin_page.php");
}

exit();
?>
