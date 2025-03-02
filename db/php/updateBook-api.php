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
    $bookId = isset($postData["id"]) ? $postData["id"] : null; // Get the book ID
    $title = isset($postData["title"]) ? $postData["title"] : null;
    $author = isset($postData["author"]) ? $postData["author"] : null;
    $isbn = isset($postData["isbn"]) ? $postData["isbn"] : null;
    $category_ids = isset($postData["category_ids"]) ? $postData["category_ids"] : null; // Changed to category_ids for multiple categories
    $book_cover = isset($postData["book_cover"]) ? $postData["book_cover"] : null;
    $about = isset($postData["about"]) ? $postData["about"] : null;
    $accesslvl = isset($postData["accesslvl"]) ? $postData["accesslvl"] : null;

    // Check if the book ID is provided
    if (!$bookId) {
        http_response_code(400);
        echo json_encode(["error" => "Book ID is required."]);
        exit();
    }

    // Check if all required fields are provided
    if (!$title || !$author || !$isbn || !$category_ids || !$about  ) {
        http_response_code(400);
        echo json_encode(["error" => "All fields except book_cover are required."]);
        exit();
    }

    // Start a transaction to ensure both book and book-category relations are updated correctly
    $conn->begin_transaction();

    try {
        // Prepare the update query for the book
        $updateQuery = "
            UPDATE books 
            SET title = ?, author = ?, isbn = ?, book_cover = ?, about = ? , accesslvl =?
            WHERE id = ?
        ";
        $statement = $conn->prepare($updateQuery);

        if ($book_cover) {
            // If a new book cover is provided, bind the new value
            $statement->bind_param("sssssii", $title, $author, $isbn, $book_cover, $about,$accesslvl , $bookId);
        } else {
            // If no new book cover is provided, exclude it from the update
            $updateQueryWithoutCover = "
                UPDATE books 
                SET title = ?, author = ?, isbn = ?, about = ? , accesslvl = ?
                WHERE id = ?
            ";
            $statement = $conn->prepare($updateQueryWithoutCover);
            $statement->bind_param("ssssii", $title, $author, $isbn, $about, $accesslvl , $bookId);
        }

        // Execute the statement to update book details
        if (!$statement->execute()) {
            throw new Exception("Failed to update book data.");
        }

        // Prepare the delete query for existing categories
        $deleteCategoriesQuery = "DELETE FROM bookCategories WHERE book_id = ?";
        $deleteStatement = $conn->prepare($deleteCategoriesQuery);
        $deleteStatement->bind_param("i", $bookId);
        if (!$deleteStatement->execute()) {
            throw new Exception("Failed to delete old categories.");
        }

        // Prepare the insert query for the bookCategories table (many-to-many relationship)
        $insertCategoryQuery = "
            INSERT INTO bookCategories (book_id, category_id)
            VALUES (?, ?)
        ";
        $categoryStatement = $conn->prepare($insertCategoryQuery);

        // Insert each selected category
        foreach ($category_ids as $category_id) {
            $categoryStatement->bind_param("ii", $bookId, $category_id); // Bind book_id and category_id
            if (!$categoryStatement->execute()) {
                throw new Exception("Failed to save category association.");
            }
        }

        // Commit the transaction if everything is successful
        $conn->commit();

        // Respond with success
        echo json_encode(["success" => true, "message" => "Book updated successfully"]);

        // Close the statement
        $categoryStatement->close();
        $deleteStatement->close();
        $statement->close();

    } catch (Exception $e) {
        // Rollback the transaction if an error occurs
        $conn->rollback();

        // Respond with error
        echo json_encode(["error" => $e->getMessage()]);
    }

    // Close the connection
    $conn->close();
}
?>
