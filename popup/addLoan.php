<?php include '../db/session.php'; ?>







<form class="addNewBook-container" id="loansPage">
    <div class="head">

        <div>
            <i class='bx bx-book-heart'></i>
            <span>Add New Entry</span>
        </div>


        <i class='bx bx-x' onclick="closePopup()"></i>
    </div>

    <div class="form-body">
        <div class="col-1">
            <div class="row-2">



                <!-- Dropdown Form -->
                <div class="item"  >
                    <label for="user_id">User</label>
                    <select name="user_id" id="user_id" class="form-control select2" required>
                        <option></option> <!-- Placeholder option -->
                        <?php
                        // Query to fetch user data (id, first name, last name, and role)
                        $users = $conn->query("SELECT id, fname, lname FROM users ORDER BY fname ASC");

                        // Check if the query was successful
                        if (!$users) {
                            // Display the error message in a more readable format
                            die("Query failed: " . $conn->error . " - SQL: SELECT id, fname, lname  FROM users ORDER BY fname ASC");
                        }

                        // Check if there are users in the result set
                        if ($users->num_rows > 0) :
                            while ($row = $users->fetch_array()) :
                        ?>
                                <option value="<?php echo htmlspecialchars($row['id']); ?>"

                                    data-fullname="<?php echo htmlspecialchars(ucwords($row['fname']) . ' ' . ucwords($row['lname'])); ?>">
                                    <?php echo ucwords($row['fname']) . ' ' . ucwords($row['lname']); ?>
                                </option>
                        <?php
                            endwhile;
                        else :
                            echo "<option>No users found</option>";
                        endif;
                        ?>
                    </select>
                </div>






                <!-- Dropdown Form -->
                <div class="item"  >
                    <label for="book_id">Book</label>
                    <select name="book_id" id="book_id" class="form-control select2" required>
                        <option></option> <!-- Placeholder option -->
                        <?php
                        // Query to fetch user data (id, first name, last name, and role) 
                        $books = $conn->query("SELECT id, title FROM books WHERE availability = 'available' ORDER BY id ASC");
                        // Check if the query was successful

                        // Check if there are users in the result set
                        if ($books->num_rows > 0) :
                            while ($row = $books->fetch_array()) :
                        ?>



                                <option value="<?php echo htmlspecialchars($row['id']); ?>"

                                    data-fullname="<?php echo htmlspecialchars(ucwords($row['title'])); ?>">
                                    <?php echo ucwords($row['title']); ?>
                                </option>
                        <?php
                            endwhile;
                        else :
                            echo "<option>No Books Available found</option>";
                        endif;
                        ?>
                    </select>
                </div>




            </div>


            <div class="row-2">

                <div class="item">
                    <label for="">From</label>
                    
                    <input type="date" name="loan_from" id="loan_from"   style="">
            
                </div>

                <div class="item">
                    <label for="">To</label>
                    <input type="date" name="loan_to" id="loan_to">
                </div>
            </div>




        </div>


    </div>

    <button type="button" id="addNewLoanButton" onclick="AddBookLoanClick()">Add New Entry</button>



</form>