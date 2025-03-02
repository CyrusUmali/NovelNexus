<?php
include '../../conn.php'; // Database connection

// Set the timezone to Philippines
date_default_timezone_set('Asia/Manila');

// Get the page, limit, search, and month parameters from the request
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n'); // Default to current month
$offset = ($page - 1) * $limit;

// Define the mapping for payment types
$paymentTypeMapping = [
    1 => 'Fines',
    2 => 'Subscription'
];

// Base SQL query to fetch payments and their associated users
$sql = "
    SELECT 
        p.id AS payment_id,
        p.user_id,
        p.payment_type, 
        p.amount, 
        p.created_at AS payment_date,
        CONCAT(u.fname, ' ', u.lname) AS member
    FROM payment p
    LEFT JOIN users u ON p.user_id = u.id
    WHERE MONTH(p.created_at) = ?
";

// Add the search condition if a search query is provided (search on user name)
if (!empty($search)) {
    $sql .= " AND (u.fname LIKE ? OR u.lname LIKE ?)";
}

$sql .= " ORDER BY p.id DESC LIMIT ?, ?";
$stmt = $conn->prepare($sql);

// Bind parameters based on whether a search query is provided
if (!empty($search)) {
    $searchWildcard = "%" . $search . "%";
    $stmt->bind_param('issii', $month, $searchWildcard, $searchWildcard, $offset, $limit);
} else {
    $stmt->bind_param('iii', $month, $offset, $limit);
}

$stmt->execute();
$result = $stmt->get_result();

$payments = [];
while ($row = $result->fetch_assoc()) {
    // Replace payment_type with its corresponding label
    $row['payment_type'] = $paymentTypeMapping[$row['payment_type']] ?? 'Unknown';
    
    // Store the payment and associated user details
    $payments[] = $row;
}

// Query to get the total number of payments for pagination
$sqlCount = "SELECT COUNT(*) AS total_payments FROM payment p LEFT JOIN users u ON p.user_id = u.id WHERE MONTH(p.created_at) = ?";

// Add the search condition to the count query if a search query is provided
if (!empty($search)) {
    $sqlCount .= " AND (u.fname LIKE ? OR u.lname LIKE ?)";
    $stmtCount = $conn->prepare($sqlCount);
    $stmtCount->bind_param('iss', $month, $searchWildcard, $searchWildcard);
    $stmtCount->execute();
    $resultCount = $stmtCount->get_result();
} else {
    $stmtCount = $conn->prepare($sqlCount);
    $stmtCount->bind_param('i', $month);
    $stmtCount->execute();
    $resultCount = $stmtCount->get_result();
}

$totalPayments = $resultCount->fetch_assoc()['total_payments'];

$response = [
    'payments' => $payments,
    'totalPayments' => $totalPayments
];

echo json_encode($response);
?>
