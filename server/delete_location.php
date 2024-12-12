<?php
require_once('../database/database.php');
require_once('../database/db_credentials.php');

$data = json_decode(file_get_contents('php://input'), true);
$response = ['success' => false];

if (isset($data['location_id'])) {
    $location_id = intval($data['location_id']);

    // Establish the database connection
    $conn = db_connect();
    if (!$conn) {
        $response['message'] = "Database connection failed.";
        echo json_encode($response);
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM Locations WHERE location_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $location_id);
        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['message'] = "Error executing delete query.";
        }
        $stmt->close();
    } else {
        $response['message'] = "Failed to prepare statement.";
    }
    db_disconnect($conn);
} else {
    $response['message'] = "Invalid request data.";
}

echo json_encode($response);
?>
