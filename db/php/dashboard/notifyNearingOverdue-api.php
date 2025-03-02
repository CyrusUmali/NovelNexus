<?php
// Include database connection
include '../../conn.php'; // Assuming this file contains your database connection

// Set content type to JSON
header('Content-Type: application/json');

// Start the response
$response = ['status' => 'error', 'message' => 'Failed to process request.', 'data' => []];

// Fetch all loans that are nearing overdue (i.e., 2 days before loan_to)
$sql = "
    SELECT l.id AS loan_id, l.user_id, DATEDIFF(l.loan_to, CURDATE()) AS days_left
    FROM loans l
 WHERE DATEDIFF(l.loan_to, CURDATE()) <= 2

    AND l.status != 'Returned' 
    AND l.status != 'Pending'
";
$result = $conn->query($sql);

// Check if there are any loans that are nearing overdue
if ($result && $result->num_rows > 0) {
    // Process each loan nearing overdue
    while ($loan = $result->fetch_assoc()) {
        $loanId = $loan['loan_id'];
        $userId = $loan['user_id'];
        $daysLeft = $loan['days_left'];

        // Insert notification into the database
        $insertStmt = $conn->prepare("INSERT INTO notifications (loan_id, user_id, days_left, created_at, status) VALUES (?, ?, ?, NOW(), 'unread')");
        $insertStmt->bind_param("iii", $loanId, $userId, $daysLeft);

        if ($insertStmt->execute()) {
            $response['data'][] = [
                'loan_id' => $loanId,
                'user_id' => $userId,
                'days_left' => $daysLeft,
                'status' => 'unread' // Reflecting the actual inserted status
            ];
        } else {
            $response['data'][] = [
                'loan_id' => $loanId,
                'user_id' => $userId,
                'status' => 'Failed to insert notification'
            ];
        }
    }

    // If notifications were inserted, set the response status to success
    if (count($response['data']) > 0) {
        $response['status'] = 'success';
        $response['message'] = 'Notifications processed successfully.';
    } else {
        $response['message'] = 'No loans nearing overdue found.';
    }
} else {
    $response['message'] = 'No loans nearing overdue found.';
}

// Close the database connection
$conn->close();

// Return the response as JSON
echo json_encode($response);
?>
