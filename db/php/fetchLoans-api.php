<?php
include '../conn.php'; // Database connection

// Get the page, limit, and search parameters from the request
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 8;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$offset = ($page - 1) * $limit;

// Base SQL query with optional search
$sql = "
    SELECT 
        u.id AS user_id,  
        u.photo, 
        CONCAT(u.fname, ' ', u.lname) AS member, 
        b.id AS book_id, 
        b.book_cover,
        b.title AS book_title, 
        b.author, 
        l.loan_from, 
        l.loan_to, 
        l.status, 
        l.fine,
        l.paid,
        l.id AS loan_id
    FROM loans l
    JOIN users u ON l.user_id = u.id
    JOIN books b ON l.book_id = b.id
";

// Add the search condition if a search query is provided
if (!empty($search)) {
    $sql .= " WHERE CONCAT(u.fname, ' ', u.lname) LIKE ? OR b.title LIKE ?";
}

$sql .= " ORDER BY l.id DESC LIMIT ?, ?";
$stmt = $conn->prepare($sql);

if (!empty($search)) {
    $searchWildcard = "%" . $search . "%";
    $stmt->bind_param('ssii', $searchWildcard, $searchWildcard, $offset, $limit);
} else {
    $stmt->bind_param('ii', $offset, $limit);
}

$stmt->execute();
$result = $stmt->get_result();

$loans = [];
while ($row = $result->fetch_assoc()) {
    $loans[] = $row;
}

// Query to get the total number of loans for pagination
$sqlCount = "SELECT COUNT(*) AS total_loans FROM loans 
    JOIN users u ON loans.user_id = u.id 
    JOIN books b ON loans.book_id = b.id";

// Add the search condition to the count query if a search query is provided
if (!empty($search)) {
    $sqlCount .= " WHERE CONCAT(u.fname, ' ', u.lname) LIKE ? OR b.title LIKE ?";
    $stmtCount = $conn->prepare($sqlCount);
    $stmtCount->bind_param('ss', $searchWildcard, $searchWildcard);
    $stmtCount->execute();
    $resultCount = $stmtCount->get_result();
} else {
    $resultCount = $conn->query($sqlCount);
}

$totalLoans = $resultCount->fetch_assoc()['total_loans'];

$response = [
    'loans' => $loans,
    'totalLoans' => $totalLoans
];

echo json_encode($response);
?>
