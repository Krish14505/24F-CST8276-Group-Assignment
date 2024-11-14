<!-- user_auth.php -->

<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin_page.html");
    exit();
}
?>
