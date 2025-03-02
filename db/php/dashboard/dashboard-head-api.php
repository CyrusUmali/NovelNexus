<?php
include '../../conn.php'; // Database connection
session_start();
header('Content-Type: application/json');

// Get the month parameter from the request (default to current month)
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');

// Prepare response array
$response = [];

// **1. Get Paid Members**
// $sqlPaidMembers = "
//     SELECT COUNT(DISTINCT u.id) AS paid_members
//     FROM users u
//     LEFT JOIN payment p ON u.id = p.user_id
//     WHERE p.payment_type = 2 AND MONTH(p.created_at) = ?
// ";


$sqlPaidMembers = "
    SELECT COUNT(DISTINCT u.id) AS paid_members
    FROM users u 
    WHERE u.plan_id != 1  
";

$stmtPaidMembers = $conn->prepare($sqlPaidMembers); 
$stmtPaidMembers->execute();
$stmtPaidMembers->bind_result($paid_members);
$stmtPaidMembers->fetch();
$stmtPaidMembers->close();
$response['paid_members'] = $paid_members;

// **2. Get Borrowed Books**
$sqlBorrowedBooks = "
    SELECT COUNT(*) AS borrowed_books
    FROM loans
    WHERE MONTH(loan_to) = ?
";

$stmtBorrowedBooks = $conn->prepare($sqlBorrowedBooks);
$stmtBorrowedBooks->bind_param('i', $month);
$stmtBorrowedBooks->execute();
$stmtBorrowedBooks->bind_result($borrowed_books);
$stmtBorrowedBooks->fetch();
$stmtBorrowedBooks->close();
$response['borrowed_books'] = $borrowed_books;

// **3. Get Users Created**
$sqlUsersCreated = "
    SELECT COUNT(*) AS users_created
    FROM users
    WHERE MONTH(created_at) = ?
";

$stmtUsersCreated = $conn->prepare($sqlUsersCreated);
$stmtUsersCreated->bind_param('i', $month);
$stmtUsersCreated->execute();
$stmtUsersCreated->bind_result($users_created);
$stmtUsersCreated->fetch();
$stmtUsersCreated->close();
$response['users_created'] = $users_created;

// **4. Get Book Popularity (Filtered by Month)**
$queryMostBorrowedBooks = "
    SELECT b.isbn AS book_isbn, b.title AS book_title, b.author, b.book_cover, COUNT(l.id) AS borrow_count
    FROM loans l
    JOIN books b ON l.book_id = b.id
    WHERE MONTH(l.loan_to) = ?  -- Added month filter here
    GROUP BY b.isbn
    ORDER BY borrow_count DESC
    LIMIT 7
";

$stmtMostBorrowed = $conn->prepare($queryMostBorrowedBooks);
$stmtMostBorrowed->bind_param('i', $month);
$stmtMostBorrowed->execute();
$resultMostBorrowed = $stmtMostBorrowed->get_result();

$most_borrowed_books = [];
while ($row = $resultMostBorrowed->fetch_assoc()) {
    $most_borrowed_books[] = $row;
}

// Fill up with random books if needed
if (count($most_borrowed_books) < 7) {
    $remainingCount = 7 - count($most_borrowed_books);

    $queryRandomBooks = "
        SELECT b.isbn AS book_isbn, b.title AS book_title, b.author, b.book_cover
        FROM books b
        WHERE b.isbn NOT IN (
            SELECT DISTINCT b.isbn
            FROM loans l
            JOIN books b ON l.book_id = b.id
            WHERE MONTH(l.loan_to) = ?  -- Added month filter here for random books as well
        )
        ORDER BY RAND()
        LIMIT $remainingCount
    ";
    $stmtRandomBooks = $conn->prepare($queryRandomBooks);
    $stmtRandomBooks->bind_param('i', $month);
    $stmtRandomBooks->execute();
    $resultRandomBooks = $stmtRandomBooks->get_result();

    while ($row = $resultRandomBooks->fetch_assoc()) {
        $most_borrowed_books[] = $row;
    }
}

$response['most_borrowed_books'] = $most_borrowed_books;

// Return the combined response as JSON
echo json_encode([
    'status' => 'success',
    'data' => $response
]);

$conn->close();
?>
