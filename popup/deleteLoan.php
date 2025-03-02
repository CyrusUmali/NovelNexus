<?php
// Get the loan ID from the URL parameter
$loan_id = isset($_GET['loan_id']) ? intval($_GET['loan_id']) : null;

// Check if loan_id is valid
if (!$loan_id) {
    echo "Invalid loan ID.";
    exit();
}
?>

<form class="addNewBook-container" id="deleteLoan">
    <div class="head">
        <div>
            <i class='bx bx-trash-alt'></i>
            <span>Delete Confirmation</span>
        </div>
        <i class='bx bx-x' onclick="closePopup()"></i>
    </div>

    <div class="form-body" style="border: unset;">
        <span>Are you certain you wish to proceed with the deletion of the selected entry?</span>
    </div>

    <!-- Pass the loan ID directly as an argument to the confirmDeleteLoan function -->
    <button type="button" onclick="confirmDeleteLoan(<?php echo $loan_id; ?>)">
        Confirm
    </button>
</form>
