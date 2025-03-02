<?php
include '../../conn.php';
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

// Get the POST data from the Axios request
$data = json_decode(file_get_contents('php://input'), true);

$rating = $data['rating'] ?? 0;
$reviewContent = $data['review'] ?? null;
$book_id = $data['book_id'] ?? null;

// Check if the rating is above 0 and book_id is provided
if ($rating <= 0 || !$book_id) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Rating must be greater than 0 and book_id is required'
    ]);
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
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid book ID or book ISBN not found'
    ]);
    exit();
}

// SQL query to insert the review into the bookReviews table
$query = "
    INSERT INTO bookReviews (user_id, book_id, book_isbn, rating, review_content)
    VALUES (?, ?, ?, ?, ?)
";

$statement = $conn->prepare($query);

// Bind parameters to the SQL query
$statement->bind_param("iisis", $user_id, $book_id, $bookISBN, $rating, $reviewContent);

// Execute the query and check if it's successful
if ($statement->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Review submitted successfully'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error submitting review'
    ]);
}

// Close the statement and database connection
$statement->close();
$conn->close();
?>
