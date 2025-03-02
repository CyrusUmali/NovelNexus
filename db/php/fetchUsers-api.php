<?php
include '../conn.php'; // Database connection

// Get the page, limit, and search parameters from the request
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$offset = ($page - 1) * $limit;

// Base SQL query with optional search
$sql = "
    SELECT 
        u.id, 
        u.fname, 
        u.lname, 
        u.membership, 
        u.photo, 
        u.status, 
        u.phone, 
        u.email, 
        u.plan_id, 
        u.subscription_start, 
        u.subscription_end, 
        p.name, 
        COUNT(l.id) AS books_issued
    FROM users u
    LEFT JOIN loans l ON u.id = l.user_id
    LEFT JOIN plans p ON u.plan_id = p.id
";


// Add the search condition if a search query is provided
if (!empty($search)) {
    $sql .= " WHERE u.id LIKE ? OR u.fname LIKE ? OR u.lname LIKE ?";
}

$sql .= " GROUP BY u.id LIMIT ?, ?";
$stmt = $conn->prepare($sql);

if (!empty($search)) {
    $searchWildcard = "%" . $search . "%";
    $stmt->bind_param('sssii', $searchWildcard, $searchWildcard, $searchWildcard, $offset, $limit);
} else {
    $stmt->bind_param('ii', $offset, $limit);
}

$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

// Query to get the total number of users for pagination
$sqlCount = "SELECT COUNT(*) AS total_users FROM users";

// Add the search condition to the count query if a search query is provided
if (!empty($search)) {
    $sqlCount .= " WHERE id LIKE ? OR fname LIKE ? OR lname LIKE ?";
    $stmtCount = $conn->prepare($sqlCount);
    $stmtCount->bind_param('sss', $searchWildcard, $searchWildcard, $searchWildcard);
    $stmtCount->execute();
    $resultCount = $stmtCount->get_result();
} else {
    $resultCount = $conn->query($sqlCount);
}

$totalUsers = $resultCount->fetch_assoc()['total_users'];

$response = [
    'users' => $users,
    'totalUsers' => $totalUsers
];

echo json_encode($response);
?>
