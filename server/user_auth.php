<?php
// Check if the user is logged in by verifying if the user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin_page.html");
    exit(); 
}
?>
