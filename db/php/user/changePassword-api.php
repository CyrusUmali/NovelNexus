<?php
// Include database connection
include '../../conn.php';
// Start session
session_start();

// Handle POST request for password change
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve raw POST data
    $data = file_get_contents("php://input");
    // Decode JSON data
    $postData = json_decode($data, true);

    // Debugging output
    error_log("POST data received: " . print_r($postData, true));

    // Retrieve current password, new password and confirm new password from decoded JSON
    $currentPassword = $postData["current_password"] ?? '';
    $newPassword = $postData["new_password"] ?? '';
    $confirmPassword = $postData["confirm_password"] ?? '';

    // Validate passwords
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        http_response_code(400);
        echo json_encode(["error" => "All fields are required."]);
        exit();
    }

    // Check if new password and confirm password match
    if ($newPassword !== $confirmPassword) {
        http_response_code(400);
        echo json_encode(["error" => "New password and confirm password do not match."]);
        exit();
    }

    // Check if user is logged in
    if (!isset($_SESSION['userInfo'][0])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'User not logged in'
        ]);
        exit();
    }

    // Get user ID from session
    $userInfo = $_SESSION['userInfo'][0];
    $userId = $userInfo['id'];

    // Retrieve current hashed password from the database
    $sql = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($hashedPasswordFromDB);
    $stmt->fetch();
    $stmt->close();

    // Verify the current password
    if (!password_verify($currentPassword, $hashedPasswordFromDB)) {
        http_response_code(401);
        echo json_encode(["error" => "Incorrect current password."]);
        exit();
    }

    // Check if the new password is the same as the current one
    if (password_verify($newPassword, $hashedPasswordFromDB)) {
        http_response_code(400);
        echo json_encode(["error" => "New password cannot be the same as the current password."]);
        exit();
    }

    // Hash the new password
    $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the password in the database
    $updateSql = "UPDATE users SET password = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("si", $newHashedPassword, $userId);
    $updateStmt->execute();

    if ($updateStmt->affected_rows > 0) {
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "Password updated successfully"]);
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Failed to update password"]);
    }

    $updateStmt->close();
}

// Close database connection
$conn->close();
?>
