<?php
// Include database connection
include '../conn.php';
session_start();
// Set content type to JSON
header('Content-Type: application/json');

// Check if user info exists in the session
if (isset($_SESSION['userInfo'][0])) {
    $userInfo = $_SESSION['userInfo'][0];
    $user_id = $userInfo['id'];
} else {
    // If user is not logged in or session is missing, return an error response
    echo json_encode([
        'status' => 'error',
        'message' => 'User not logged in'
    ]);
    exit();
}

// Fetch the loan IDs for the user
$loanStmt = $conn->prepare("SELECT id FROM loans WHERE user_id = ?");
$loanStmt->bind_param("i", $user_id);
$loanStmt->execute();
$loanStmt->bind_result($loan_id);
$loanIds = [];
while ($loanStmt->fetch()) {
    $loanIds[] = $loan_id;
}
$loanStmt->close();

// If no loans are found for the user, return unread count as zero
if (empty($loanIds)) {
    echo json_encode([
        'status' => 'success',
        'user_id' => $user_id,
        'unread_count' => 0
    ]);
    exit();
}

// Count unread notifications
$placeholders = str_repeat('?,', count($loanIds) - 1) . '?';
$countStmt = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE loan_id IN ($placeholders) AND status = 'unread'");
$countStmt->bind_param(str_repeat('i', count($loanIds)), ...$loanIds);
$countStmt->execute();
$countStmt->bind_result($unreadCount);
$countStmt->fetch();
$countStmt->close();

// Return the unread count as JSON
echo json_encode([
    'status' => 'success',
    'user_id' => $user_id,
    'unread_count' => $unreadCount
]);

$conn->close();
?>
