<?php
// Include database connection
include '../../conn.php';

// Set content type to JSON
header('Content-Type: application/json');

// Get the current date
$currentDate = date("Y-m-d");

// Query to check users with expired subscriptions
$stmt = $conn->prepare("SELECT id, plan_id, subscription_end FROM users WHERE subscription_end < ?");
$stmt->bind_param("s", $currentDate);
$stmt->execute();
$stmt->store_result();

// Bind the result columns to variables
$stmt->bind_result($user_id, $plan_id, $subscription_end);

// Check if there are any users with expired subscriptions
if ($stmt->num_rows > 0) {
    // Loop through expired users
    while ($stmt->fetch()) {
        // Ensure that the fetched data is valid
        if (!is_null($user_id) && !is_null($plan_id) && !is_null($subscription_end)) {
            // Update expired user's plan to Free plan (plan_id = 1)
            $updateStmt = $conn->prepare("UPDATE users SET plan_id = 1, subscription_start = NULL, subscription_end = NULL WHERE id = ?");
            $updateStmt->bind_param("i", $user_id);
            $updateStmt->execute();
            $updateStmt->close();

            // Insert a notification for the user
            $notificationStmt = $conn->prepare("INSERT INTO notifications (created_at, user_id, status, message) VALUES (NOW(), ?, 'unread', ?)");
            $message = "Your subscription has expired and has been updated to the Free plan.";
            $notificationStmt->bind_param("is", $user_id, $message);
            $notificationStmt->execute();
            $notificationStmt->close();
        } else {
            // Log or handle the error if user data is incomplete
            echo json_encode([
                'status' => 'error',
                'message' => 'Incomplete user data encountered.'
            ]);
            exit;
        }
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Expired subscriptions updated to Free plan (plan_id = 1) and notifications sent.'
    ]);
} else {
    echo json_encode([
        'status' => 'success',
        'message' => 'No expired subscriptions found.'
    ]);
}

// Close main statement and connection
$stmt->close();
$conn->close();
?>
