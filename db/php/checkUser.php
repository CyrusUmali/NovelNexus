<?php

// Include database connection
include '../conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from request body
    $data = file_get_contents("php://input");
    $postData = json_decode($data, true);
    $email = $postData["email"];

    // Ensure email is provided
    if (empty($email)) {
        http_response_code(400);
        echo json_encode(["error" => "Email is required."]);
        exit();
    }

    // Check if email already exists in the users table
    $checkSql = "SELECT * FROM users WHERE email = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    // Return response based on whether the user exists
    if ($result->num_rows > 0) {
        echo json_encode(["userExists" => true]);
    } else {
        echo json_encode(["userExists" => false]);
    }

    // Close statement and connection
    $checkStmt->close();
    $conn->close();
}
?>
