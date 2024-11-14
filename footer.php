<!-- footer.php -->

<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<footer style="background-color: #031546; color: #cfc2c2; text-align: center; padding: 20px;">
    <p>&copy; <?= date("Y"); ?> Your Project Name. All rights reserved.</p>
</footer>
