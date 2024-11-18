<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set the content type to JSON
header('Content-Type: application/json');
require_once('../database/database.php');
require_once('../database/db_credentials.php');

session_start();

// Step 1: Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}
$user_id = $_SESSION['user_id'];

// Step 2: Connect to the database
$db = db_connect();
if (!$db) {
    echo json_encode(['success' => false, 'message' => 'Failed to connect to the database']);
    exit();
}

// Step 3: Prepare the SQL query to fetch the user's locations
$sql = "SELECT * FROM Locations WHERE user_id = ?";
$stmt = mysqli_prepare($db, $sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
    db_disconnect($db);
    exit();
}

// Step 4: Bind parameters and execute the query
mysqli_stmt_bind_param($stmt, "i", $user_id);
if (!mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => false, 'message' => 'Failed to execute query']);
    db_disconnect($db);
    exit();
}

// Step 5: Fetch the results of the query
$result = mysqli_stmt_get_result($stmt);
if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch results']);
    db_disconnect($db);
    exit();
}

// Step 6: Fetch locations into an array
$locations = [];
while ($row = mysqli_fetch_assoc($result)) {
    $locations[] = $row;
}

// Step 7: Output the locations as a JSON response
echo json_encode(['success' => true, 'locations' => $locations]);

// Step 8: Close the database connection
mysqli_stmt_close($stmt);
db_disconnect($db);
?>
