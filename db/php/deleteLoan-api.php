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

// Check if loan_id is provided
$loan_id = isset($requestData["id"]) ? intval($requestData["id"]) : null;
if (!$loan_id) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "Loan ID is required."]);
    exit();
}

// Begin a transaction for data integrity
$conn->begin_transaction();

try {
    // Get the book ID associated with this loan
    $getBookQuery = "SELECT book_id FROM loans WHERE id = ?";
    $getBookStmt = $conn->prepare($getBookQuery);
    $getBookStmt->bind_param("i", $loan_id);
    $getBookStmt->execute();
    $getBookStmt->bind_result($book_id);
    $getBookStmt->fetch();
    $getBookStmt->close();

    if (!$book_id) {
        throw new Exception("Loan record not found.");
    }

    // Delete the loan record
    $deleteLoanQuery = "DELETE FROM loans WHERE id = ?";
    $deleteLoanStmt = $conn->prepare($deleteLoanQuery);
    $deleteLoanStmt->bind_param("i", $loan_id);
    if (!$deleteLoanStmt->execute()) {
        throw new Exception("Failed to delete loan record.");
    }
    $deleteLoanStmt->close();

    // Update the book's availability to 'available'
    $updateBookQuery = "UPDATE books SET availability = 'Available' WHERE id = ?";
    $updateBookStmt = $conn->prepare($updateBookQuery);
    $updateBookStmt->bind_param("i", $book_id);
    if (!$updateBookStmt->execute()) {
        throw new Exception("Failed to update book availability.");
    }
    $updateBookStmt->close();

    // Commit transaction
    $conn->commit();
    echo json_encode(["success" => true, "message" => "Loan deleted and book availability updated."]);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => $e->getMessage()]);
}

// Close the database connection
$conn->close();
?>
