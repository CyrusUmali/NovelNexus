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

$query = "
    SELECT 
        b.title, 
        b.author, 
        b.book_cover, 
        l.loan_from, 
        l.loan_to, 
        l.book_id, 
        l.status, 
        l.id AS loan_id,
        AVG(r.rating) AS average_rating
    FROM loans l
    JOIN books b ON l.book_id = b.id
    LEFT JOIN bookReviews r ON b.id = r.book_id
    WHERE l.user_id = ?
    GROUP BY b.id, l.loan_from, l.loan_to, l.book_id, l.status
    ORDER BY l.id DESC
";

$statement = $conn->prepare($query);
$statement->bind_param("i", $user_id);
$statement->execute();
$result = $statement->get_result();

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = [
        "title" => $row["title"],
        "author" => $row["author"],
        "book_cover" => htmlspecialchars($row["book_cover"]),
        "loan_from" => $row["loan_from"],
        "loan_to" => $row["loan_to"],
        "book_id" => $row["book_id"],
        "status" => $row["status"],
        "loan_id" => $row["loan_id"],
        "average_rating" => $row["average_rating"] ? floatval($row["average_rating"]) : null // Set to null if no reviews
    ];
}

echo json_encode($books);

$statement->close();
$conn->close();
?>
