<?php
header("Content-Type: application/json");
include '../conn.php';
session_start();

// Check if user info exists in the session
if (!isset($_SESSION['userInfo'][0])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'User not logged in'
    ]);
    exit();
}

$userInfo = $_SESSION['userInfo'][0];
$user_id = $userInfo['id'];

// Get the book ID from the request body
$data = json_decode(file_get_contents("php://input"), true);
$book_id = isset($data['book_id']) ? $data['book_id'] : null;

if (!$book_id) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Book ID is required'
    ]);
    exit();
}

$query = "DELETE FROM shelf WHERE user_id = ? AND book_id = ?";
$statement = $conn->prepare($query);
$statement->bind_param("ii", $user_id, $book_id);

if ($statement->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Book removed from shelf'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to remove book from shelf'
    ]);
}

$statement->close();
$conn->close();
?>
