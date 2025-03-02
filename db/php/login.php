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

    // Retrieve email and password from decoded JSON
    $email = $postData["email"] ?? '';
    $password = $postData["password"] ?? '';

    // Validate email and password
    if (empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode(["error" => "Email and password are required."]);
        exit();
    }

    // Debugging output
    error_log("Email: $email, Password: [hidden for security]");

    // Prepare SQL statement
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows === 0) {
        http_response_code(401);
        echo json_encode(["error" => "Invalid email."]);
        exit();                                                                                                             
    }

    // Fetch user information
    $userInfo = [];
    while ($row = $result->fetch_assoc()) {
        $userInfo[] = $row;
    }

    // Debugging output
    error_log("User Info: " . print_r($userInfo, true));

    // Extract hashed password from the retrieved user information
    // Uncomment and use this section for password validation
    $hashedPasswordFromDB = $userInfo[0]['password'];
    if (!password_verify($password, $hashedPasswordFromDB)) {
        http_response_code(401);
        echo json_encode(["error" => "Invalid email or password."]);
        exit();
    }

    // Extract and store customer_id separately
    $userIds = array_column($userInfo, 'id');

    // User authenticated successfully
    http_response_code(200);

    // Set session variables
    $_SESSION['userInfo'] = $userInfo;
    $_SESSION['id'] = $userIds;

   
    echo json_encode(["success" => true, "userInfo" => $userInfo, "message" => "User authenticated successfully"]);

    // Close database connection
    $stmt->close();
}

// Close database connection
$conn->close();
