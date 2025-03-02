<?php
include '../conn.php'; // Database connection

// Get the page and limit parameters from the request
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = ($page - 1) * $limit;

// Query to get the loans with user and book details for the current page
$sql = "
    SELECT 
        u.id AS user_id, 
        CONCAT(u.fname, ' ', u.lname) AS member, 
        b.id AS book_id, 
        b.title AS book_title, 
        b.author, 
        l.loan_from, 
        l.loan_to, 
        l.status, 
        l.fine,
        l.id AS loan_id
    FROM loans l
    JOIN users u ON l.user_id = u.id
    JOIN books b ON l.book_id = b.id
    WHERE l.status = 'Pending'
    ORDER BY l.loan_to DESC
    LIMIT ?, ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();

$loans = [];
while ($row = $result->fetch_assoc()) {
    $loans[] = $row;
}
 
// Query to get the total number of pending loans for pagination
$sqlCount = "SELECT COUNT(*) AS total_loans FROM loans WHERE status = 'Pending'";
$resultCount = $conn->query($sqlCount);
$totalLoans = $resultCount->fetch_assoc()['total_loans'];

$response = [
    'loans' => $loans,
    'totalLoans' => $totalLoans
];

echo json_encode($response);
?>
