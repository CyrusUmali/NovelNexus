<?php
// Include database connection
include '../../conn.php';
session_start();
header('Content-Type: application/json');
 

// Fetch the count of available and borrowed books
$stmt = $conn->prepare("
    SELECT 
        SUM(CASE WHEN Availability = 'Available' THEN 1 ELSE 0 END) AS available_books,
        SUM(CASE WHEN Availability = 'Loaned' THEN 1 ELSE 0 END) AS borrowed_books
    FROM books
");
$stmt->execute();
$stmt->bind_result($available_books, $borrowed_books);
$stmt->fetch();
$stmt->close();

// Return the book availability data as JSON
echo json_encode([
    'status' => 'success',
    'labels' => ['Available', 'Loaned'],
    'data' => [$available_books, $borrowed_books]
]);

$conn->close();
?>
