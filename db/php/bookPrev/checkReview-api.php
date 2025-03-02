<?php
include '../../conn.php';
session_start();

header("Content-Type: application/json");

// Check if user info exists in the session
if (isset($_SESSION['userInfo'][0])) {
    $userInfo = $_SESSION['userInfo'][0];
    $user_id = $userInfo['id'];
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

// Get the POST data from the Axios request
$data = json_decode(file_get_contents('php://input'), true);
$book_id = $data['book_id'] ?? null;

if (!$book_id) {
    echo json_encode(['status' => 'error', 'message' => 'Book ID is required']);
    exit();
}

// Fetch the book_isbn using the book_id
$isbnQuery = "SELECT isbn FROM books WHERE id = ? LIMIT 1";
$isbnStatement = $conn->prepare($isbnQuery);
$isbnStatement->bind_param("i", $book_id);
$isbnStatement->execute();
$isbnResult = $isbnStatement->get_result();

$bookISBN = null;
if ($isbnResult->num_rows > 0) {
    $row = $isbnResult->fetch_assoc();
    $bookISBN = $row['isbn'];
}
$isbnStatement->close();

// Check if book_isbn was retrieved
if (!$bookISBN) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid book ID or book ISBN not found']);
    exit();
}

// Check if a review exists for the user and the book using book_isbn
$query = "
    SELECT id FROM bookReviews WHERE user_id = ? AND book_isbn = ?
";
$statement = $conn->prepare($query);
$statement->bind_param("is", $user_id, $bookISBN);
$statement->execute();
$result = $statement->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'exists']);
} else {
    echo json_encode(['status' => 'not_exists']);
}

$statement->close();
$conn->close();
?>
