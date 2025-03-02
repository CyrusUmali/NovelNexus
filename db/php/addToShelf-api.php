<?php
include '../conn.php';
session_start();

header("Content-Type: application/json"); // Set the response to JSON format

// Check if user info exists in the session
if (isset($_SESSION['userInfo'][0])) {
    $userInfo = $_SESSION['userInfo'][0];
    $user_id = $userInfo['id'];
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'User not logged in'
    ]);
    exit();
}

// Retrieve book_id from the request body
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['book_id']) || empty($input['book_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Book ID is required'
    ]);
    exit();
}

$book_id = $input['book_id'];

// Check if the book is already in the user's shelf
$checkQuery = "
    SELECT id FROM shelf WHERE user_id = ? AND book_id = ?
";
$checkStatement = $conn->prepare($checkQuery);
$checkStatement->bind_param("ii", $user_id, $book_id);
$checkStatement->execute();
$checkResult = $checkStatement->get_result();

if ($checkResult->num_rows > 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Book already exists in the shelf'
    ]);
    $checkStatement->close();
    $conn->close();
    exit();
}

// Insert the book into the user's shelf
$insertQuery = "
    INSERT INTO shelf (user_id, book_id) VALUES (?, ?)
";
$insertStatement = $conn->prepare($insertQuery);
$insertStatement->bind_param("ii", $user_id, $book_id);

if ($insertStatement->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Book added to shelf successfully'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to add book to shelf'
    ]);
}

$insertStatement->close();
$conn->close();
?>
