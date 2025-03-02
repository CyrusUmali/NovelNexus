<?php
include '../conn.php'; // Database connection

// Initialize response
$response = ['success' => false, 'message' => 'Failed to update loan statuses and fines.'];

// Start transaction to ensure data integrity
$conn->begin_transaction();

try {
    // Fetch all overdue loans (including loans already marked as Overdue)
    $sql = "
        SELECT l.id AS loan_id, l.user_id, l.loan_to, u.plan_id
        FROM loans l
        JOIN users u ON l.user_id = u.id
       WHERE l.loan_to < CURDATE() 
  AND l.status != 'Returned'
  AND l.status != 'Pending'

    ";
    $result = $conn->query($sql);

    // Check if there are overdue loans
    if ($result && $result->num_rows > 0) {
        // Process each overdue loan
        while ($loan = $result->fetch_assoc()) {
            $loan_id = $loan['loan_id'];
            $user_id = $loan['user_id'];
            $loan_to = $loan['loan_to'];
            $plan_id = $loan['plan_id'];

            // Calculate the number of overdue days
            $overdue_days = (strtotime(date('Y-m-d')) - strtotime($loan_to)) / (60 * 60 * 24);

            // Determine fine rate based on the user's plan
            $fine_per_day = 0;

            // Only apply fines for Free and Standard plans
            switch ($plan_id) {
                case 1: // Free Plan
                    $fine_per_day = 50; // $1/day for free plan
                    break;
                case 2: // Standard Plan
                    $fine_per_day = 25; // $0.75/day for standard plan
                    break;
                case 3: // Premium Plan
                    // No fines for Premium Plan
                    $fine_per_day = 0;
                    break;
                default:
                    $fine_per_day = 0;
                    break;
            }

            // Only calculate and apply fines if it's not a Premium Plan
            $total_fine = 0;
            if ($fine_per_day > 0) {
                $total_fine = $overdue_days * $fine_per_day;
            }

            // Update loan status to 'Overdue' and apply fine if applicable
            $updateSql = "
                UPDATE loans 
                SET status = 'Overdue', fine = ? 
                WHERE id = ?
            ";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("di", $total_fine, $loan_id);

            // Execute the update
            if (!$updateStmt->execute()) {
                throw new Exception('Failed to update loan status or apply fine.');
            }
        }

        // Commit the transaction if all updates are successful
        $conn->commit();
        $response['success'] = true;
        $response['message'] = 'Loan statuses and fines updated successfully.';
    } else {
        $response['success'] = true;
        $response['message'] = 'No overdue loans found.';
    }
} catch (Exception $e) {
    // Rollback the transaction if any error occurs
    $conn->rollback();
    $response['message'] = 'Error: ' . $e->getMessage();
}

// Close the database connection
$conn->close();

// Return the response as JSON
echo json_encode($response);
?>
