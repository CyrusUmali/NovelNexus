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

// Check if amount is provided in the URL query string or in the JSON body
if (isset($_GET['amount'])) {
    // Amount from the URL query string
    $amount = floatval($_GET['amount']);
} elseif (isset($_POST['amount'])) {
    // Amount from the JSON body (if sent via POST)
    $amount = floatval($_POST['amount']);
} elseif ($data = json_decode(file_get_contents('php://input'), true) && isset($data['amount'])) {
    // Amount from the JSON body if the data is sent as JSON via POST
    $amount = floatval($data['amount']);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Amount is required.'
    ]);
    exit;
}

// Handle the payment process
$conn->begin_transaction(); // Start the transaction

try {
    // Update the loans table where user_id matches and paid is 0, setting it to 1
    $stmt = $conn->prepare("UPDATE loans SET paid = 1 WHERE user_id = ? AND paid = 0 AND status='Returned'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Record the payment in the payments table with payment_type set to 2
        $stmt = $conn->prepare("INSERT INTO payment (user_id, payment_type, amount, created_at) VALUES (?, 2, ?, NOW())");
        $stmt->bind_param("id", $user_id, $amount);
        $stmt->execute();

        // Commit the transaction if everything is successful
        $conn->commit();

        echo json_encode([
            'status' => 'success',
            'message' => 'Fines paid successfully.'
        ]);
        header("Location: ../../../index.php?page=subscription");
            exit;
    } else {
        throw new Exception("No unpaid loans found.");
    }
} catch (Exception $e) {
    // Rollback the transaction if there was an error
    $conn->rollback();

    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to process the payment: ' . $e->getMessage()
    ]);
} finally {
    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
