<?php
// Include database connection
include '../conn.php';
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

// Fetch notifications for the user's loans or directly by user_id
$stmt = $conn->prepare("
    SELECT 
        n.id, 
        n.created_at, 
        n.days_left, 
        n.status, 
        n.message, 
        b.title, 
        b.author, 
        b.book_cover
    FROM notifications n
    LEFT JOIN loans l ON n.loan_id = l.id
    LEFT JOIN books b ON l.book_id = b.id
    WHERE n.user_id = ? OR l.user_id = ?
    ORDER BY n.id DESC
");
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$stmt->bind_result($id, $created_at, $days_left, $status, $message, $book_title, $book_author, $book_cover);

$notifications = [];
while ($stmt->fetch()) {
    $notifications[] = [
        'id' => $id,
        'created_at' => $created_at,
        'days_left' => $days_left,
        'status' => $status,
        'message' => $message, 
        'book_title' => $book_title,
        'book_author' => $book_author,
        'book_cover' => $book_cover
    ];
}
$stmt->close();

// Mark notifications as read for the current user
$updateStmt = $conn->prepare("
    UPDATE notifications 
    SET status = 'read' 
    WHERE (user_id = ? OR loan_id IN (SELECT id FROM loans WHERE user_id = ?)) 
    AND status = 'unread'
");
$updateStmt->bind_param("ii", $user_id, $user_id);
$updateStmt->execute();
$updateStmt->close();

// Return notification details
echo json_encode([
    'status' => 'success',
    'user_id' => $user_id,
    'notifications' => $notifications
]);

$conn->close();
?>
