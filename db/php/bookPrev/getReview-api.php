<?php
include '../../conn.php';
session_start();

// Assuming the logged-in user's ID is stored in the session
$user_id = $_SESSION['userInfo'][0]['id'];
$bookId = $_GET['book_id']; // Get the book ID from the request

// Query to fetch the book_isbn from the books table using book_id
$isbnQuery = "SELECT isbn FROM books WHERE id = ? LIMIT 1";
$isbnStatement = $conn->prepare($isbnQuery);
$isbnStatement->bind_param("i", $bookId);
$isbnStatement->execute();
$isbnResult = $isbnStatement->get_result();

$bookISBN = null;
if ($isbnResult->num_rows > 0) {
    $row = $isbnResult->fetch_assoc();
    $bookISBN = $row['isbn'];
}
$isbnStatement->close();

// Default review data structure
$reviewData = [
    'id' => null,
    'rating' => 0,
    'review_content' => null,
];

// Only proceed if book_isbn was retrieved
if ($bookISBN) {
    // Query to fetch the user's rating and review content for this book using book_isbn
    $query = "SELECT id, rating, review_content FROM bookReviews WHERE user_id = ? AND book_isbn = ? LIMIT 1";
    $statement = $conn->prepare($query);
    $statement->bind_param("is", $user_id, $bookISBN); // Bind as string for book_isbn
    $statement->execute();
    $result = $statement->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $reviewData = [
            'id' => $row['id'],
            'rating' => $row['rating'], // User's rating for this book
            'review_content' => $row['review_content'], // The review content (if any)
        ];
    }
    $statement->close();
}

// Return the complete review data as a JSON response
echo json_encode($reviewData);
?>
