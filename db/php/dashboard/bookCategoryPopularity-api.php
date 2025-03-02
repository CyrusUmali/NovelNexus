<?php
// Include database connection
include '../../conn.php';
session_start();
header('Content-Type: application/json');

// Get the month parameter from the request
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n'); // Default to current month

// Fetch the book category popularity with the month filter
$query = "
    SELECT c.name AS category_name, COUNT(l.id) AS loan_count
    FROM category c
    JOIN bookcategories bc ON c.id = bc.category_id
    JOIN books b ON b.id = bc.book_id
    LEFT JOIN loans l ON l.book_id = b.id
    WHERE MONTH(l.loan_from) = ?  -- Assuming the loan date is the relevant column to filter by month
    GROUP BY c.id
    ORDER BY loan_count DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $month);  // Bind the month parameter
$stmt->execute();
$result = $stmt->get_result();

$categories = [];
$loan_counts = [];

while ($row = $result->fetch_assoc()) {
    $categories[] = $row['category_name'];
    $loan_counts[] = (int)$row['loan_count'];  // Ensure the count is an integer
}

echo json_encode([
    'categories' => $categories,
    'loan_counts' => $loan_counts
]);

$conn->close();
?>
