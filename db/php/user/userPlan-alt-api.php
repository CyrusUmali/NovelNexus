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

// Check if plan_id exists in the JSON data or URL query string
if (isset($data['plan_id'])) {
    $plan_id = intval($data['plan_id']); // Plan ID from the JSON body
} elseif (isset($_GET['plan_id'])) {
    $plan_id = intval($_GET['plan_id']); // Plan ID from the URL query string
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Plan ID is required.'
    ]);
    exit;
}

// Handle plan update logic
if ($plan_id === 1) {
    // Free plan (plan_id = 1) - No payment required
    $stmt = $conn->prepare("UPDATE users SET plan_id = ?, subscription_start = NULL, subscription_end = NULL WHERE id = ?");
    $stmt->bind_param("ii", $plan_id, $user_id);
    
    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Plan upgraded to Free plan successfully.',
             'redirect' => './index.php?page=subscription'
        ]); 
        // header("Location: ../../../index.php?page=subscription");
        exit; // Ensure no further code executes after the redirection
  

    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to upgrade to Free plan.'
        ]);
    }
} elseif (in_array($plan_id, [2, 3])) {
    // Paid plans (plan_id = 2 or 3)
    $amount = ($plan_id === 2) ? 399 : 699; // Assign amount based on the plan

    // Update user plan with subscription dates
    $stmt = $conn->prepare("UPDATE users SET plan_id = ?, subscription_start = CURDATE(), subscription_end = DATE_ADD(CURDATE(), INTERVAL 1 MONTH) WHERE id = ?");
    $stmt->bind_param("ii", $plan_id, $user_id);

    if ($stmt->execute()) {
        // Insert a payment record
        $paymentStmt = $conn->prepare("INSERT INTO payment (user_id, payment_type, amount, created_at) VALUES (?, 1, ?, NOW())");
        $paymentStmt->bind_param("id", $user_id, $amount);

        if ($paymentStmt->execute()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Plan upgraded successfully, and payment recorded.',
                'amount' => $amount ,
                 'redirect' => './index.php?page=subscription'
            ]); 
            exit;
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Plan upgraded, but failed to record payment.'
            ]);
        }

        $paymentStmt->close();
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to upgrade the plan.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid plan ID.'
    ]);
}

// Close statements and connection
$stmt->close();
$conn->close();
?>
