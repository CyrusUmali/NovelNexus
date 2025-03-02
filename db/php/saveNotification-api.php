<?php
// Include database connection
include '../conn.php'; // Assuming this file contains your database connection

// Set content type to JSON
header('Content-Type: application/json');

// Get the raw POST data
$data = file_get_contents("php://input");
$requestData = json_decode($data, true);

// Validate input
$loanId = isset($requestData["loan_id"]) ? intval($requestData["loan_id"]) : null;
$userId = isset($requestData["user_id"]) ? intval($requestData["user_id"]) : null;

if (!$loanId) {
    echo json_encode(["status" => "error", "message" => "Loan ID is required."]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Use SQL's DATEDIFF() to calculate days left
    $stmt = $conn->prepare("
    SELECT DATEDIFF(loan_to, CURDATE()) AS days_left, loan_to AS loanToDate
    FROM loans 
    WHERE id = ?
");
    $stmt->bind_param("i", $loanId);
    $stmt->execute();
    $stmt->bind_result($daysLeft, $loanToDate);

    $currentDate = new DateTime();
    $dueDate = new DateTime($loanToDate);    $stmt->fetch();
    $stmt->close();

    if ($daysLeft !== null) {
        // Prepare the query based on whether user_id is provided
        if ($userId) {
            // Insert notification with user_id
            $insertStmt = $conn->prepare("
                INSERT INTO notifications (loan_id, user_id, days_left, created_at, status) 
                VALUES (?, ?, ?, NOW(), 'unread')
            ");
            $insertStmt->bind_param("iii", $loanId, $userId, $daysLeft);
        } else {
            // Insert notification without user_id
            $insertStmt = $conn->prepare("
                INSERT INTO notifications (loan_id, days_left, created_at, status) 
                VALUES (?, ?, NOW(), 'unread')
            ");
            $insertStmt->bind_param("ii", $loanId, $daysLeft);
        }

        // Execute the query
        if ($insertStmt->execute()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Notification saved successfully',
                'days_left' => $daysLeft,
                'calculation' => "Days left is calculated as the difference between the current date ('" . $currentDate->format('Y-m-d') . "') and the due date ('" . $dueDate->format('Y-m-d') . "')"
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to execute insert query']);
        }
        $insertStmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Loan not found']);
    }

    $conn->close();
}
