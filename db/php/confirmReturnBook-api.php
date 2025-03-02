<?php
// Include database connection
include '../conn.php'; // Assuming this file contains your database connection

// Set content type to JSON
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Invalid request method."]);
    exit();
}

// Get the raw POST data
$data = file_get_contents("php://input");
$requestData = json_decode($data, true);

// Check if necessary parameters are provided
$loanId = isset($requestData["id"]) ? intval($requestData["id"]) : null;
$status = isset($requestData["status"]) ? $requestData["status"] : null;

if (!$loanId || !$status) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "Loan ID and status are required."]);
    exit();
}

// Begin a transaction for data integrity
$conn->begin_transaction();

try {
    // Get the book ID associated with this loan
    $getBookQuery = "SELECT book_id FROM loans WHERE id = ?";
    $getBookStmt = $conn->prepare($getBookQuery);
    $getBookStmt->bind_param("i", $loanId);
    $getBookStmt->execute();
    $getBookStmt->bind_result($bookId);
    $getBookStmt->fetch();
    $getBookStmt->close();

    if (!$bookId) {
        throw new Exception("Loan record not found.");
    }

    // Update the loan record
    $updateLoanQuery = "UPDATE loans SET status = ? WHERE id = ?";
    $updateLoanStmt = $conn->prepare($updateLoanQuery);
    $updateLoanStmt->bind_param("si", $status, $loanId);
    if (!$updateLoanStmt->execute()) {
        throw new Exception("Failed to update loan record.");
    }
    $updateLoanStmt->close();

    // Update the book's availability if the loan status is "Returned"
    if ($status === 'Returned') {
        $updateBookQuery = "UPDATE books SET availability = 'Available' WHERE id = ?";
        $updateBookStmt = $conn->prepare($updateBookQuery);
        $updateBookStmt->bind_param("i", $bookId);
        if (!$updateBookStmt->execute()) {
            throw new Exception("Failed to update book availability.");
        }
        $updateBookStmt->close();
    }

    // Commit transaction
    $conn->commit();
    echo json_encode(["success" => true, "message" => "Loan updated and book availability set to 'Available'."]);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => $e->getMessage()]);
}

// Close the database connection
$conn->close();
?>
