<?php include '../db/session.php'; ?>


<?php
// Get the loan ID from the URL parameter
$loan_id = isset($_GET['loan_id']) ? intval($_GET['loan_id']) : null;

// Check if loan_id is valid
if (!$loan_id) {
    echo "Invalid loan_id.";
    exit();
}
?>


<form class="addNewBook-container" id="returnBook">


    <div class="head">

        <div>
        <i class='bx bx-trash-alt'></i>
            <span> Return Confirmaton</span>
        </div>


        <i class='bx bx-x' onclick="closePopup()"></i>
    </div>

    <div class="form-body" style="border: unset;">

        <span>
            "Are you certain you wish to proceed with the book Return?"
        </span>


    </div>

    <button type="button" onclick="confirmReturnClick(<?php echo $loan_id; ?>)">
        Confirm
    </button>

</form>