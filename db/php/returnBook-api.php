<?php
include '../conn.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = file_get_contents("php://input");
    $postData = json_decode($data, true);

    $loan_id = $postData['loan_id']; // Use loan_id instead of book_id

    // Check if user info exists in the session
    if (isset($_SESSION['userInfo'][0])) {
        $userInfo = $_SESSION['userInfo'][0];
        $user_id = $userInfo['id'];
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'User not logged in'
        ]);
        exit();
    }

    $conn->begin_transaction();

    try {
        $returnDate = date("Y-m-d"); // Get the current date

        // Update the loan record to set status to 'Pending' and update the loan_to date
        $updateLoanQuery = "UPDATE loans SET status = 'Pending', loan_to = ? WHERE id = ? AND user_id = ?";
        $statement = $conn->prepare($updateLoanQuery);
        $statement->bind_param("sii", $returnDate, $loan_id, $user_id);

        if ($statement->execute()) {
            $conn->commit();
            echo json_encode(["success" => true, "message" => "Book returned successfully"]);
        } else {
            $conn->rollback();
            echo json_encode(["error" => "Failed to update loan status."]);
        }

        $statement->close();
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["error" => "An error occurred: " . $e->getMessage()]);
    }

    $conn->close();
}
?>
