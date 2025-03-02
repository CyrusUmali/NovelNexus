<?php
include '../../conn.php';
session_start();

header("Content-Type: application/json");

if (isset($_SESSION['userInfo'][0])) {
    $userInfo = $_SESSION['userInfo'][0];
    $user_id = $userInfo['id'];
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$book_id = $data['book_id'];
$rating = $data['rating'];
$review_content = $data['review'] ?? null;

$query = "
    UPDATE bookReviews
    SET rating = ?, review_content = ?, created_at = NOW()
    WHERE user_id = ? AND book_id = ?
";


$statement = $conn->prepare($query);
$statement->bind_param("isii", $rating, $review_content, $user_id, $book_id);

if ($statement->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Review updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error updating review']);
}

$statement->close();
$conn->close();
?>
