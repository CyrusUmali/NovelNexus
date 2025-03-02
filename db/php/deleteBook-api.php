<?php

// Include database connection
include '../conn.php';

// Start the session
session_start();

// Handle DELETE request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the raw POST data
    $data = file_get_contents("php://input");

    // Check if data is empty
    if (empty($data)) {
        http_response_code(400);
        echo json_encode(["error" => "No data received."]);
        exit();
    }

    // Decode JSON data
    $postData = json_decode($data, true);

    // Retrieve book ID from decoded JSON
    $bookId = isset($postData["id"]) ? $postData["id"] : null;

    // Check if the book ID is provided
    if (!$bookId) {
        http_response_code(400);
        echo json_encode(["error" => "Book ID is required."]);
        exit();
    }

    // Prepare the delete query
    $deleteQuery = "DELETE FROM books WHERE id = ?";

    // Prepare the statement
    $statement = $conn->prepare($deleteQuery);

    // Bind the book ID parameter
    $statement->bind_param("i", $bookId);

    // Execute the statement
    if ($statement->execute()) {
        echo json_encode(["success" => true, "message" => "Book deleted successfully"]);
    } else {
        echo json_encode(["error" => "Failed to delete book."]);
    }

    // Close the statement and connection
    $statement->close();
    $conn->close();
}
