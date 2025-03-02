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

    // Check if it's a Google Sign-In request
    if (isset($postData["google_signin"]) && $postData["google_signin"] === true) {
        $email = $postData["email"];

        // Check if the email exists in the database
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            http_response_code(401);
            echo json_encode(["error" => "User not found."]);
            exit();
        }

        // Fetch user information
        $userInfo = [];
        while ($row = $result->fetch_assoc()) {
            $userInfo[] = $row;
        }

        // Set session variables consistently
        $_SESSION['userInfo'] = $userInfo;
        $_SESSION['id'] = array_column($userInfo, 'id');
        $_SESSION['testValue'] = 'Test Value';

        // Debugging output
        error_log("Session userInfo: " . print_r($_SESSION['userInfo'], true));
        error_log("Session userIds: " . print_r($_SESSION['id'], true));

        echo json_encode([
            "success" => true,
            "userInfo" => $userInfo,
            "message" => "User signed in successfully"
        ]);
    }

    // Close database connection
    $stmt->close();
}

$conn->close();
