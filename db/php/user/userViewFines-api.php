<?php
include '../../conn.php'; // Database connection
session_start(); // Start session to access user info

// Check if user info exists in the session
if (isset($_SESSION['userInfo'][0])) {
    $userInfo = $_SESSION['userInfo'][0];
    $user_id = $userInfo['id'];

    // SQL query to get overdue loans with fines not 0 and unpaid for a specific user
    $sql = "
        SELECT 
            u.id AS user_id, 
            CONCAT(u.fname, ' ', u.lname) AS member, 
            b.id AS book_id, 
            b.title AS book_title, 
            b.book_cover,
            b.author, 
            l.loan_from, 
            l.loan_to, 
            l.status, 
            l.fine,
            DATEDIFF(CURDATE(), l.loan_to) AS days_overdue
        FROM loans l
        JOIN users u ON l.user_id = u.id
        JOIN books b ON l.book_id = b.id
        WHERE 
            l.status = 'Returned' 
            AND l.user_id = ? 
            AND l.fine > 0 
            AND l.paid = 0
        ORDER BY l.loan_to DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $overdueLoans = [];
    while ($row = $result->fetch_assoc()) {
        $overdueLoans[] = $row;
    }

    $response = [
        'overdueLoans' => $overdueLoans
    ];

    echo json_encode($response);
} else {
    // If user info is not in the session, return an error response
    echo json_encode([
        'error' => 'User not logged in or session expired'
    ]);
}
?>
