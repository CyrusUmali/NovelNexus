<?php
// Include database connection
include '../db/conn.php';

// Check if 'loan_id' is set in the URL
if (isset($_GET['loan_id']) && is_numeric($_GET['loan_id'])) {
    $loanId = $_GET['loan_id'];
    
    
   
} else {
    echo "No valid loan ID provided.";
    exit();
}

// Close the database connection
$conn->close();
?>



<form class="addNewBook-container" id="rejectBookReturn">


    <div class="head">

        <div>
        <i class='bx bx-check-square'></i>
            <span>Reject Confirmaton</span>
        </div>


        <i class='bx bx-x' onclick="closePopup()"></i>
    </div>

    <div class="form-body" style="border: unset; display:flex; justify-content:center ;
    align-items:center;flex-direction:column">

        <span>
           Send this message to the User 
        </span>

        <textarea name="" id="rejectNotif"></textarea>


    </div>

    <button type="button" onclick="confirmRejectClick(<?php echo $loanId; ?> )">
    Confirm
</button>


</form>