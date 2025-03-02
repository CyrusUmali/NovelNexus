<?php
// Include database connection
include '../db/conn.php';

// Check if 'loan_id' is set in the URL
if (isset($_GET['loan_id']) && is_numeric($_GET['loan_id'])) {
    $loanId = $_GET['loan_id'];

    // Prepare the SQL query to retrieve the loan data based on the provided loan_id
    $sql = "
        SELECT loans.*, users.fname AS user_fname, users.lname AS user_lname, books.title AS book_title 
        FROM loans 
        JOIN users ON loans.user_id = users.id
        JOIN books ON loans.book_id = books.id
        WHERE loans.id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $loanId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a loan was found
    if ($result->num_rows > 0) {
        // Fetch the loan data
        $loan = $result->fetch_assoc();
    } else {
        echo "Loan not found.";
        exit();
    }

    // Close the statement
    $stmt->close();
} else {
    echo "No valid loan ID provided.";
    exit();
}

// Close the database connection
$conn->close();
?>

<?php include '../db/session.php'; ?>

<form class="addNewBook-container" id="editLoanPage">
    <div class="head">
        <div>
            <i class='bx bx-book-heart'></i>
            <span>Loan Details</span>
        </div>
        <i class='bx bx-x' onclick="closePopup()"></i>
    </div>

    <div class="form-body">
        <div class="col-1">
            <div class="row-2">
                <!-- User Information -->
                <div class="item">
                    <label for="user_id">User</label>
                    <input type="text" class="form-control"
                        value="<?php echo htmlspecialchars($loan['user_fname'] . ' ' . $loan['user_lname']); ?>"
                        disabled>
                </div>

                <!-- Book Information -->
                <div class="item">
                    <label for="book_id">Book</label>
                    <input type="text" class="form-control"
                        value="<?php echo htmlspecialchars($loan['book_title']); ?>"
                        disabled>
                </div>
            </div>

            <div class="row-2">
                <!-- Loan Dates - Editable -->
                <div class="item">
                    <label for="loan_from">From</label>
                  
                    <input type="date" name="loan_from" id="loan_from"  
                        value="<?php echo htmlspecialchars($loan['loan_from']); ?>">
                </div>

                <div class="item">
                    <label for="loan_to">To</label>
                    <input type="date" name="loan_to" id="loan_to"
                        value="<?php echo htmlspecialchars($loan['loan_to']); ?>">
                </div>
            </div>
        </div>

        <div class="col-2">
            <div class="row-2">
                <!-- Loan Status -->
                <div class="item">
                    <label for="loanStatus">Status</label>
                    <select id="loanStatus">
                        <option value="Loaned" <?php echo ($loan['status'] == 'Loaned') ? 'selected' : ''; ?>>Loaned</option>
                        <option value="Returned" <?php echo ($loan['status'] == 'Returned') ? 'selected' : ''; ?>>Returned</option>
                        <option value="Overdue" <?php echo ($loan['status'] == 'Overdue') ? 'selected' : ''; ?>>Overdue</option>
                    </select>

                </div>
            </div>

            <div class="row-2">
                <!-- Loan Fine -->
                <div class="item">
                    <label for="loanFine">Loan Fine</label>
                    <input type="text" id="loanFine" placeholder="Enter loan fine"
                        value="<?php echo htmlspecialchars($loan['fine'] ?? ''); ?>">
                </div>
            </div>
        </div>
    </div>

    <button type="button" id="saveLoanButton" onclick="updateLoanClick(<?php echo $loanId; ?>)">Save Changes</button>
</form>