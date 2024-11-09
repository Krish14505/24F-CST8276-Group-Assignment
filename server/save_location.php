<?php
require_once ('../database/db_credentials.php');
require_once ('../database/database.php');

// Get JSON input and decode
$data = json_decode(file_get_contents("php://input"), true);

// Extract data from JSON
$user_id = $data['user_id'];
$latitude = $data['latitude'];
$longitude = $data['longitude'];
$country = $data['country'];
$city = $data['city'];
$postal_code = $data['postal_code'];
$formatted_address = $data['formatted_address'];

// Connect to the database
$db = db_connect();

// Prepare the SQL statement with additional fields
$sql = "INSERT INTO Locations (user_id, latitude, longitude, country, city, postal_code, formatted_address) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($db, $sql);

// Bind parameters
mysqli_stmt_bind_param($stmt, "iddssss", $user_id, $latitude, $longitude, $country, $city, $postal_code, $formatted_address);

// Execute the statement and handle response
if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Location saved.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save location.']);
}

// Close the database connection
db_disconnect($db);
?>
