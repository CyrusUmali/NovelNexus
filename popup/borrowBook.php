<?php include '../db/session.php'; ?>


<?php
// Get the loan ID from the URL parameter
$book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : null;

// Check if loan_id is valid
if (!$book_id) {
    echo "Invalid book ID.";
    exit();
}
?>




<form class="addNewBook-container" id="borrowBook">
    <div class="head">

        <div>
            <i class='bx bx-book-heart'></i>
            <span>Borrow this Book</span>
        </div>


        <i class='bx bx-x' onclick="closePopup()"></i>
    </div>

    <div class="form-body">
        <div class="col-1">
             

            <div class="row-2">

                <div class="item">
                    <label for="">From</label>
                    <input type="date" name="loan_from" id="loan_from" readonly style="">
                </div>

                <div class="item">
                    <label for="">To</label>
                    <input type="date" name="loan_to" id="loan_to">
                </div>
            </div>




        </div>


    </div>

    <button type="button" id="addNewLoanButton" onclick="confirmBorrowBook(<?php echo $book_id; ?>)">Confirm</button>



</form>