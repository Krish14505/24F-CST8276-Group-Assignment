<!-- check_login.php -->

<?php
session_start();
if (isset($_SESSION['user_id'])) {
    // If user is logged in, redirect to dashboard
    header("Location: ../dashboard.php");
} else {
    // If not logged in, redirect to sign-in page
    header("Location: ../signin_page.php");
}
exit();
?>
