<form class="addNewBook-container" id="deleteBook">


    <div class="head">

        <div>
        <i class='bx bx-trash-alt'></i>
            <span>Delete Confirmaton</span>
        </div>


        <i class='bx bx-x' onclick="closePopup()"></i>
    </div>

    <div class="form-body" style="border: unset;">

        <span>
            "Are you certain you wish to proceed with the deletion of the selected entry?"
        </span>


    </div>

    <button type="button" onclick="confirmDeleteClick()">
        Confirm
    </button>

</form>