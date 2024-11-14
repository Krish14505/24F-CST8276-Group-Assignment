<!-- save_location.php -->

<?php
// AUTHOR: Paulo Ricardo Gomes Granjeiro, Kyla Pineda
// Collaborators: Craig, Krish, Leonardo, Yazid

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../database/db_credentials.php');
require_once('../database/database.php');

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Get JSON input and decode
$data = json_decode(file_get_contents("php://input"), true);

// Check if data is received properly
if (!$data || !isset($data['latitude']) || !isset($data['longitude'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit();
}

// Extract data from JSON with validation
$latitude = $data['latitude'];
$longitude = $data['longitude'];
$country = isset($data['country']) ? $data['country'] : null;
$city = isset($data['city']) ? $data['city'] : null;
$postal_code = isset($data['postal_code']) ? $data['postal_code'] : null;
$formatted_address = isset($data['formatted_address']) ? $data['formatted_address'] : null;

// Connect to the database
$db = db_connect();
if (!$db) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

// Prepare SQL statement
$sql = "INSERT INTO Locations (user_id, latitude, longitude, country, city, postal_code, formatted_address) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($db, $sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
    db_disconnect($db);
    exit();
}

// Bind parameters
mysqli_stmt_bind_param($stmt, "iddssss", $user_id, $latitude, $longitude, $country, $city, $postal_code, $formatted_address);

// Execute the statement and handle response
if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Location saved successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save location']);
}

// Close the statement and database connection
mysqli_stmt_close($stmt);
db_disconnect($db);
?>
