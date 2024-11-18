<?php
// Enable error reporting for debugging during development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection files
require_once('../database/db_credentials.php');
require_once('../database/database.php');

// Start the session to access session data
session_start();

// Step 1: Check if the user is logged in by verifying the session 'user_id'
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();  // Stop execution if the user is not logged in
}

// Step 2: Decode the incoming JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Check if data is received; if not, return an error message
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No data received']);
    exit();
}

// Step 3: Validate the data (check if latitude and longitude are present)
if (!isset($data['latitude']) || !isset($data['longitude'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit();
}

// Extract the data from the incoming JSON
$latitude = $data['latitude'];
$longitude = $data['longitude'];
$country = $data['country'] ?? null;
$city = $data['city'] ?? null;
$postal_code = $data['postal_code'] ?? null;
$formatted_address = $data['formatted_address'] ?? null;

// Step 4: Connect to the database
$db = db_connect();
if (!$db) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

// Step 5: Prepare the SQL query to insert the location data
$sql = "INSERT INTO Locations (user_id, latitude, longitude, country, city, postal_code, formatted_address, timestamp) 
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
$stmt = mysqli_prepare($db, $sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . mysqli_error($db)]);
    db_disconnect($db);
    exit();
}

// Bind parameters to the prepared statement
mysqli_stmt_bind_param($stmt, "iddssss", $_SESSION['user_id'], $latitude, $longitude, $country, $city, $postal_code, $formatted_address);

// Step 6: Execute the SQL statement and check if it's successful
if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Location saved successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save location: ' . mysqli_error($db)]);
}

// Close the prepared statement and disconnect from the database
mysqli_stmt_close($stmt);
db_disconnect($db);
?>
