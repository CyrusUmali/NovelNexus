<?php
include '../../conn.php'; // Database connection

// Initialize response
$response = ['success' => false, 'message' => 'Failed to update user subscription statuses.'];

// Start transaction to ensure data integrity
$conn->begin_transaction();

try {
    // Fetch all users whose subscription has expired
    $sql = "
        SELECT u.id AS user_id, u.plan_id, u.subscription_end
        FROM users u
        WHERE u.subscription_end < CURDATE()
        AND u.plan_id != 1
    ";
    $result = $conn->query($sql);

    // Check if there are users with expired subscriptions
    if ($result && $result->num_rows > 0) {
        // Process each user with an expired subscription
        while ($user = $result->fetch_assoc()) {
            $user_id = $user['user_id'];
            $plan_id = $user['plan_id'];
            $subscription_end = $user['subscription_end'];

            // Update user's plan to Free Plan (plan_id = 1) after subscription expiration
            $updateSql = "
                UPDATE users 
                SET plan_id = 1, subscription_end = NULL 
                WHERE id = ?
            ";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("i", $user_id);

            // Execute the update
            if (!$updateStmt->execute()) {
                throw new Exception('Failed to update user plan to Free Plan.');
            }

            // Insert a notification for the user
            $notificationSql = "
                INSERT INTO notifications (created_at, user_id, status, message) 
                VALUES (NOW(), ?, 'unread', ?)
            ";
            $notificationStmt = $conn->prepare($notificationSql);
            $message = "Your subscription has expired and has been updated to the Free plan.";
            $notificationStmt->bind_param("is", $user_id, $message);

            // Execute the notification insertion
            if (!$notificationStmt->execute()) {
                throw new Exception('Failed to insert notification for user.');
            }
        }

        // Commit the transaction if all updates and notifications are successful
        $conn->commit();
        $response['success'] = true;
        $response['message'] = 'User subscriptions updated to Free Plan and notifications sent successfully.';
    } else {
        $response['success'] = true;
        $response['message'] = 'No expired subscriptions found.';
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
