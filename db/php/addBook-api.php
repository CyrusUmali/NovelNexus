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
    $title = isset($postData["title"]) ? $postData["title"] : null;
    $author = isset($postData["author"]) ? $postData["author"] : null;
    $isbn = isset($postData["isbn"]) ? $postData["isbn"] : null;
    $category_ids = isset($postData["category_ids"]) ? $postData["category_ids"] : null; // Changed to category_ids for multiple categories
    $book_cover = isset($postData["book_cover"]) ? $postData["book_cover"] : null;
    $about = isset($postData["about"]) ? $postData["about"] : null;
    $accesslvl = isset($postData["accesslvl"]) ? $postData["accesslvl"] : null;

    // Check if all required fields are provided
    if (!$title || !$author || !$isbn || !$category_ids || !$book_cover || !$about ) {
        http_response_code(400);
        echo json_encode(["error" => "All fields are required."]);
        exit();
    }

    // Start a transaction to ensure both book and book-category relations are inserted correctly
    $conn->begin_transaction();

    try {
        // Prepare and execute the insert query for the book
        $insertQuery = "
            INSERT INTO books (title, author, isbn, book_cover, about ,accesslvl)
            VALUES (?, ?, ?, ?, ? ,?)
        ";
        $statement = $conn->prepare($insertQuery);
        $statement->bind_param("sssssi", $title, $author, $isbn, $book_cover, $about , $accesslvl);

        if ($statement->execute()) {
            $book_id = $statement->insert_id; // Get the inserted book ID
        } else {
            throw new Exception("Failed to save book data.");
        }

        // Prepare the insert query for the bookCategories table (many-to-many relationship)
        $insertCategoryQuery = "
            INSERT INTO bookCategories (book_id, category_id)
            VALUES (?, ?)
        ";
        $categoryStatement = $conn->prepare($insertCategoryQuery);

        // Insert each selected category
        foreach ($category_ids as $category_id) {
            $categoryStatement->bind_param("ii", $book_id, $category_id); // Bind book_id and category_id
            if (!$categoryStatement->execute()) {
                throw new Exception("Failed to save category association.");
            }
        }

        // Commit the transaction if everything is successful
        $conn->commit();

        // Respond with success
        echo json_encode(["success" => true, "message" => "Book saved successfully", "book_id" => $book_id]);

        // Close the statement
        $categoryStatement->close();
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
