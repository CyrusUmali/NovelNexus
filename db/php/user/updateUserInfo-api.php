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

// Check if all necessary fields are provided
if (!isset($_POST['first_name'], $_POST['last_name'], $_POST['phone_number'], $_POST['email'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing required parameters'
    ]);
    exit();
}

// Retrieve the form data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$phone_number = $_POST['phone_number'];
$email = $_POST['email'];

// Check if the photo URL is provided (from Cloudinary)
$photo_url = isset($_POST['user_photo']) ? $_POST['user_photo'] : null;

// SQL query to update user info in the database
$query = "UPDATE users SET fname = ?, lname = ?, phone = ?, email = ?";

// If a photo URL is provided, add it to the update query
if ($photo_url) {
    $query .= ", photo = ?";
}

// Ensure the user is updated
$query .= " WHERE id = ?";

// Prepare statement
$statement = $conn->prepare($query);

// Bind parameters for the prepared statement
if ($photo_url) {
    $statement->bind_param("sssssi", $first_name, $last_name, $phone_number, $email, $photo_url, $user_id);
} else {
    $statement->bind_param("ssssi", $first_name, $last_name, $phone_number, $email, $user_id);
}

// Execute the query
$statement->execute();

// Check if any rows were affected
if ($statement->affected_rows > 0) {
    echo json_encode([
        'status' => 'success',
        'message' => 'User information updated successfully'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'No changes made or user not found'
    ]);
}

$statement->close();
$conn->close();
?>
