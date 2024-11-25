<?php
require_once('../database/database.php');
require_once('../database/db_credentials.php');

$data = json_decode(file_get_contents('php://input'), true);
$response = ['success' => false];

if (isset($data['route_id'])) {
    $route_id = intval($data['route_id']);

    // Establish the database connection
    $conn = db_connect();
    if (!$conn) {
        $response['message'] = "Database connection failed.";
        echo json_encode($response);
        exit();
    }

    // Prepare the delete query
    $stmt = $conn->prepare("DELETE FROM Routes WHERE route_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $route_id);
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