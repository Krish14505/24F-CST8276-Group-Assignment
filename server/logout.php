<?php
// Log out the user by clearing the session and redirecting to the homepage
session_start();
session_unset();
session_destroy();
header("Location: ../index.php");
exit();
?>
