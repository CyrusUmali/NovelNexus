<?php
// Include database connection
include '../conn.php';
// Start session
session_start();

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve raw POST data
    $data = file_get_contents("php://input");
    // Decode JSON data
    $postData = json_decode($data, true);

    // Debugging output
    error_log("POST data received: " . print_r($postData, true));

    // Retrieve username and password from decoded JSON
    $username = $postData["username"] ?? '';
    $password = $postData["password"] ?? '';

    // Validate username and password
    if (empty($username) || empty($password)) {
        http_response_code(400);
        echo json_encode(["error" => "Username and password are required."]);
        exit();
    }

    // Debugging output
    error_log("Username: $username, Password: [hidden for security]");

    // Prepare SQL statement
    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows === 0) {
        http_response_code(401);
        echo json_encode(["error" => "Invalid username."]);
        exit();
    }

    // Fetch admin information
    $adminInfo = $result->fetch_assoc();

    // Debugging output
    error_log("Admin Info: " . print_r($adminInfo, true));

    // Check the password (plaintext comparison)
    if ($password !== $adminInfo['password']) {
        http_response_code(401);
        echo json_encode([
            "error" => "Invalid username or password.",
            "debug" => [
                "password_from_db" => $adminInfo['password'],
                "password_entered" => $password
            ]
        ]);
        exit();
    }

    // Admin authenticated successfully
    http_response_code(200);

    // Set session variables for admin
    $_SESSION['admin'] = $adminInfo;

    echo json_encode(["success" => true, "adminInfo" => $adminInfo, "message" => "Admin authenticated successfully"]);

    // Close database connection
    $stmt->close();
}

// Close database connection
$conn->close();
