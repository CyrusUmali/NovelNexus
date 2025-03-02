<?php
// Include database connection
include '../../conn.php';
session_start();

// Set content type to JSON
header('Content-Type: application/json');

// Check if user info exists in the session
if (isset($_SESSION['userInfo'][0])) {
    $userInfo = $_SESSION['userInfo'][0];
    $user_id = $userInfo['id'];
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'User not logged in'
    ]);
    exit;
}

// Read the JSON input data
$data = json_decode(file_get_contents('php://input'), true);

// Handle the plan update
$stmt = $conn->prepare("UPDATE users SET plan_id = 1, subscription_start = NULL, subscription_end = NULL WHERE id = ?");
$stmt->bind_param("i", $user_id);

// Execute and return response for canceling the plan
if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Plan canceled successfully.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to cancel the plan.'
    ]);
}

$stmt->close();
$conn->close();
?>
