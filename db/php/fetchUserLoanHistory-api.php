<?php
// Assuming you have an active database connection
include '../conn.php'; 

// Get the user_id from the query string
$user_id = $_GET['user_id'];

// Query to fetch borrow history for the specific user
$query = "
    SELECT b.isbn, b.book_cover,  b.title AS book_title, l.loan_from, l.loan_to, l.status ,l.id
    FROM loans l
    JOIN books b ON l.book_id = b.id
    WHERE l.user_id = ?  -- Use parameterized query for security
    ORDER BY l.loan_to DESC
";

// Prepare and execute the query
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the results as an associative array
$borrowHistory = [];
while ($row = $result->fetch_assoc()) {
    $borrowHistory[] = $row;
}

// Return the result as JSON
echo json_encode($borrowHistory);

// Close the database connection
$stmt->close();
$conn->close();
?>
