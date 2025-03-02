<?php
// Include database connection
include '../../conn.php';
session_start();
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
    exit();
}

// Check if notification ID is provided
$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['notif_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Notification ID is required'
    ]);
    exit();
}

$notif_id = $input['notif_id'];

// Check if the notification belongs to the logged-in user
$stmt = $conn->prepare("
    SELECT id 
    FROM notifications 
    WHERE id = ?  
");
$stmt->bind_param("i", $notif_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();
    echo json_encode([
        'status' => 'error',
        'message' => 'Notification not found '
    ]);
    exit();
}
$stmt->close();

// Delete the notification
$deleteStmt = $conn->prepare("
    DELETE FROM notifications 
    WHERE id = ?
");
$deleteStmt->bind_param("i", $notif_id);
$deleteStmt->execute();

if ($deleteStmt->affected_rows > 0) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Notification deleted successfully'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to delete notification'
    ]);
}
$deleteStmt->close();

$conn->close();
?>
