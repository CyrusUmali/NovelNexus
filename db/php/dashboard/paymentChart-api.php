<?php
// Include database connection
include '../../conn.php';
session_start();
header('Content-Type: application/json');

// Get the month parameter from the request (default to current month)
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');

// Fetch the payment data for the user (grouped by payment_type) for the selected month
$stmt = $conn->prepare("
    SELECT payment_type, SUM(amount) AS total_amount
    FROM payment 
    WHERE MONTH(created_at) = ?
    GROUP BY payment_type
"); 
$stmt->bind_param('i', $month);
$stmt->execute();
$stmt->bind_result($payment_type, $total_amount);

$labels = [];
$data = [];

// Map payment_type to labels
$labelsMap = [
    1 => 'Subscription',
    2 => 'Fines'
];

while ($stmt->fetch()) {
    $labels[] = isset($labelsMap[$payment_type]) ? $labelsMap[$payment_type] : 'Unknown';
    $data[] = (float) $total_amount;
}

$stmt->close();

// Return the payment data as JSON
echo json_encode([
    'status' => 'success', 
    'labels' => $labels,
    'data' => $data
]);

$conn->close();
?>
