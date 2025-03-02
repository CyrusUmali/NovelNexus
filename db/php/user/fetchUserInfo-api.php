<?php
include '../../conn.php';
session_start();

header("Content-Type: application/json"); // Set the response to JSON format

// Check if user info exists in the session
if (!isset($_SESSION['userInfo'][0])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'User not logged in'
    ]);
    exit();
}

$userInfo = $_SESSION['userInfo'][0];
$user_id = $userInfo['id'];

// SQL query to fetch user information based on the session user_id
$query = "
    SELECT 
        id, 
        fname, 
        lname, 
        email, 
        phone ,
        photo
    FROM users 
    WHERE id = ?
";

$statement = $conn->prepare($query);
$statement->bind_param("i", $user_id); // Bind the user_id parameter
$statement->execute();
$result = $statement->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode([
        'status' => 'success',
        'user' => [
            'id' => $user['id'],
            'fname' => $user['fname'],
            'lname' => $user['lname'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'photo' => $user['photo']
        ]
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'User not found'
    ]);
}

$statement->close();
$conn->close();
