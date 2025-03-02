<?php

// Include database connection
include '../conn.php';

// Start session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from request body
    $data = file_get_contents("php://input");
    $postData = json_decode($data, true);

    $first_name = $postData["first_name"];
    $last_name = $postData["last_name"];
    $email = $postData["email"];
    $password = $postData["password"] ?? null;
    $google_signup = $postData["google_signup"] ?? false;
    $picture = $postData["picture"] ?? null;  // Optional: URL or path to profile picture

    // Ensure required fields are present
    if (empty($first_name) || empty($last_name) || empty($email)) {
        http_response_code(400);
        echo json_encode(["error" => "First name, last name, and email are required."]);
        exit();
    }

    // Check if email already exists
    $checkSql = "SELECT * FROM users WHERE email = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        http_response_code(409);
        echo json_encode(["error" => "User with this email already exists."]);
        exit();
    }

    // Handle Google OAuth sign-up (skip password)
    if ($google_signup) {
        // Insert new user without password for Google OAuth
        $sql = "INSERT INTO users (fname, lname, email, photo) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $first_name, $last_name, $email, $picture);
    } 

    // Execute the statement
    if ($stmt->execute()) {
        // After successful insertion, fetch user data to start session
        $userSql = "SELECT * FROM users WHERE email = ?";
        $userStmt = $conn->prepare($userSql);
        $userStmt->bind_param("s", $email);
        $userStmt->execute();
        $userResult = $userStmt->get_result();
        // $userInfo = $userResult->fetch_assoc();

         // Fetch user information
    $userInfo = [];
    while ($row = $userResult->fetch_assoc()) {
        $userInfo[] = $row;
    }

     // Extract and store customer_id separately
     $userIds = array_column($userInfo, 'id');

        // Set session variables
        $_SESSION['userInfo'] = $userInfo;
        $_SESSION['id'] = $userIds;

        // Send success response
        http_response_code(200);
        echo json_encode(["success" => true, "userInfo" => $userInfo, "message" => "User registered and logged in successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Internal Server Error"]);
    }

    // Close statements and connection
    $stmt->close();
    $conn->close();
}
?>
