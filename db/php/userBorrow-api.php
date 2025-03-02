<?php

// Include database connection
include '../conn.php'; // Assuming this file contains your database connection

// Start the session
session_start();

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the raw POST data
    $data = file_get_contents("php://input");

    // Check if data is empty
    if (empty($data)) {
        http_response_code(400);
        echo json_encode(["error" => "No data received."]);
        exit();
    }

    // Decode JSON data
    $postData = json_decode($data, true);

    // Check if user info exists in the session
    if (isset($_SESSION['userInfo'][0])) {
        $userInfo = $_SESSION['userInfo'][0];
        $user_id = $userInfo['id'];
        $plan_id = $userInfo['plan_id']; // Assuming the user's plan ID is stored in the session
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'User not logged in'
        ]);
        exit();
    }

    // Get data from POST request
    $book_id = isset($postData["book_id"]) ? $postData["book_id"] : null;
    $from_date = isset($postData["from_date"]) ? $postData["from_date"] : null;
    $to_date = isset($postData["to_date"]) ? $postData["to_date"] : null;

    // Check if all required fields are provided
    if (!$user_id || !$book_id || !$from_date || !$to_date) {
        http_response_code(400);
        echo json_encode(["error" => "All fields are required."]);
        exit();
    }

    // Validate that 'to_date' is later than 'from_date'
    if (strtotime($to_date) <= strtotime($from_date)) {
        http_response_code(400);
        echo json_encode([
            "error" => "'Loan to' date must be later than the 'Loan from' date."
        ]);
        exit();
    }

    // Plan Restrictions
    $maxBooks = 0;
    $borrowDaysLimit = 0;

    switch ($plan_id) {
        case 1: // Free Plan
            $maxBooks = 2; // Max 2 books for free plan
            $borrowDaysLimit = 7; // Borrow period max 7 days

            // Check if the book's access level is restricted for free plan users
            $accessCheckQuery = "
            SELECT accesslvl 
            FROM books 
            WHERE id = ?
        ";
            $accessCheckStmt = $conn->prepare($accessCheckQuery);
            $accessCheckStmt->bind_param("i", $book_id);
            $accessCheckStmt->execute();
            $accessResult = $accessCheckStmt->get_result()->fetch_assoc();
            $bookAccessLevel = $accessResult['accesslvl'] ?? null;
            $accessCheckStmt->close();

            if ($bookAccessLevel == 1) { // Access level 1 indicates paid access
                echo json_encode([
                    "error" => "This book is only available to paid plans. Please upgrade your plan to borrow this book."
                ]);
                exit();
            }

            // Check fines for Free Plan users
            $finesQuery = "SELECT SUM(fine) AS total_fines FROM loans WHERE user_id = ? AND paid = 0 AND status = 'Returned'";
            $finesStmt = $conn->prepare($finesQuery);
            $finesStmt->bind_param("i", $user_id);
            $finesStmt->execute();
            $finesResult = $finesStmt->get_result()->fetch_assoc();
            $totalFines = $finesResult['total_fines'] ?? 0;
            $finesStmt->close();

            if ($totalFines > 200) {
                echo json_encode([
                    "error" => "You have unpaid fines exceeding the limit of 200. Please pay the fines before borrowing more books."
                ]);
                exit();
            }

            break;

        case 2: // Standard Plan
            $maxBooks = 5; // Max 5 books for standard plan
            $borrowDaysLimit = 14; // Borrow period max 14 days
            break;

        case 3: // Premium Plan
            $maxBooks = 10; // Max 10 books for premium plan
            $borrowDaysLimit = 30; // Borrow period max 30 days
            break;

        default:
            echo json_encode(["error" => "Invalid plan."]);
            exit();
    }


    // If the user is on the Premium Plan (Plan ID 3), check for overdue books
    if ($plan_id == 3) {
        $overdueQuery = "
            SELECT COUNT(*) AS overdue_count 
            FROM loans 
            WHERE user_id = ? 
              AND status = 'Overdue'
        ";
        $overdueStmt = $conn->prepare($overdueQuery);
        $overdueStmt->bind_param("i", $user_id);
        $overdueStmt->execute();
        $overdueResult = $overdueStmt->get_result()->fetch_assoc();
        $overdueCount = $overdueResult['overdue_count'];
        $overdueStmt->close();

        // If the user has overdue books, prevent borrowing new ones
        if ($overdueCount > 0) {
            echo json_encode([
                "error" => "You have overdue books. Please return them before borrowing new books."
            ]);
            exit();
        }
    }

    // Check current borrowing count for the user
    $loanCountQuery = "
        SELECT COUNT(*) AS loan_count 
        FROM loans 
        WHERE user_id = ? 
          AND loan_to >= CURDATE() 
          AND status != 'Returned'
    ";
    $loanCountStmt = $conn->prepare($loanCountQuery);
    $loanCountStmt->bind_param("i", $user_id);
    $loanCountStmt->execute();
    $loanCountResult = $loanCountStmt->get_result()->fetch_assoc();
    $currentLoans = $loanCountResult['loan_count'];
    $loanCountStmt->close();

    if ($currentLoans >= $maxBooks) {
        echo json_encode([
            "error" => "You have reached the maximum number of books allowed for your plan."
        ]);
        exit();
    }

    // Check borrowing period limit
    $borrowDays = (strtotime($to_date) - strtotime($from_date)) / (60 * 60 * 24);
    if ($borrowDays > $borrowDaysLimit) {
        echo json_encode([
            "error" => "The borrowing period exceeds the limit for your plan."
        ]);
        exit();
    }

    // Begin a transaction to ensure data integrity
    $conn->begin_transaction();

    try {
        // Insert the book loan record
        $insertQuery = "
            INSERT INTO loans (user_id, book_id, loan_from, loan_to)
            VALUES (?, ?, ?, ?)
        ";
        $statement = $conn->prepare($insertQuery);
        $statement->bind_param("iiss", $user_id, $book_id, $from_date, $to_date);

        if ($statement->execute()) {
            // Update the book's availability to 'Loaned'
            $updateQuery = "
                UPDATE books 
                SET availability = 'Loaned' 
                WHERE id = ? 
            ";
            $updateStatement = $conn->prepare($updateQuery);
            $updateStatement->bind_param("i", $book_id);

            if ($updateStatement->execute()) {
                // Commit the transaction
                $conn->commit();
                echo json_encode(["success" => true, "message" => "Book loaned successfully"]);
            } else {
                // Rollback the transaction if the update failed
                $conn->rollback();
                echo json_encode(["error" => "Failed to update book availability."]);
            }
        } else {
            // Rollback the transaction if the insert failed
            $conn->rollback();
            echo json_encode(["error" => "Failed to save book loan data."]);
        }

        // Close the statements
        $statement->close();
        $updateStatement->close();
    } catch (Exception $e) {
        // Rollback the transaction if there's any exception
        $conn->rollback();
        echo json_encode(["error" => "An error occurred: " . $e->getMessage()]);
    }

    // Close the connection
    $conn->close();
}
