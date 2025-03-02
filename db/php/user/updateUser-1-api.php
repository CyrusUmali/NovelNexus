<?php
include '../../conn.php'; // Database connection

// Parse the incoming JSON request body
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!isset($data['id']) || !isset($data['status']) || !isset($data['plan_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$id = $data['id'];
$status = $data['status'];
$plan_id = $data['plan_id'];
$plan_start = isset($data['plan_start']) ? $data['plan_start'] : null;
$plan_end = isset($data['plan_end']) ? $data['plan_end'] : null;

// If plan_id is 1, set plan_start and plan_end to NULL
if ($plan_id == 1) {
    $plan_start = null;
    $plan_end = null;
}

// Update the user's data
$sql = "
    UPDATE users
    SET status = ?, plan_id = ?, subscription_start = ?, subscription_end = ?
    WHERE id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sissi', $status, $plan_id, $plan_start, $plan_end, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update user']);
}
?>
