<?php include '../db/session.php'; ?>


<?php
// Get the loan ID from the URL parameter
$review_id = isset($_GET['review_id']) ? intval($_GET['review_id']) : null;

// Check if loan_id is valid
if (!$review_id) {
    echo "Invalid book ID.";
    exit();
}
?>


<form class="addNewBook-container" id="deleteReview">


    <div class="head">

        <div>
        <i class='bx bx-trash-alt'></i>
            <span>Delete Confirmaton</span>
        </div>


        <i class='bx bx-x' onclick="closePopup()"></i>
    </div>

    <div class="form-body" style="border: unset;">

        <span>
            "Are you certain you wish to delete your Review?"
        </span>


    </div>

    <button type="button" onclick="confirmDeleteReview(<?php echo $review_id; ?>)">
        Confirm
    </button>

</form>