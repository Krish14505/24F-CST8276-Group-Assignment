<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

// Get and decode input data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['total_distance'], $data['start_location'], $data['end_location'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit();
}

// Prepare data for insertion
$routeName = isset($data['route_name']) ? $data['route_name'] : 'Unnamed Route';
$totalDistance = (float) $data['total_distance'];
$startLocation = $data['start_location'];
$endLocation = $data['end_location'];
$userId = $_SESSION['user_id'];

// Connect to the database
require_once('../database/database.php');
$db = db_connect();

// Insert data into Routes table
$sql = "INSERT INTO Routes (route_name, total_distance, start_location, end_location, Users_user_id)
        VALUES (?, ?, ?, ?, ?)";
$stmt = $db->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $db->error]);
    exit();
}

$stmt->bind_param('sdssi', $routeName, $totalDistance, $startLocation, $endLocation, $userId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Route saved successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save route: ' . $stmt->error]);
}

$stmt->close();
$db->close();
?>
