<?php
// Check if the user is logged in and return the user_id in JSON format
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
session_start();

if (isset($_SESSION['user_id'])) {
    echo json_encode(['user_id' => $_SESSION['user_id']]);
} else {
    echo json_encode(['user_id' => null]);
}
?>
