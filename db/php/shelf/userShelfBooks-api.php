<?php
include '../../conn.php';
session_start();

header("Content-Type: application/json"); // Set the response to JSON format

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

// SQL query to fetch books from the user's shelf
$query = "
    SELECT 
        b.id,
        b.title, 
        b.author, 
        b.book_cover
    FROM books b
    INNER JOIN shelf s ON b.id = s.book_id
    WHERE s.user_id = ?
";

$statement = $conn->prepare($query);
$statement->bind_param("i", $user_id); // Binding user_id for the shelf
$statement->execute();
$result = $statement->get_result();

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'author' => $row['author'],
        'book_cover' => htmlspecialchars($row['book_cover']),
    ];
}

echo json_encode($books);

$statement->close();
$conn->close();
