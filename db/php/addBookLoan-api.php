<?php

// Include database connection
include '../conn.php'; // Assuming this file contains your database connection

// Start the session
session_start();

// Handle POST request
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

    // Retrieve specific fields from decoded JSON
    $user_id = isset($postData["user_id"]) ? $postData["user_id"] : null;
    $book_id = isset($postData["book_id"]) ? $postData["book_id"] : null;
    $from_date = isset($postData["from_date"]) ? $postData["from_date"] : null;
    $to_date = isset($postData["to_date"]) ? $postData["to_date"] : null;

    // Check if all required fields are provided
    if (!$user_id || !$book_id || !$from_date || !$to_date) {
        http_response_code(400);
        echo json_encode(["error" => "All fields are required."]);
        exit();
    }

    // Begin a transaction to ensure data integrity
    $conn->begin_transaction();

    try {
        // Insert the book loan record
        $insertQuery = "
            INSERT INTO loans (user_id, book_id, loan_from, loan_to)
            VALUES (?, ?, ?, ?)
        ";
        $statement = $conn->prepare($insertQuery);
        $statement->bind_param("iiss", $user_id, $book_id, $from_date, $to_date);

        if ($statement->execute()) { 
            $updateQuery = "
                UPDATE books 
                SET availability = 'Loaned' 
                WHERE id = ?
            ";
            $updateStatement = $conn->prepare($updateQuery);
            $updateStatement->bind_param("i", $book_id);

            if ($updateStatement->execute()) {
                // Commit the transaction
                $conn->commit();
                echo json_encode(["success" => true, "message" => "Book loaned successfully"]);
            } else {
                // Rollback the transaction if the update failed
                $conn->rollback();
                echo json_encode(["error" => "Failed to update book availability."]);
            }
        } else {
            // Rollback the transaction if the insert failed
            $conn->rollback();
            echo json_encode(["error" => "Failed to save book loan data."]);
        }
        
        // Close the statements
        $statement->close();
        $updateStatement->close();

    } catch (Exception $e) {
        // Rollback the transaction if there's any exception
        $conn->rollback();
        echo json_encode(["error" => "An error occurred: " . $e->getMessage()]);
    }

    // Close the connection
    $conn->close();
}
?>
