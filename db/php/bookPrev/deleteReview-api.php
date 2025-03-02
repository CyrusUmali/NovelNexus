<?php
include '../../conn.php';
session_start();

$response = [
    'success' => false,
    'message' => 'Invalid request.',
];

// Check if the review ID and session data are valid
if (!isset($_SESSION['userInfo'][0]['id'])) {
    $response['message'] = 'User session data is missing.';
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['userInfo'][0]['id'];

// Get review_id from POST data (for DELETE requests)
$inputData = json_decode(file_get_contents("php://input"), true);
$reviewId = $inputData['review_id'] ?? null;

if (!$reviewId) {
    $response['message'] = 'Review ID not provided.';
    echo json_encode($response);
    exit;
}

// Query to delete the review if it belongs to the logged-in user
$query = "DELETE FROM bookReviews WHERE id = ? AND user_id = ?";
$statement = $conn->prepare($query);
$statement->bind_param("ii", $reviewId, $user_id);

if ($statement->execute() && $statement->affected_rows > 0) {
    $response['success'] = true;
    $response['message'] = 'Review deleted successfully.';
} else {
    $response['message'] = 'Failed to delete review or review not found.';
}

$statement->close();

// Return the response as JSON
echo json_encode($response);
?>
