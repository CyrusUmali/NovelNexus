<?php

// Include database connection
include '../conn.php';

// Start session
session_start();

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from request body
    $data = file_get_contents("php://input");
    $postData = json_decode($data, true);

    // Extract data fields
    $first_name = $postData["first_name"];
    $last_name = $postData["last_name"];
    $email = $postData["email"];
    $password = $postData["password"];

    // Check if all fields are provided
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode(["error" => "All fields are required."]);
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement to check if email already exists
    $sqlCheck = "SELECT * FROM users WHERE email = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("s", $email);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    // If email already exists
    if ($resultCheck->num_rows > 0) {
        http_response_code(400);
        echo json_encode(["error" => "Email already in use."]);
        exit();
    }

    // Prepare SQL statement to insert the new user
    $sql = "INSERT INTO users (fname, lname, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);

    // Execute the statement
    if ($stmt->execute()) {
        // Data added successfully

        // Fetch user data for session
        $sqlFetch = "SELECT * FROM users WHERE email = ?";
        $stmtFetch = $conn->prepare($sqlFetch);
        $stmtFetch->bind_param("s", $email);
        $stmtFetch->execute();
        $resultFetch = $stmtFetch->get_result();
        // $userInfo = $resultFetch->fetch_assoc();
 
        // Fetch user information
        $userInfo = [];
        while ($row = $resultFetch->fetch_assoc()) {
            $userInfo[] = $row;
        }

        // Extract and store customer_id separately
        $userIds = array_column($userInfo, 'id');

        // Set session variables
        $_SESSION['userInfo'] = $userInfo;
        $_SESSION['id'] = $userIds;

        // Return success response
        http_response_code(200);
        echo json_encode(["success" => true, "userInfo" => $userInfo, "message" => "User registered and logged in successfully"]);
    } else {
        // Error inserting data into the database
        http_response_code(500);
        echo json_encode(["error" => "Internal Server Error"]);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
