<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
require_once('../database/database.php');

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Connect to the database
$db = db_connect();
if (!$db) {
    echo json_encode(['success' => false, 'message' => 'Failed to connect to the database']);
    exit();
}

// Fetch saved routes for the logged-in user
$sql = "SELECT route_id, route_name, start_location, end_location, total_distance
        FROM Routes
        WHERE Users_user_id = ?
        ORDER BY route_id DESC";

$stmt = mysqli_prepare($db, $sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
    db_disconnect($db);
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $user_id);
if (!mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => false, 'message' => 'Failed to execute query']);
    db_disconnect($db);
    exit();
}

$result = mysqli_stmt_get_result($stmt);
if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch results']);
    db_disconnect($db);
    exit();
}

// Fetch routes into an array
$routes = [];
while ($row = mysqli_fetch_assoc($result)) {
    $routes[] = $row;
}

// Return routes as JSON response
echo json_encode(['success' => true, 'routes' => $routes]);

mysqli_stmt_close($stmt);
db_disconnect($db);
?>
