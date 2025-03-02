<?php
include '../../conn.php'; // Database connection

// Get the page, limit, and search parameters from the request
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$offset = ($page - 1) * $limit;

// Base SQL query with optional search
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
        l.id,
        DATEDIFF(CURDATE(), l.loan_to) AS days_overdue
    FROM loans l
    JOIN users u ON l.user_id = u.id
    JOIN books b ON l.book_id = b.id
    WHERE l.status = 'overdue'
";

// Add the search condition if a search query is provided
if (!empty($search)) {
    $sql .= " AND (u.fname LIKE ? OR u.lname LIKE ? OR b.title LIKE ?)";
}

$sql .= " ORDER BY l.id DESC LIMIT ?, ?";
$stmt = $conn->prepare($sql);

if (!empty($search)) {
    $searchWildcard = "%" . $search . "%";
    $stmt->bind_param('sssii', $searchWildcard, $searchWildcard, $searchWildcard, $offset, $limit);
} else {
    $stmt->bind_param('ii', $offset, $limit);
}

$stmt->execute();
$result = $stmt->get_result();

$overdueLoans = [];
while ($row = $result->fetch_assoc()) {
    $overdueLoans[] = $row;
}

// Query to get the total number of overdue loans for pagination
$sqlCount = "
    SELECT COUNT(*) AS total_overdue
    FROM loans l
    JOIN users u ON l.user_id = u.id
    JOIN books b ON l.book_id = b.id
    WHERE l.status = 'overdue'
";

// Add the search condition to the count query if a search query is provided
if (!empty($search)) {
    $sqlCount .= " AND (u.fname LIKE ? OR u.lname LIKE ? OR b.title LIKE ?)";
    $stmtCount = $conn->prepare($sqlCount);
    $stmtCount->bind_param('sss', $searchWildcard, $searchWildcard, $searchWildcard);
    $stmtCount->execute();
    $resultCount = $stmtCount->get_result();
} else {
    $resultCount = $conn->query($sqlCount);
}

$totalOverdue = $resultCount->fetch_assoc()['total_overdue'];

$response = [
    'overdueLoans' => $overdueLoans,
    'totalOverdue' => $totalOverdue
];

echo json_encode($response);
?>
